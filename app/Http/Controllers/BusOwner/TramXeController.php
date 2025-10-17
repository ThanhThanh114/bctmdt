<?php

namespace App\Http\Controllers\BusOwner;

use App\Http\Controllers\Controller;
use App\Models\TramXe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TramXeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        // Build query with search - CHỈ LẤY TRẠM CỦA NHÀ XE NÀY
        $query = TramXe::with('nhaXe')->where('ma_nha_xe', $busCompany->ma_nha_xe);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ten_tram', 'LIKE', "%{$search}%")
                    ->orWhere('dia_chi', 'LIKE', "%{$search}%")
                    ->orWhere('tinh_thanh', 'LIKE', "%{$search}%")
                    ->orWhere('ma_tram_xe', 'LIKE', "%{$search}%");
            });
        }

        // Get stations of this bus company only
        $tramXe = $query->orderBy('ma_tram_xe', 'asc')->paginate(20);

        return view('AdminLTE.bus_owner.tram_xe.index', compact('tramXe', 'busCompany'));
    }

    public function dashboard()
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        // Get dashboard statistics
        $totalStations = TramXe::count();
        $stationsByCompany = TramXe::where('ma_nha_xe', $busCompany->ma_nha_xe)->count();
        $stationsWithoutCompany = TramXe::whereNull('ma_nha_xe')->count();

        // Get stations by province
        $stationsByProvince = TramXe::select('tinh_thanh', DB::raw('COUNT(*) as count'))
            ->groupBy('tinh_thanh')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Get recent stations (last 30 days) - since there's no timestamp, we'll get latest by ID
        $recentStations = TramXe::orderBy('ma_tram_xe', 'desc')
            ->limit(5)
            ->get();

        // Get most used stations (by trip count)
        $mostUsedStations = TramXe::select('tram_xe.*', DB::raw('COUNT(chuyen_xe.id) as trip_count'))
            ->leftJoin('chuyen_xe', function ($join) {
                $join->on('tram_xe.ma_tram_xe', '=', 'chuyen_xe.ma_tram_di')
                    ->orOn('tram_xe.ma_tram_xe', '=', 'chuyen_xe.ma_tram_den');
            })
            ->groupBy('tram_xe.ma_tram_xe')
            ->orderBy('trip_count', 'desc')
            ->limit(10)
            ->get();

        // Get station usage statistics
        $totalTrips = DB::table('chuyen_xe')->count();
        $stationsWithTrips = TramXe::whereHas('chuyenXeDi')->orWhereHas('chuyenXeDen')->count();

        return view('AdminLTE.bus_owner.tram_xe.dashboard', compact(
            'busCompany',
            'totalStations',
            'stationsByCompany',
            'stationsWithoutCompany',
            'stationsByProvince',
            'recentStations',
            'mostUsedStations',
            'totalTrips',
            'stationsWithTrips'
        ));
    }

    public function show($id)
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $tram = TramXe::with(['nhaXe', 'chuyenXeDi', 'chuyenXeDen'])->findOrFail($id);

        // Thống kê chuyến xe
        $tongChuyenDi = $tram->chuyenXeDi()->count();
        $tongChuyenDen = $tram->chuyenXeDen()->count();
        $tongChuyen = $tongChuyenDi + $tongChuyenDen;

        return view('AdminLTE.bus_owner.tram_xe.show', compact('tram', 'busCompany', 'tongChuyenDi', 'tongChuyenDen', 'tongChuyen'));
    }

    public function create()
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        return view('AdminLTE.bus_owner.tram_xe.create', compact('busCompany'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $validated = $request->validate([
            'ten_tram' => 'required|string|max:255',
            'dia_chi' => 'required|string|max:500',
            'tinh_thanh' => 'required|string|max:100',
        ], [
            'ten_tram.required' => 'Vui lòng nhập tên trạm xe',
            'dia_chi.required' => 'Vui lòng nhập địa chỉ trạm xe',
            'tinh_thanh.required' => 'Vui lòng chọn tỉnh/thành phố',
        ]);

        try {
            // Thêm ma_nha_xe vào dữ liệu
            $validated['ma_nha_xe'] = $busCompany->ma_nha_xe;

            TramXe::create($validated);

            return redirect()->route('bus-owner.tram-xe.index')
                ->with('success', 'Thêm trạm xe mới thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $tram = TramXe::findOrFail($id);

        return view('AdminLTE.bus_owner.tram_xe.edit', compact('tram', 'busCompany'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $tram = TramXe::findOrFail($id);

        $validated = $request->validate([
            'ten_tram' => 'required|string|max:255',
            'dia_chi' => 'required|string|max:500',
            'tinh_thanh' => 'required|string|max:100',
        ], [
            'ten_tram.required' => 'Vui lòng nhập tên trạm xe',
            'dia_chi.required' => 'Vui lòng nhập địa chỉ trạm xe',
            'tinh_thanh.required' => 'Vui lòng chọn tỉnh/thành phố',
        ]);

        try {
            $tram->update($validated);

            return redirect()->route('bus-owner.tram-xe.index')
                ->with('success', 'Cập nhật trạm xe thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chưa được gán cho nhà xe nào.'
            ], 403);
        }

        try {
            $tram = TramXe::findOrFail($id);

            // Kiểm tra xem trạm có đang được sử dụng trong chuyến xe không
            $hasTrips = DB::table('chuyen_xe')
                ->where('ma_tram_di', $id)
                ->orWhere('ma_tram_den', $id)
                ->exists();

            if ($hasTrips) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa trạm xe này vì đang được sử dụng trong chuyến xe!'
                ], 400);
            }

            $tram->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa trạm xe thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
