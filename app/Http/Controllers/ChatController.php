<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:1000',
                'user_id' => 'nullable|integer',
                'user_role' => 'nullable|string|in:admin,staff,bus_owner,user'
            ]);

            $message = $request->input('message');
            $sessionId = $request->input('session_id', 'default');
            $userId = $request->input('user_id');
            $userRole = $request->input('user_role');

            // Xác thực quyền admin thực sự
            $isAdmin = $this->verifyAdminAccess($userId, $userRole);
            $isStaff = $this->verifyStaffAccess($userId, $userRole);
            $isBusOwner = $this->verifyBusOwnerAccess($userId, $userRole);

            Log::info('Chat request', [
                'message' => $message,
                'session' => $sessionId,
                'user_id' => $userId,
                'user_role' => $userRole,
                'is_admin' => $isAdmin,
                'is_staff' => $isStaff,
                'is_bus_owner' => $isBusOwner,
                'admin_verified' => $isAdmin,
                'staff_verified' => $isStaff,
                'bus_owner_verified' => $isBusOwner
            ]);

            // Kiểm tra nếu user_role là admin nhưng không được xác thực
            if ($userRole === 'admin' && !$isAdmin) {
                Log::warning('Unauthorized admin access attempt', [
                    'user_id' => $userId,
                    'user_role' => $userRole,
                    'message' => $message
                ]);

                return response()->json([
                    'success' => false,
                    'error' => 'Bạn không có quyền truy cập thông tin admin. Vui lòng đăng nhập với tài khoản admin hợp lệ.'
                ], 403);
            }

            // Phân tích ý định người dùng và lấy dữ liệu từ database
            $contextData = $this->getContextData($message, $isAdmin, $isStaff, $isBusOwner, $userId);

            // Tạo prompt với context từ database
            $systemPrompt = $this->buildEnhancedPrompt($contextData, $isAdmin, $userRole);

            $apiKey = env('GEMINI_API_KEY', 'AIzaSyAf1CCFAqfOowuQfkP0YoFb_PS5N6uJULg');
            $apiUrl = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key={$apiKey}";

            $requestData = [
                'contents' => [
                    [
                        'parts' => [['text' => $systemPrompt . "\n\nCâu hỏi khách: " . $message]]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 1000,
                    'topP' => 0.95,
                    'topK' => 40
                ]
            ];

            $response = Http::timeout(30)->post($apiUrl, $requestData);

            if ($response->failed()) {
                Log::error('Gemini API failed', ['status' => $response->status()]);
                return response()->json([
                    'success' => false,
                    'error' => 'Không thể kết nối AI. Vui lòng thử lại sau!'
                ], 500);
            }

            $data = $response->json();

            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'];

                Log::info('Chat response sent', ['length' => strlen($aiResponse)]);

                // Chuẩn bị dữ liệu trả về
                $responseData = [
                    'success' => true,
                    'content' => $aiResponse,
                    'timestamp' => now()->toIso8601String(),
                    'has_routes' => !empty($contextData['routes']) || !empty($contextData['nearby_routes'])
                ];

                // Thêm thông tin chuyến xe vào response
                if (!empty($contextData['routes'])) {
                    $responseData['routes'] = array_map(function ($route) {
                        return [
                            'id' => $route->id ?? 0, // Thêm ID chuyến xe
                            'diem_di' => $route->diem_di ?? '',
                            'tram_di' => $route->tram_di ?? '',
                            'diem_den' => $route->diem_den ?? '',
                            'tram_den' => $route->tram_den ?? '',
                            'ngay_di' => $route->ngay_di ?? date('Y-m-d'),
                            'gio_di' => $route->gio_di ?? '',
                            'gia_ve' => $route->gia_ve ?? 0,
                            'loai_xe' => $route->loai_xe ?? '',
                            'con_trong' => $route->con_trong ?? 0
                        ];
                    }, $contextData['routes']);
                }

                // Thêm thông tin chuyến gần nhất
                if (!empty($contextData['nearby_routes']) && !empty($contextData['nearby_routes']['routes'])) {
                    $responseData['nearby_routes'] = array_map(function ($route) {
                        return [
                            'id' => $route->id ?? 0, // Thêm ID chuyến xe
                            'diem_di' => $route->diem_di ?? '',
                            'tram_di' => $route->tram_di ?? '',
                            'diem_den' => $route->diem_den ?? '',
                            'tram_den' => $route->tram_den ?? '',
                            'ngay_di' => $route->ngay_di ?? date('Y-m-d'),
                            'gio_di' => $route->gio_di ?? '',
                            'gia_ve' => $route->gia_ve ?? 0,
                            'loai_xe' => $route->loai_xe ?? '',
                            'con_trong' => $route->con_trong ?? 0
                        ];
                    }, $contextData['nearby_routes']['routes']);
                }

                return response()->json($responseData);
            }

            return response()->json([
                'success' => false,
                'error' => 'AI không trả lời được. Vui lòng thử lại!'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Chat error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Có lỗi xảy ra. Vui lòng thử lại!'
            ], 500);
        }
    }

    /**
     * Xác thực quyền admin thực sự
     */
    private function verifyAdminAccess($userId, $userRole)
    {
        // Chỉ cho phép admin thực sự
        if ($userRole !== 'admin' || !$userId) {
            return false;
        }

        try {
            // Kiểm tra user có tồn tại và có role admin trong database
            $user = DB::table('users')
                ->where('id', $userId)
                ->where('role', 'admin')
                ->first();

            return $user !== null;
        } catch (\Exception $e) {
            Log::error('Admin verification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Xác thực quyền staff
     */
    private function verifyStaffAccess($userId, $userRole)
    {
        if (!in_array($userRole, ['admin', 'staff']) || !$userId) {
            return false;
        }

        try {
            $user = DB::table('users')
                ->where('id', $userId)
                ->whereIn('role', ['admin', 'staff'])
                ->first();

            return $user !== null;
        } catch (\Exception $e) {
            Log::error('Staff verification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Xác thực quyền bus owner
     */
    private function verifyBusOwnerAccess($userId, $userRole)
    {
        if (!in_array($userRole, ['admin', 'bus_owner']) || !$userId) {
            return false;
        }

        try {
            $user = DB::table('users')
                ->where('id', $userId)
                ->whereIn('role', ['admin', 'bus_owner'])
                ->first();

            return $user !== null;
        } catch (\Exception $e) {
            Log::error('Bus owner verification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Phân tích câu hỏi và lấy dữ liệu từ database
     */
    private function getContextData($message, $isAdmin = false, $isStaff = false, $isBusOwner = false, $userId = null)
    {
        $context = [
            'routes' => [],
            'bookings' => [],
            'intent' => 'general',
            'locations' => [],
            'price_range' => null,
            'admin_data' => [],
            'user_data' => [],
            'statistics' => [],
            'is_admin' => $isAdmin,
            'is_staff' => $isStaff,
            'is_bus_owner' => $isBusOwner
        ];

        // Danh sách địa điểm phổ biến (chuẩn hóa theo tên trong database)
        $locations = [
            'Hồ Chí Minh' => ['Sài Gòn', 'SG', 'HCM', 'TP.HCM', 'TPHCM', 'TP HCM'],
            'Hà Nội' => ['HN', 'Hanoi', 'Ha Noi'],
            'Lâm Đồng' => ['Đà Lạt', 'Da Lat', 'Dalat'],
            'Khánh Hòa' => ['Nha Trang', 'Nhatrang'],
            'Đà Nẵng' => ['Da Nang', 'Danang', 'DN'],
            'Bà Rịa - Vũng Tàu' => ['Vũng Tàu', 'Vung Tau', 'BR-VT'],
            'Cần Thơ' => ['Can Tho', 'CT'],
            'Thừa Thiên Huế' => ['Huế', 'Hue'],
            'Nghệ An' => ['Vinh'],
            'Hải Phòng' => ['Hai Phong', 'HP'],
            'An Giang' => ['AG'],
            'Bắc Ninh' => ['Bac Ninh', 'BN'],
            'Bình Dương' => ['Binh Duong', 'BD'],
            'Đồng Nai' => ['Dong Nai', 'DN'],
            'Long An' => []
        ];

        // Tìm địa điểm trong câu hỏi
        $messageLower = mb_strtolower($message, 'UTF-8');
        $foundLocations = [];

        foreach ($locations as $city => $aliases) {
            $patterns = array_merge([$city], $aliases);
            foreach ($patterns as $pattern) {
                if (stripos($messageLower, mb_strtolower($pattern, 'UTF-8')) !== false) {
                    $foundLocations[] = $city;
                    break;
                }
            }
        }

        $context['locations'] = array_unique($foundLocations);

        // Phát hiện giá tiền trong câu hỏi
        $priceDetected = false;
        if (preg_match('/(\d+)\s*(k|ngàn|nghìn|triệu|tr)/i', $message, $matches)) {
            $amount = (int) $matches[1];
            $unit = strtolower($matches[2]);

            if (in_array($unit, ['k', 'ngàn', 'nghìn'])) {
                $price = $amount * 1000;
            } elseif (in_array($unit, ['triệu', 'tr'])) {
                $price = $amount * 1000000;
            } else {
                $price = $amount;
            }

            // Tìm chuyến xe trong khoảng giá ±20%
            $minPrice = $price * 0.8;
            $maxPrice = $price * 1.2;
            $context['price_range'] = ['min' => $minPrice, 'max' => $maxPrice];
            $context['routes'] = $this->searchRoutesByPrice($minPrice, $maxPrice);
            $context['intent'] = 'price_search';
            $priceDetected = true;
        }

        // Phát hiện yêu cầu tra lịch sử đặt vé
        if (preg_match('/(lịch sử|lich su|vé của tôi|ve cua toi|đã đặt|da dat|đơn hàng|don hang)/i', $message)) {
            $context['intent'] = 'booking_history';

            // Tìm SĐT hoặc email trong câu hỏi
            if (preg_match('/(\d{10,11})/', $message, $phoneMatch)) {
                $context['bookings'] = $this->getBookingHistory('phone', $phoneMatch[1]);
            } elseif (preg_match('/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/', $message, $emailMatch)) {
                $context['bookings'] = $this->getBookingHistory('email', $emailMatch[1]);
            }
        }

        // Nếu chưa phát hiện giá và không phải tra lịch sử
        if (!$priceDetected && $context['intent'] !== 'booking_history') {
            // Nếu tìm thấy 2 địa điểm → có thể là hỏi về tuyến xe
            if (count($foundLocations) >= 2) {
                $context['intent'] = 'search_route';
                $context['routes'] = $this->searchRoutes($foundLocations[0], $foundLocations[1]);

                // Nếu không tìm thấy chuyến trực tiếp, tìm chuyến gần nhất
                if (empty($context['routes'])) {
                    $context['nearby_routes'] = $this->searchNearbyRoutes($foundLocations[0], $foundLocations[1]);
                    if (!empty($context['nearby_routes'])) {
                        $context['intent'] = 'nearby_route';
                    }
                }
            }
            // Nếu có 1 địa điểm → hỏi về chuyến từ/đến địa điểm đó
            elseif (count($foundLocations) == 1) {
                $context['intent'] = 'location_info';
                $context['routes'] = $this->getRoutesByLocation($foundLocations[0]);
            }
        }

        // Phát hiện ý định khác
        if (preg_match('/(giá|bao nhiêu|chi phí|tiền)/i', $message) && !$priceDetected) {
            $context['intent'] = 'price_inquiry';
        }
        if (preg_match('/(đặt vé|book|mua vé)/i', $message)) {
            $context['intent'] = 'booking';
        }
        if (preg_match('/(giờ|lúc|mấy giờ|thời gian)/i', $message)) {
            $context['intent'] = 'schedule';
        }

        // Phát hiện admin queries
        if ($isAdmin) {
            $this->detectAdminQueries($message, $context);
        }

        // Phát hiện staff queries
        if ($isStaff) {
            $this->detectStaffQueries($message, $context);
        }

        // Phát hiện bus owner queries
        if ($isBusOwner) {
            $this->detectBusOwnerQueries($message, $context, $userId);
        }

        return $context;
    }

    /**
     * Phát hiện admin queries
     */
    private function detectAdminQueries($message, &$context)
    {
        $messageLower = mb_strtolower($message, 'UTF-8');

        // Thống kê tổng quan
        if (preg_match('/(thống kê|statistics|dashboard|tổng quan|báo cáo)/i', $message)) {
            $context['intent'] = 'admin_statistics';
            $context['admin_data'] = $this->getAdminStatistics();
        }

        // Quản lý user
        if (preg_match('/(user|người dùng|khách hàng|danh sách user|list user)/i', $message)) {
            $context['intent'] = 'admin_users';
            $context['admin_data'] = $this->getUserData();
        }

        // Quản lý booking
        if (preg_match('/(booking|đặt vé|vé|ticket|dat ve)/i', $message)) {
            $context['intent'] = 'admin_bookings';
            $context['admin_data'] = $this->getBookingData();
        }

        // Quản lý chuyến xe
        if (preg_match('/(chuyến xe|trip|route|tuyến)/i', $message)) {
            $context['intent'] = 'admin_trips';
            $context['admin_data'] = $this->getTripData();
        }

        // Quản lý nhà xe
        if (preg_match('/(nhà xe|bus company|company|nha xe)/i', $message)) {
            $context['intent'] = 'admin_companies';
            $context['admin_data'] = $this->getCompanyData();
        }

        // Doanh thu
        if (preg_match('/(doanh thu|revenue|income|thu nhập)/i', $message)) {
            $context['intent'] = 'admin_revenue';
            $context['admin_data'] = $this->getRevenueData();
        }
    }

    /**
     * Phát hiện staff queries
     */
    private function detectStaffQueries($message, &$context)
    {
        $messageLower = mb_strtolower($message, 'UTF-8');

        // Booking management
        if (preg_match('/(booking|đặt vé|vé|ticket|dat ve)/i', $message)) {
            $context['intent'] = 'staff_bookings';
            $context['admin_data'] = $this->getBookingData();
        }

        // Customer service
        if (preg_match('/(khách hàng|customer|service|dịch vụ)/i', $message)) {
            $context['intent'] = 'staff_customers';
            $context['admin_data'] = $this->getCustomerData();
        }
    }

    /**
     * Phát hiện bus owner queries
     */
    private function detectBusOwnerQueries($message, &$context, $userId)
    {
        $messageLower = mb_strtolower($message, 'UTF-8');

        // My bookings
        if (preg_match('/(đặt vé của tôi|my booking|vé của tôi)/i', $message)) {
            $context['intent'] = 'bus_owner_bookings';
            $context['admin_data'] = $this->getBusOwnerBookings($userId);
        }

        // My revenue
        if (preg_match('/(doanh thu của tôi|my revenue|thu nhập)/i', $message)) {
            $context['intent'] = 'bus_owner_revenue';
            $context['admin_data'] = $this->getBusOwnerRevenue($userId);
        }

        // My trips
        if (preg_match('/(chuyến xe của tôi|my trips|tuyến của tôi)/i', $message)) {
            $context['intent'] = 'bus_owner_trips';
            $context['admin_data'] = $this->getBusOwnerTrips($userId);
        }
    }

    /**
     * Tìm chuyến xe giữa 2 địa điểm
     */
    private function searchRoutes($from, $to, $limit = 5)
    {
        try {
            $routes = DB::table('chuyen_xe as cx')
                ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
                ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
                ->where(function ($query) use ($from, $to) {
                    $query->where('di.tinh_thanh', 'LIKE', "%$from%")
                        ->where('den.tinh_thanh', 'LIKE', "%$to%");
                })
                ->orWhere(function ($query) use ($from, $to) {
                    $query->where('di.tinh_thanh', 'LIKE', "%$to%")
                        ->where('den.tinh_thanh', 'LIKE', "%$from%");
                })
                ->select(
                    'cx.id', // Thêm ID
                    'di.tinh_thanh as diem_di',
                    'di.ten_tram as tram_di',
                    'den.tinh_thanh as diem_den',
                    'den.ten_tram as tram_den',
                    'cx.ngay_di',
                    'cx.gio_di',
                    'cx.gio_den',
                    'cx.gia_ve',
                    'cx.loai_xe',
                    'cx.so_cho',
                    'cx.so_ve as con_trong'
                )
                ->orderBy('cx.gio_di')
                ->limit($limit)
                ->get();

            return $routes->toArray();
        } catch (\Exception $e) {
            Log::error('Search routes error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy thông tin chuyến xe liên quan đến 1 địa điểm
     */
    private function getRoutesByLocation($location, $limit = 5)
    {
        try {
            $routes = DB::table('chuyen_xe as cx')
                ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
                ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
                ->where(function ($query) use ($location) {
                    $query->where('di.tinh_thanh', 'LIKE', "%$location%")
                        ->orWhere('den.tinh_thanh', 'LIKE', "%$location%");
                })
                ->select(
                    'cx.id', // Thêm ID
                    'di.tinh_thanh as diem_di',
                    'den.tinh_thanh as diem_den',
                    'cx.gio_di',
                    'cx.gia_ve',
                    'cx.loai_xe',
                    'cx.so_cho'
                )
                ->orderBy('cx.gio_di')
                ->limit($limit)
                ->get();

            return $routes->toArray();
        } catch (\Exception $e) {
            Log::error('Get routes error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Tìm chuyến xe theo khoảng giá
     */
    private function searchRoutesByPrice($minPrice, $maxPrice, $limit = 10)
    {
        try {
            $routes = DB::table('chuyen_xe as cx')
                ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
                ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
                ->whereBetween('cx.gia_ve', [$minPrice, $maxPrice])
                ->select(
                    'cx.id', // Thêm ID
                    'di.tinh_thanh as diem_di',
                    'di.ten_tram as tram_di',
                    'den.tinh_thanh as diem_den',
                    'den.ten_tram as tram_den',
                    'cx.ngay_di',
                    'cx.gio_di',
                    'cx.gio_den',
                    'cx.gia_ve',
                    'cx.loai_xe',
                    'cx.so_cho',
                    'cx.so_ve as con_trong'
                )
                ->orderBy('cx.gia_ve')
                ->limit($limit)
                ->get();

            return $routes->toArray();
        } catch (\Exception $e) {
            Log::error('Search routes by price error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Tìm chuyến xe gần nhất khi không có chuyến trực tiếp
     * VD: Không có An Giang -> Hà Nội, thì tìm An Giang -> các tỉnh gần Hà Nội
     */
    private function searchNearbyRoutes($from, $to, $limit = 10)
    {
        try {
            // Danh sách các tỉnh lân cận (theo vùng miền)
            $nearbyProvinces = [
                'Hà Nội' => ['Hưng Yên', 'Bắc Ninh', 'Hải Phòng', 'Hải Dương', 'Vĩnh Phúc', 'Hà Nam', 'Nam Định', 'Thái Bình'],
                'Hồ Chí Minh' => ['Đồng Nai', 'Bình Dương', 'Long An', 'Tiền Giang', 'Bà Rịa - Vũng Tàu', 'Tây Ninh', 'Bình Phước'],
                'Đà Nẵng' => ['Quảng Nam', 'Thừa Thiên Huế', 'Quảng Ngãi', 'Quảng Trị'],
                'Cần Thơ' => ['Vĩnh Long', 'Hậu Giang', 'Sóc Trăng', 'An Giang', 'Đồng Tháp'],
                'Hải Phòng' => ['Hà Nội', 'Hải Dương', 'Quảng Ninh', 'Thái Bình'],
            ];

            // Tìm các tỉnh lân cận điểm đến
            $targetProvinces = [$to];
            if (isset($nearbyProvinces[$to])) {
                $targetProvinces = array_merge($targetProvinces, $nearbyProvinces[$to]);
            }

            // Query tìm chuyến từ $from đến các tỉnh lân cận $to
            $query = DB::table('chuyen_xe as cx')
                ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
                ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
                ->where('di.tinh_thanh', 'LIKE', "%$from%");

            // Thêm điều kiện OR cho các tỉnh lân cận
            $query->where(function ($q) use ($targetProvinces) {
                foreach ($targetProvinces as $province) {
                    $q->orWhere('den.tinh_thanh', 'LIKE', "%$province%");
                }
            });

            $routes = $query->select(
                'cx.id', // Thêm ID
                'di.tinh_thanh as diem_di',
                'di.ten_tram as tram_di',
                'den.tinh_thanh as diem_den',
                'den.ten_tram as tram_den',
                'cx.ngay_di',
                'cx.gio_di',
                'cx.gio_den',
                'cx.gia_ve',
                'cx.loai_xe',
                'cx.so_cho',
                'cx.so_ve as con_trong'
            )
                ->orderBy('cx.gio_di')
                ->limit($limit)
                ->get();

            // Thêm metadata về tỉnh đích ban đầu
            $result = [
                'original_destination' => $to,
                'routes' => $routes->toArray()
            ];

            return $result;
        } catch (\Exception $e) {
            Log::error('Search nearby routes error: ' . $e->getMessage());
            return ['original_destination' => $to, 'routes' => []];
        }
    }

    /**
     * Tra cứu lịch sử đặt vé
     */
    private function getBookingHistory($type, $value, $limit = 10)
    {
        try {
            $query = DB::table('dat_ve as dv')
                ->join('chuyen_xe as cx', 'dv.chuyen_xe_id', '=', 'cx.id')
                ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
                ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
                ->select(
                    'cx.id', // Thêm ID chuyến xe
                    'dv.ma_ve',
                    'dv.ten_khach_hang',
                    'dv.sdt_khach_hang',
                    'dv.email_khach_hang',
                    'dv.so_ghe',
                    'dv.ngay_dat',
                    'dv.trang_thai',
                    'di.tinh_thanh as diem_di',
                    'den.tinh_thanh as diem_den',
                    'cx.ngay_di',
                    'cx.gio_di',
                    'cx.gia_ve',
                    'cx.loai_xe'
                );

            if ($type === 'phone') {
                $query->where('dv.sdt_khach_hang', $value);
            } elseif ($type === 'email') {
                $query->where('dv.email_khach_hang', $value);
            }

            $bookings = $query
                ->orderBy('dv.ngay_dat', 'desc')
                ->limit($limit)
                ->get();

            return $bookings->toArray();
        } catch (\Exception $e) {
            Log::error('Get booking history error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy thống kê admin
     */
    private function getAdminStatistics()
    {
        try {
            $stats = [
                'total_users' => DB::table('users')->count(),
                'total_bookings' => DB::table('dat_ve')->count(),
                'total_trips' => DB::table('chuyen_xe')->count(),
                'total_companies' => DB::table('nha_xe')->count(),
                'revenue_today' => DB::table('dat_ve as dv')
                    ->join('chuyen_xe as cx', 'dv.chuyen_xe_id', '=', 'cx.id')
                    ->whereDate('dv.ngay_dat', today())
                    ->where('dv.trang_thai', 'Đã thanh toán')
                    ->sum('cx.gia_ve'),
                'revenue_month' => DB::table('dat_ve as dv')
                    ->join('chuyen_xe as cx', 'dv.chuyen_xe_id', '=', 'cx.id')
                    ->whereMonth('dv.ngay_dat', now()->month)
                    ->whereYear('dv.ngay_dat', now()->year)
                    ->where('dv.trang_thai', 'Đã thanh toán')
                    ->sum('cx.gia_ve'),
                'pending_bookings' => DB::table('dat_ve')
                    ->where('trang_thai', 'Đã đặt')
                    ->count(),
                'confirmed_bookings' => DB::table('dat_ve')
                    ->where('trang_thai', 'Đã thanh toán')
                    ->count()
            ];
            return $stats;
        } catch (\Exception $e) {
            Log::error('Get admin statistics error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy dữ liệu user
     */
    private function getUserData()
    {
        try {
            $users = DB::table('users')
                ->select('id', 'name', 'email', 'phone', 'role', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();
            return $users->toArray();
        } catch (\Exception $e) {
            Log::error('Get user data error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy dữ liệu booking
     */
    private function getBookingData()
    {
        try {
            $bookings = DB::table('dat_ve as dv')
                ->join('chuyen_xe as cx', 'dv.chuyen_xe_id', '=', 'cx.id')
                ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
                ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
                ->select(
                    'dv.ma_ve',
                    'dv.ten_khach_hang',
                    'dv.sdt_khach_hang',
                    'dv.email_khach_hang',
                    'dv.trang_thai',
                    'dv.ngay_dat',
                    'di.tinh_thanh as diem_di',
                    'den.tinh_thanh as diem_den',
                    'cx.ngay_di',
                    'cx.gio_di',
                    'dv.gia_ve'
                )
                ->orderBy('dv.ngay_dat', 'desc')
                ->limit(20)
                ->get();
            return $bookings->toArray();
        } catch (\Exception $e) {
            Log::error('Get booking data error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy dữ liệu chuyến xe
     */
    private function getTripData()
    {
        try {
            $trips = DB::table('chuyen_xe as cx')
                ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
                ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
                ->join('nha_xe as nx', 'cx.ma_nha_xe', '=', 'nx.ma_nha_xe')
                ->select(
                    'cx.id',
                    'cx.ten_xe',
                    'nx.ten_nha_xe',
                    'di.tinh_thanh as diem_di',
                    'den.tinh_thanh as diem_den',
                    'cx.ngay_di',
                    'cx.gio_di',
                    'cx.gia_ve',
                    'cx.loai_xe',
                    'cx.so_cho',
                    'cx.so_ve'
                )
                ->orderBy('cx.ngay_di', 'desc')
                ->limit(20)
                ->get();
            return $trips->toArray();
        } catch (\Exception $e) {
            Log::error('Get trip data error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy dữ liệu nhà xe
     */
    private function getCompanyData()
    {
        try {
            $companies = DB::table('nha_xe')
                ->select('ma_nha_xe', 'ten_nha_xe', 'dia_chi', 'sdt', 'email', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();
            return $companies->toArray();
        } catch (\Exception $e) {
            Log::error('Get company data error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy dữ liệu doanh thu
     */
    private function getRevenueData()
    {
        try {
            $revenue = [
                'today' => DB::table('dat_ve as dv')
                    ->join('chuyen_xe as cx', 'dv.chuyen_xe_id', '=', 'cx.id')
                    ->whereDate('dv.ngay_dat', today())
                    ->where('dv.trang_thai', 'Đã thanh toán')
                    ->sum('cx.gia_ve'),
                'this_month' => DB::table('dat_ve as dv')
                    ->join('chuyen_xe as cx', 'dv.chuyen_xe_id', '=', 'cx.id')
                    ->whereMonth('dv.ngay_dat', now()->month)
                    ->whereYear('dv.ngay_dat', now()->year)
                    ->where('dv.trang_thai', 'Đã thanh toán')
                    ->sum('cx.gia_ve'),
                'last_month' => DB::table('dat_ve as dv')
                    ->join('chuyen_xe as cx', 'dv.chuyen_xe_id', '=', 'cx.id')
                    ->whereMonth('dv.ngay_dat', now()->subMonth()->month)
                    ->whereYear('dv.ngay_dat', now()->subMonth()->year)
                    ->where('dv.trang_thai', 'Đã thanh toán')
                    ->sum('cx.gia_ve'),
                'monthly_breakdown' => DB::table('dat_ve as dv')
                    ->join('chuyen_xe as cx', 'dv.chuyen_xe_id', '=', 'cx.id')
                    ->select(
                        DB::raw('MONTH(dv.ngay_dat) as month'),
                        DB::raw('SUM(cx.gia_ve) as revenue')
                    )
                    ->where('dv.trang_thai', 'Đã thanh toán')
                    ->whereYear('dv.ngay_dat', now()->year)
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get()
            ];
            return $revenue;
        } catch (\Exception $e) {
            Log::error('Get revenue data error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy dữ liệu khách hàng cho staff
     */
    private function getCustomerData()
    {
        try {
            $customers = DB::table('dat_ve as dv')
                ->select(
                    'dv.ten_khach_hang',
                    'dv.sdt_khach_hang',
                    'dv.email_khach_hang',
                    DB::raw('COUNT(*) as total_bookings'),
                    DB::raw('SUM(dv.gia_ve) as total_spent'),
                    DB::raw('MAX(dv.ngay_dat) as last_booking')
                )
                ->groupBy('dv.ten_khach_hang', 'dv.sdt_khach_hang', 'dv.email_khach_hang')
                ->orderBy('total_bookings', 'desc')
                ->limit(20)
                ->get();
            return $customers->toArray();
        } catch (\Exception $e) {
            Log::error('Get customer data error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy booking của bus owner
     */
    private function getBusOwnerBookings($userId)
    {
        try {
            $bookings = DB::table('dat_ve as dv')
                ->join('chuyen_xe as cx', 'dv.chuyen_xe_id', '=', 'cx.id')
                ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
                ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
                ->where('cx.ma_nha_xe', function ($query) use ($userId) {
                    $query->select('ma_nha_xe')
                        ->from('users')
                        ->where('id', $userId);
                })
                ->select(
                    'dv.ma_ve',
                    'dv.ten_khach_hang',
                    'dv.sdt_khach_hang',
                    'dv.trang_thai',
                    'dv.ngay_dat',
                    'di.tinh_thanh as diem_di',
                    'den.tinh_thanh as diem_den',
                    'cx.ngay_di',
                    'cx.gio_di',
                    'dv.gia_ve'
                )
                ->orderBy('dv.ngay_dat', 'desc')
                ->limit(20)
                ->get();
            return $bookings->toArray();
        } catch (\Exception $e) {
            Log::error('Get bus owner bookings error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy doanh thu của bus owner
     */
    private function getBusOwnerRevenue($userId)
    {
        try {
            $revenue = DB::table('dat_ve as dv')
                ->join('chuyen_xe as cx', 'dv.chuyen_xe_id', '=', 'cx.id')
                ->where('cx.ma_nha_xe', function ($query) use ($userId) {
                    $query->select('ma_nha_xe')
                        ->from('users')
                        ->where('id', $userId);
                })
                ->where('dv.trang_thai', 'Đã thanh toán')
                ->select(
                    DB::raw('SUM(cx.gia_ve) as total_revenue'),
                    DB::raw('COUNT(*) as total_bookings'),
                    DB::raw('AVG(cx.gia_ve) as avg_ticket_price')
                )
                ->first();
            return $revenue;
        } catch (\Exception $e) {
            Log::error('Get bus owner revenue error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy chuyến xe của bus owner
     */
    private function getBusOwnerTrips($userId)
    {
        try {
            $trips = DB::table('chuyen_xe as cx')
                ->join('tram_xe as di', 'cx.ma_tram_di', '=', 'di.ma_tram_xe')
                ->join('tram_xe as den', 'cx.ma_tram_den', '=', 'den.ma_tram_xe')
                ->where('cx.ma_nha_xe', function ($query) use ($userId) {
                    $query->select('ma_nha_xe')
                        ->from('users')
                        ->where('id', $userId);
                })
                ->select(
                    'cx.id',
                    'cx.ten_xe',
                    'di.tinh_thanh as diem_di',
                    'den.tinh_thanh as diem_den',
                    'cx.ngay_di',
                    'cx.gio_di',
                    'cx.gia_ve',
                    'cx.loai_xe',
                    'cx.so_cho',
                    'cx.so_ve'
                )
                ->orderBy('cx.ngay_di', 'desc')
                ->limit(20)
                ->get();
            return $trips->toArray();
        } catch (\Exception $e) {
            Log::error('Get bus owner trips error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Xây dựng prompt nâng cao với context từ database
     */
    private function buildEnhancedPrompt($contextData, $isAdmin = false, $userRole = 'user')
    {
        if ($isAdmin) {
            $prompt = "🤖 BẠN LÀ MINH - AI ASSISTANT CHO ADMIN FUTA BUS LINES

📋 VAI TRÒ & NHIỆM VỤ:
- Tên: Minh (AI Assistant cho Admin)
- Công ty: FUTA Bus Lines (Phương Trang)
- Nhiệm vụ: Hỗ trợ Admin quản lý hệ thống, phân tích dữ liệu, báo cáo thống kê
- Quyền hạn: Truy cập toàn bộ dữ liệu hệ thống, thống kê, báo cáo

🎯 KHẢ NĂNG ADMIN:
✅ Thống kê tổng quan hệ thống (users, bookings, revenue, trips)
✅ Quản lý người dùng, đặt vé, chuyến xe, nhà xe
✅ Phân tích doanh thu, xu hướng, báo cáo
✅ Hỗ trợ quyết định quản lý dựa trên dữ liệu thực
✅ Giải đáp mọi thắc mắc về hệ thống và dữ liệu

";
        } else {
            $prompt = "🤖 BẠN LÀ MINH - TƯ VẤN VIÊN AI THÔNG MINH CỦA FUTA BUS LINES

📋 VAI TRÒ & NHIỆM VỤ:
- Tên: Minh (nam, 25 tuổi, thân thiện, nhiệt tình)
- Công ty: FUTA Bus Lines (Phương Trang - hãng xe khách #1 Việt Nam)
- Nhiệm vụ: Tư vấn đặt vé xe khách, giải đáp thắc mắc, hỗ trợ khách hàng

🎯 KHẢ NĂNG CỦA BẠN:
✅ Tư vấn tuyến xe, giá vé, lịch trình (có dữ liệu thật từ hệ thống)
✅ Hướng dẫn đặt vé online, thanh toán, nhận vé
✅ Giải đáp về loại xe, tiện ích, chính sách hủy/đổi vé
✅ Trò chuyện thân thiện, trả lời câu hỏi ngoài lề một cách tự nhiên
✅ Hiểu tiếng Việt có dấu, không dấu, viết tắt, lỗi chính tả

📊 THÔNG TIN FUTA (CẬP NHẬT 2025):
- Hotline: 1900 6067 (24/7)
- Website: futabus.vn
- App: FUTA Bus Lines (iOS/Android)
- Fanpage: facebook.com/FutaBusLines

🚌 LOẠI XE:
1. **Giường nằm Limousine VIP** (22-28 ghế)
   - Ghế massage, điều hòa riêng, wifi, giải trí
   - Giá: 250k-500k/vé
   
2. **Ghế ngồi VIP** (36-40 ghế)
   - Ghế da, ngả 135°, wifi, nước uống
   - Giá: 150k-300k/vé
   
3. **Xe khách thường** (45 ghế)
   - Ghế ngồi cơ bản, điều hòa
   - Giá: 100k-200k/vé

💳 THANH TOÁN:
- Chuyển khoản ngân hàng (Vietcombank, Techcombank, BIDV...)
- Ví điện tử (Momo, ZaloPay, VNPay)
- Thanh toán tại bến xe, quầy vé
- Thẻ tín dụng/ghi nợ (Visa, Mastercard)

🎁 ƯU ĐÃI HIỆN TẠI:
- Giảm 10% cho khách đặt vé lần đầu (mã: FUTANEW)
- Tích điểm: 1000đ = 1 điểm, đổi quà khi đủ 100 điểm
- Giảm 15% cho nhóm từ 10 người trở lên
- Miễn phí 1 vé cho trẻ em dưới 6 tuổi (đi cùng người lớn)
- Giảm 20% vé khứ hồi (book 2 chiều cùng lúc)

📍 TUYẾN XE HOT:
- TP.HCM → Đà Lạt: 230k-450k (6-7 giờ)
- TP.HCM → Nha Trang: 180k-350k (8-9 giờ)  
- TP.HCM → Vũng Tàu: 100k-150k (2-3 giờ)
- Hà Nội → Hải Phòng: 120k-180k (2 giờ)
- Hà Nội → Vinh: 250k-400k (5-6 giờ)
- Đà Nẵng → Huế: 80k-120k (2-3 giờ)

⚠️ CHÍNH SÁCH QUAN TRỌNG:
- Miễn phí hủy/đổi vé trước 24h khởi hành
- Phí 20% nếu hủy trong vòng 24h
- Không hoàn tiền nếu hủy sau giờ khởi hành
- Cho phép mang 20kg hành lý miễn phí

";
        }

        // Thêm thông tin từ database nếu có
        if (!empty($contextData['routes'])) {
            $prompt .= "\n🔍 DỮ LIỆU CHUYẾN XE TỪ HỆ THỐNG (REAL DATA):\n";
            $prompt .= "📊 Tổng cộng: " . count($contextData['routes']) . " chuyến xe\n";

            foreach ($contextData['routes'] as $index => $route) {
                $num = $index + 1;
                $prompt .= "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                $prompt .= "✅ CHUYẾN {$num}:\n";
                $prompt .= "  📍 Tuyến: {$route->diem_di} → {$route->diem_den}\n";

                if (isset($route->tram_di)) {
                    $prompt .= "  🚏 Trạm đi: {$route->tram_di}\n";
                }
                if (isset($route->tram_den)) {
                    $prompt .= "  🚏 Trạm đến: {$route->tram_den}\n";
                }
                if (isset($route->ngay_di)) {
                    $prompt .= "  📅 Ngày: {$route->ngay_di}\n";
                }

                $prompt .= "  🕐 Giờ khởi hành: {$route->gio_di}\n";

                if (isset($route->gio_den)) {
                    $prompt .= "  🕐 Giờ đến (dự kiến): {$route->gio_den}\n";
                }

                $prompt .= "  💰 Giá vé: " . number_format($route->gia_ve, 0, ',', '.') . "đ\n";
                $prompt .= "  🚌 Loại xe: {$route->loai_xe}\n";

                if (isset($route->so_cho)) {
                    $prompt .= "  💺 Tổng số ghế: {$route->so_cho} chỗ\n";
                }
                if (isset($route->con_trong)) {
                    $prompt .= "  ✨ Còn trống: {$route->con_trong} vé\n";
                }
            }

            $prompt .= "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            $prompt .= "✅ Đây là dữ liệu THẬT 100% từ hệ thống đặt vé FUTA.\n";
            $prompt .= "📌 QUAN TRỌNG: Khi trả lời, hãy:\n";
            $prompt .= "   - Liệt kê TỪNG CHUYẾN một cách rõ ràng, dễ đọc\n";
            $prompt .= "   - Dùng số thứ tự (Chuyến 1, Chuyến 2...)\n";
            $prompt .= "   - Hiển thị đầy đủ: Giờ đi, Giá vé, Loại xe, Số ghế trống\n";
            $prompt .= "   - Format giá tiền dễ đọc (VD: 200,000đ hoặc 200k)\n";
            $prompt .= "   - Gợi ý khách chọn chuyến phù hợp\n\n";

            if (!empty($contextData['price_range'])) {
                $min = number_format($contextData['price_range']['min'], 0, ',', '.');
                $max = number_format($contextData['price_range']['max'], 0, ',', '.');
                $prompt .= "💡 Khách đang tìm chuyến trong khoảng giá {$min}đ - {$max}đ\n\n";
            }

            $prompt .= "📌 Nếu khách hỏi về tuyến khác không có trong danh sách:\n";
            $prompt .= "   - Gọi hotline 1900 6067 để biết thêm chi tiết\n";
            $prompt .= "   - Hoặc truy cập futabus.vn để xem tất cả các tuyến\n\n";
        }

        // Thêm thông tin chuyến gần nhất nếu không có chuyến trực tiếp
        if (!empty($contextData['nearby_routes'])) {
            $nearbyData = $contextData['nearby_routes'];
            $originalDest = $nearbyData['original_destination'];
            $nearbyRoutes = $nearbyData['routes'];

            if (!empty($nearbyRoutes)) {
                $prompt .= "\n⚠️ LƯU Ý: KHÔNG CÓ CHUYẾN TRỰC TIẾP\n";
                $prompt .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                $prompt .= "Hiện tại FUTA chưa có chuyến trực tiếp đến {$originalDest}.\n";
                $prompt .= "Tuy nhiên, có các chuyến đến CÁC TỈNH GẦN {$originalDest}:\n\n";

                $prompt .= "📊 Tổng cộng: " . count($nearbyRoutes) . " chuyến gần nhất\n";

                foreach ($nearbyRoutes as $index => $route) {
                    $num = $index + 1;
                    $prompt .= "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                    $prompt .= "✅ CHUYẾN {$num} (GẦN {$originalDest}):\n";
                    $prompt .= "  📍 Tuyến: {$route->diem_di} → {$route->diem_den}\n";

                    if (isset($route->tram_di)) {
                        $prompt .= "  🚏 Trạm đi: {$route->tram_di}\n";
                    }
                    if (isset($route->tram_den)) {
                        $prompt .= "  🚏 Trạm đến: {$route->tram_den}\n";
                    }
                    if (isset($route->ngay_di)) {
                        $prompt .= "  📅 Ngày: {$route->ngay_di}\n";
                    }

                    $prompt .= "  🕐 Giờ khởi hành: {$route->gio_di}\n";

                    if (isset($route->gio_den)) {
                        $prompt .= "  🕐 Giờ đến (dự kiến): {$route->gio_den}\n";
                    }

                    $prompt .= "  💰 Giá vé: " . number_format($route->gia_ve, 0, ',', '.') . "đ\n";
                    $prompt .= "  🚌 Loại xe: {$route->loai_xe}\n";

                    if (isset($route->so_cho)) {
                        $prompt .= "  💺 Tổng số ghế: {$route->so_cho} chỗ\n";
                    }
                    if (isset($route->con_trong)) {
                        $prompt .= "  ✨ Còn trống: {$route->con_trong} vé\n";
                    }
                }

                $prompt .= "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                $prompt .= "💡 GỢI Ý CÁCH TRẢ LỜI:\n";
                $prompt .= "   - Thông báo: Hiện không có chuyến trực tiếp\n";
                $prompt .= "   - Giải thích: Các tỉnh này gần {$originalDest}, bạn có thể di chuyển thêm bằng xe khác\n";
                $prompt .= "   - Liệt kê các chuyến gần nhất (như trên)\n";
                $prompt .= "   - Gợi ý: Hoặc gọi hotline 1900 6067 để được tư vấn thêm về chuyến kết nối\n\n";
            }
        }

        // Thêm thông tin lịch sử đặt vé nếu có
        if (!empty($contextData['bookings'])) {
            $prompt .= "\n📋 LỊCH SỬ ĐẶT VÉ (REAL DATA):\n";
            $prompt .= "📊 Tổng cộng: " . count($contextData['bookings']) . " vé đã đặt\n";

            foreach ($contextData['bookings'] as $index => $booking) {
                $num = $index + 1;
                $prompt .= "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                $prompt .= "🎫 VÉ {$num}:\n";
                $prompt .= "  🔖 Mã vé: {$booking->ma_ve}\n";
                $prompt .= "  👤 Khách hàng: {$booking->ten_khach_hang}\n";
                $prompt .= "  📞 SĐT: {$booking->sdt_khach_hang}\n";

                if ($booking->email_khach_hang) {
                    $prompt .= "  📧 Email: {$booking->email_khach_hang}\n";
                }

                $prompt .= "  📍 Tuyến: {$booking->diem_di} → {$booking->diem_den}\n";
                $prompt .= "  📅 Ngày đi: {$booking->ngay_di}\n";
                $prompt .= "  🕐 Giờ đi: {$booking->gio_di}\n";
                $prompt .= "  💺 Số ghế: {$booking->so_ghe}\n";
                $prompt .= "  💰 Giá vé: " . number_format($booking->gia_ve, 0, ',', '.') . "đ\n";
                $prompt .= "  🚌 Loại xe: {$booking->loai_xe}\n";
                $prompt .= "  📅 Ngày đặt: {$booking->ngay_dat}\n";
                $prompt .= "  ⚡ Trạng thái: {$booking->trang_thai}\n";
            }

            $prompt .= "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            $prompt .= "✅ Đây là lịch sử đặt vé THẬT của khách hàng.\n";
            $prompt .= "📌 Hãy tóm tắt thông tin một cách rõ ràng, dễ hiểu!\n\n";
        }

        // Thêm dữ liệu admin nếu có
        if ($isAdmin && !empty($contextData['admin_data'])) {
            $this->addAdminDataToPrompt($prompt, $contextData);
        }

        $prompt .= "\n💬 CÁCH TRẢ LỜI:
1. **Thân thiện & Tự nhiên**: Dùng emoji phù hợp (😊🚌🎫💰👍✨🔥)
2. **Ngắn gọn**: 2-4 câu cho câu hỏi đơn giản, chi tiết hơn khi cần
3. **Chính xác**: Ưu tiên dữ liệu thật từ hệ thống
4. **Hữu ích**: Luôn gợi ý bước tiếp theo (đặt vé, gọi hotline...)
5. **Linh hoạt**: 
   - Nếu hỏi về chuyến xe → trả lời dựa vào dữ liệu thật
   - Nếu không có dữ liệu → gợi ý gọi hotline hoặc vào website
   - Nếu hỏi ngoài lề (chào hỏi, thời tiết...) → trả lời tự nhiên, rồi dẫn về dịch vụ FUTA
   - Nếu hỏi về công ty khác → lịch sự từ chối, giới thiệu FUTA

📌 VÍ DỤ TRẢ LỜI HAY:

❓ \"Có chuyến nào từ HCM đi Đà Lạt không?\"
✅ \"Dạ có nhiều chuyến luôn bạn ơi! 😊 Để mình liệt kê chi tiết nhé:

**CHUYẾN 1:**
🕐 07:30 sáng | 💰 250,000đ | 🚌 Giường nằm Limousine | ✨ Còn 15 chỗ

**CHUYẾN 2:**
🕐 09:00 sáng | 💰 230,000đ | 🚌 Giường nằm | ✨ Còn 20 chỗ

**CHUYẾN 3:**
🕐 14:30 chiều | 💰 200,000đ | 🚌 Ghế ngồi VIP | ✨ Còn 8 chỗ

Bạn thích chuyến nào để mình hướng dẫn đặt vé luôn nhé! 🎫\"

❓ \"Có chuyến từ An Giang đi Hà Nội không?\" (KHÔNG CÓ CHUYẾN TRỰC TIẾP)
✅ \"Dạ hiện tại FUTA chưa có chuyến trực tiếp từ An Giang đến Hà Nội bạn nhé 😊

Tuy nhiên, mình tìm được các chuyến đến CÁC TỈNH GẦN HÀ NỘI, bạn có thể tham khảo:

**CHUYẾN 1:** An Giang → Hưng Yên (cách Hà Nội 60km)
🕐 08:00 | 💰 450,000đ | 🚌 Giường nằm | ✨ Còn 12 chỗ

**CHUYẾN 2:** An Giang → Bắc Ninh (cách Hà Nội 30km)  
🕐 07:30 | 💰 400,000đ | 🚌 Giường nằm | ✨ Còn 10 chỗ

Từ Hưng Yên/Bắc Ninh, bạn có thể đi xe khác về Hà Nội rất gần nhé! 

Hoặc bạn gọi hotline 1900 6067 để được tư vấn về chuyến kết nối phù hợp hơn! 📞\"

❓ \"Có chuyến nào giá 500k không?\"
✅ \"Dạ có ạ! Đây là các chuyến xe cao cấp Limousine VIP trong khoảng giá 500k:

**CHUYẾN 1:** HCM → Đà Lạt
🕐 08:00 | 💰 480,000đ | 🚌 Limousine 28 ghế | ✨ Còn 5 chỗ

**CHUYẾN 2:** Hà Nội → Vinh  
🕐 10:30 | 💰 520,000đ | 🚌 Limousine VIP | ✨ Còn 12 chỗ

Bạn muốn đặt chuyến nào không? 😊\"

❓ \"Tra lịch sử vé của tôi - SĐT 0912345678\"
✅ \"Dạ để mình check lịch sử đặt vé cho bạn nhé! 🔍

**VÉ 1:** Mã VE001
📍 HCM → Đà Lạt | 📅 20/10/2025 lúc 08:00
💺 Ghế A12 | 💰 250,000đ | ⚡ Đã thanh toán ✅

**VÉ 2:** Mã VE002  
📍 Đà Lạt → HCM | 📅 25/10/2025 lúc 14:00
💺 Ghế B05 | 💰 250,000đ | ⚡ Đã đặt

Bạn cần hỗ trợ gì thêm về các vé này không? 😊\"

❓ \"Hôm nay trời đẹp quá!\"
✅ \"Đúng rồi! Trời đẹp thế này đi du lịch là nhất luôn 😎☀️ 
Bạn có muốn mình tư vấn chuyến xe đi chơi cuối tuần không? FUTA đang có ưu đãi giảm 10% đấy! 🎉\"

❓ \"Giá vé bao nhiêu?\"
✅ \"Dạ để mình tư vấn chính xác, bạn muốn đi tuyến nào nhé? 🤔
Ví dụ: HCM-Đà Lạt, HN-Hải Phòng... Hoặc inbox ngay để mình check giá cho bạn! 😊\"

❓ \"Xe Mai Linh có tốt không?\"
✅ \"Mình là tư vấn viên của FUTA thôi nên không tiện nhận xét hãng khác bạn nhé 😊
Nhưng mình có thể giới thiệu dịch vụ của FUTA - hãng xe 5 sao với hơn 20 năm kinh nghiệm! Bạn quan tâm tuyến nào để mình tư vấn? 🚌✨\"

🎯 MỤC TIÊU: Biến mỗi cuộc trò chuyện thành cơ hội bán vé, nhưng KHÔNG spam hay quá ép!

⚡ LƯU Ý QUAN TRỌNG KHI TRẢ LỜI:
1. **Liệt kê từng chuyến rõ ràng** - Dùng số thứ tự, xuống dòng dễ đọc
2. **Hiển thị đầy đủ thông tin** - Giờ, giá, loại xe, số ghế trống
3. **Format đẹp** - Dùng emoji, chia nhóm thông tin
4. **Gợi ý tiếp theo** - Luôn kết thúc bằng câu hỏi/gợi ý hành động
5. **Tự nhiên** - Không cứng nhắc, thân thiện như chat với bạn bè


";

        return $prompt;
    }

    /**
     * Thêm dữ liệu admin vào prompt
     */
    private function addAdminDataToPrompt(&$prompt, $contextData)
    {
        $intent = $contextData['intent'];
        $adminData = $contextData['admin_data'];

        switch ($intent) {
            case 'admin_statistics':
                $prompt .= "\n📊 THỐNG KÊ TỔNG QUAN HỆ THỐNG:\n";
                $prompt .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                $prompt .= "👥 Tổng người dùng: " . number_format($adminData['total_users']) . "\n";
                $prompt .= "🎫 Tổng đặt vé: " . number_format($adminData['total_bookings']) . "\n";
                $prompt .= "🚌 Tổng chuyến xe: " . number_format($adminData['total_trips']) . "\n";
                $prompt .= "🏢 Tổng nhà xe: " . number_format($adminData['total_companies']) . "\n";
                $prompt .= "💰 Doanh thu hôm nay: " . number_format($adminData['revenue_today']) . "đ\n";
                $prompt .= "💰 Doanh thu tháng này: " . number_format($adminData['revenue_month']) . "đ\n";
                $prompt .= "⏳ Đặt vé chờ xử lý: " . number_format($adminData['pending_bookings']) . "\n";
                $prompt .= "✅ Đặt vé đã xác nhận: " . number_format($adminData['confirmed_bookings']) . "\n";
                break;

            case 'admin_users':
                $prompt .= "\n👥 DANH SÁCH NGƯỜI DÙNG:\n";
                $prompt .= "📊 Tổng cộng: " . count($adminData) . " người dùng\n";
                foreach ($adminData as $index => $user) {
                    $num = $index + 1;
                    $prompt .= "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                    $prompt .= "👤 USER {$num}:\n";
                    $prompt .= "  🆔 ID: {$user->id}\n";
                    $prompt .= "  📝 Tên: {$user->name}\n";
                    $prompt .= "  📧 Email: {$user->email}\n";
                    $prompt .= "  📞 SĐT: {$user->phone}\n";
                    $prompt .= "  🔐 Vai trò: {$user->role}\n";
                    $prompt .= "  📅 Ngày tạo: {$user->created_at}\n";
                }
                break;

            case 'admin_bookings':
                $prompt .= "\n🎫 DANH SÁCH ĐẶT VÉ:\n";
                $prompt .= "📊 Tổng cộng: " . count($adminData) . " vé\n";
                foreach ($adminData as $index => $booking) {
                    $num = $index + 1;
                    $prompt .= "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                    $prompt .= "🎫 VÉ {$num}:\n";
                    $prompt .= "  🔖 Mã vé: {$booking->ma_ve}\n";
                    $prompt .= "  👤 Khách: {$booking->ten_khach_hang}\n";
                    $prompt .= "  📞 SĐT: {$booking->sdt_khach_hang}\n";
                    $prompt .= "  📍 Tuyến: {$booking->diem_di} → {$booking->diem_den}\n";
                    $prompt .= "  📅 Ngày đi: {$booking->ngay_di}\n";
                    $prompt .= "  🕐 Giờ đi: {$booking->gio_di}\n";
                    $prompt .= "  💰 Giá: " . number_format($booking->gia_ve) . "đ\n";
                    $prompt .= "  ⚡ Trạng thái: {$booking->trang_thai}\n";
                }
                break;

            case 'admin_trips':
                $prompt .= "\n🚌 DANH SÁCH CHUYẾN XE:\n";
                $prompt .= "📊 Tổng cộng: " . count($adminData) . " chuyến\n";
                foreach ($adminData as $index => $trip) {
                    $num = $index + 1;
                    $prompt .= "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                    $prompt .= "🚌 CHUYẾN {$num}:\n";
                    $prompt .= "  🆔 ID: {$trip->id}\n";
                    $prompt .= "  🚌 Tên xe: {$trip->ten_xe}\n";
                    $prompt .= "  🏢 Nhà xe: {$trip->ten_nha_xe}\n";
                    $prompt .= "  📍 Tuyến: {$trip->diem_di} → {$trip->diem_den}\n";
                    $prompt .= "  📅 Ngày: {$trip->ngay_di}\n";
                    $prompt .= "  🕐 Giờ: {$trip->gio_di}\n";
                    $prompt .= "  💰 Giá: " . number_format($trip->gia_ve) . "đ\n";
                    $prompt .= "  🚌 Loại: {$trip->loai_xe}\n";
                    $prompt .= "  💺 Ghế: {$trip->so_cho}\n";
                    $prompt .= "  ✨ Còn: {$trip->so_ve}\n";
                }
                break;

            case 'admin_companies':
                $prompt .= "\n🏢 DANH SÁCH NHÀ XE:\n";
                $prompt .= "📊 Tổng cộng: " . count($adminData) . " nhà xe\n";
                foreach ($adminData as $index => $company) {
                    $num = $index + 1;
                    $prompt .= "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                    $prompt .= "🏢 NHÀ XE {$num}:\n";
                    $prompt .= "  🆔 Mã: {$company->ma_nha_xe}\n";
                    $prompt .= "  📝 Tên: {$company->ten_nha_xe}\n";
                    $prompt .= "  📍 Địa chỉ: {$company->dia_chi}\n";
                    $prompt .= "  📞 SĐT: {$company->sdt}\n";
                    $prompt .= "  📧 Email: {$company->email}\n";
                    $prompt .= "  📅 Ngày tạo: {$company->created_at}\n";
                }
                break;

            case 'admin_revenue':
                $prompt .= "\n💰 BÁO CÁO DOANH THU:\n";
                $prompt .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                $prompt .= "💰 Hôm nay: " . number_format($adminData['today']) . "đ\n";
                $prompt .= "💰 Tháng này: " . number_format($adminData['this_month']) . "đ\n";
                $prompt .= "💰 Tháng trước: " . number_format($adminData['last_month']) . "đ\n";
                $prompt .= "\n📊 PHÂN TÍCH THEO THÁNG:\n";
                foreach ($adminData['monthly_breakdown'] as $month) {
                    $prompt .= "  📅 Tháng {$month->month}: " . number_format($month->revenue) . "đ\n";
                }
                break;
        }

        $prompt .= "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $prompt .= "✅ Đây là dữ liệu THẬT từ hệ thống quản trị.\n";
        $prompt .= "📌 Hãy phân tích và đưa ra insights hữu ích cho Admin!\n\n";
    }

    public function test()
    {
        return response()->json([
            'success' => true,
            'message' => 'Chat API working',
            'model' => 'gemini-2.5-flash',
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}