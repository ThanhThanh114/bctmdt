<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BankPaymentService
{
    private $apiUrl = 'https://api.4gsieure.net/api/historyacb/history/65954375ec19aea350a68f7c80b9ac2a';

    /**
     * Lấy lịch sử giao dịch từ API ngân hàng
     */
    public function getTransactionHistory()
    {
        try {
            $response = Http::timeout(10)->get($this->apiUrl);

            if ($response->successful()) {
                $data = $response->json();

                // Kiểm tra xem có key codeStatus không
                if (!isset($data['codeStatus'])) {
                    // API trả về lỗi không theo format chuẩn
                    $errorMsg = isset($data['message']) ? $data['message'] : 'Lỗi không xác định từ API';
                    Log::warning('Bank API returned error: ' . json_encode($data));
                    return [
                        'success' => false,
                        'message' => 'API ngân hàng: ' . $errorMsg
                    ];
                }

                if ($data['codeStatus'] == 200 && $data['messageStatus'] == 'success') {
                    return [
                        'success' => true,
                        'data' => $data['data'],
                        'time' => $data['time']
                    ];
                }

                // API trả về nhưng không success
                return [
                    'success' => false,
                    'message' => isset($data['messageStatus']) ? $data['messageStatus'] : 'Không thể lấy dữ liệu từ ngân hàng'
                ];
            }

            return [
                'success' => false,
                'message' => 'Không thể kết nối đến API ngân hàng'
            ];

        } catch (\Exception $e) {
            Log::error('Bank API Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Lỗi kết nối API: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Kiểm tra giao dịch với mã booking
     */
    public function verifyTransaction($bookingCode, $expectedAmount)
    {
        $result = $this->getTransactionHistory();

        if (!$result['success']) {
            return [
                'verified' => false,
                'message' => $result['message']
            ];
        }

        // Lọc giao dịch IN (tiền vào) trong 24h gần nhất
        $now = now()->timestamp * 1000; // Convert to milliseconds
        $oneDayAgo = $now - (24 * 60 * 60 * 1000);

        foreach ($result['data'] as $transaction) {
            // Chỉ kiểm tra giao dịch tiền VÀO
            if ($transaction['type'] != 'IN') {
                continue;
            }

            // Kiểm tra thời gian (trong 24h)
            if ($transaction['activeDatetime'] < $oneDayAgo) {
                continue;
            }

            // Kiểm tra số tiền
            if ($transaction['amount'] != $expectedAmount) {
                continue;
            }

            // Kiểm tra mã booking trong nội dung chuyển khoản
            $description = strtoupper($transaction['description']);
            $bookingCodeUpper = strtoupper($bookingCode);

            if (strpos($description, $bookingCodeUpper) !== false) {
                return [
                    'verified' => true,
                    'transaction' => $transaction,
                    'message' => 'Xác nhận thanh toán thành công!'
                ];
            }
        }

        return [
            'verified' => false,
            'message' => 'Chưa tìm thấy giao dịch phù hợp. Vui lòng kiểm tra lại nội dung chuyển khoản có chứa mã: ' . $bookingCode
        ];
    }
}
