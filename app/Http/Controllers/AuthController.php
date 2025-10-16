<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login', [
            'message' => session('message'),
            'messageType' => session('messageType', 'success')
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register', [
            'message' => session('message'),
            'messageType' => session('messageType', 'success')
        ]);
    }


    public function authenticate(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required'
        ]);

        $identifier = $request->identifier;
        $password = $request->password;

        // Tìm user theo username, email hoặc phone
        $user = User::where('username', $identifier)
            ->orWhere('email', $identifier)
            ->orWhere('phone', $identifier)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'identifier' => 'Tài khoản không tồn tại!',
            ])->onlyInput('identifier');
        }

        // Kiểm tra mật khẩu (hỗ trợ cả plain text và hash)
        if ($password === $user->password || Hash::check($password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            // Chuyển hướng dựa trên role
            switch (strtolower($user->role)) {
                case 'admin':
                    return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
                case 'staff':
                    return redirect()->route('staff.dashboard')->with('success', 'Đăng nhập thành công!');
                case 'bus_owner':
                    return redirect()->route('bus-owner.dashboard')->with('success', 'Đăng nhập thành công!');
                case 'user':
                default:
                    return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
            }
        }

        return back()->withErrors([
            'password' => 'Mật khẩu không chính xác!',
        ])->onlyInput('identifier');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users',
            'fullname' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'phone' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        try {
            $user = User::create([
                'username' => $request->username,
                'fullname' => $request->fullname,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'user'
            ]);

            Auth::login($user);

            return redirect()->route('home')->with('success', 'Đăng ký thành công!');

        } catch (\Exception $e) {
            // Log the actual error for debugging
            Log::error('Registration error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi đăng ký: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Đã đăng xuất thành công!');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Không tìm thấy tài khoản với email này.']);
        }

        $otp = rand(100000, 999999);
        $user->update([
            'reset_token' => $otp,
            'reset_token_expires_at' => now()->addMinutes(5)
        ]);

        // Send OTP email (implement if needed)
        // Mail::to($user->email)->send(new ResetPasswordMail($user, $otp));

        return redirect()->route('password.verify-otp')->with('success', 'Mã OTP đã được gửi đến email của bạn.');
    }

    public function showVerifyOtp()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        $user = User::where('reset_token', $request->otp)
            ->where('reset_token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Mã OTP không hợp lệ hoặc đã hết hạn.']);
        }

        // OTP valid, redirect to reset password
        return redirect()->route('password.reset', ['token' => $request->otp]);
    }

    public function showResetPasswordForm(Request $request, $token)
    {
        $user = User::where('reset_token', $token)
            ->where('reset_token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.');
        }

        return view('auth.reset-password', compact('token', 'user'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Password::min(8)]
        ]);

        $user = User::where('email', $request->email)
            ->where('reset_token', $request->token)
            ->where('reset_token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Thông tin đặt lại mật khẩu không hợp lệ.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'reset_token' => null,
            'reset_token_expires_at' => null
        ]);

        return redirect()->route('login')->with('success', 'Mật khẩu đã được đặt lại thành công!');
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function showProfile()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fullname' => 'required|string|max:100',
            'phone' => 'required|string|max:15|unique:users,phone,' . $user->id,
            'email' => 'required|string|email|max:100|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:Nam,Nữ,Khác'
        ]);

        $user->update([
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
        ]);

        return redirect()->route('profile.edit')->with('success', 'Thông tin tài khoản đã được cập nhật thành công!');
    }

    public function editPassword()
    {
        $user = Auth::user();
        return view('profile.change-password', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed'
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng!']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('password.edit')->with('success', 'Mật khẩu đã được cập nhật thành công!');
    }
}