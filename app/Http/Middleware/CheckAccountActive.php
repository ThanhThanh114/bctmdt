<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAccountActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Kiểm tra tài khoản có bị khóa không
            if (isset($user->is_active) && !$user->is_active) {
                Auth::logout();
                
                return redirect()->route('login')
                    ->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin để biết thêm chi tiết.');
            }
        }
        
        return $next($request);
    }
}
