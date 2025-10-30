<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckStaffBusCompany
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Nếu không phải staff thì bỏ qua (để các middleware khác xử lý)
        if (!$user || $user->role !== 'staff') {
            return $next($request);
        }

        // Staff phải có mã nhà xe
        if (!$user->ma_nha_xe) {
            // Redirect to login with error instead of staff dashboard to avoid infinite loop
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Tài khoản nhân viên chưa được gán cho nhà xe nào. Vui lòng liên hệ quản trị viên.');
        }

        // Kiểm tra route có tham số nha_xe_id, chuyen_xe_id, booking_id, etc
        // Để đảm bảo staff chỉ truy cập dữ liệu của nhà xe mình

        // Lưu ma_nha_xe vào request để các controller có thể sử dụng
        $request->attributes->set('staff_ma_nha_xe', $user->ma_nha_xe);

        return $next($request);
    }
}
