<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthenticationService;
use App\Services\RegistrationService;
use App\Services\PasswordResetService;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private AuthenticationService $authService;
    private RegistrationService $registrationService;
    private PasswordResetService $passwordResetService;
    private ProfileService $profileService;

    public function __construct(
        AuthenticationService $authService,
        RegistrationService $registrationService,
        PasswordResetService $passwordResetService,
        ProfileService $profileService
    ) {
        $this->authService = $authService;
        $this->registrationService = $registrationService;
        $this->passwordResetService = $passwordResetService;
        $this->profileService = $profileService;
    }
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


    /**
     * Authenticate user with enhanced security and rate limiting
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string'
        ], [
            'identifier.required' => 'Vui lòng nhập tên đăng nhập, email hoặc số điện thoại',
            'password.required' => 'Vui lòng nhập mật khẩu'
        ]);

        // Use authentication service
        $result = $this->authService->authenticate($validated['identifier'], $validated['password']);

        if (!$result['success']) {
            return back()->withErrors([
                'authentication' => $result['message']
            ])->withInput($request->only('identifier'));
        }

        // Redirect based on role
        $redirectRoute = $result['redirect_route'];
        $user = $result['user'];

        return redirect()->route($redirectRoute)->with('success', 'Đăng nhập thành công! Chào ' . $user->fullname);
    }

    /**
     * Register new user with enhanced validation and security
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Use registration service
        $result = $this->registrationService->register($request->all());

        if (!$result['success']) {
            return back()->withInput($request->except('password', 'password_confirmation'))
                ->with('error', $result['message']);
        }

        return redirect()->route('home')->with('success', $result['message']);
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

    /**
     * Send password reset OTP
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email',
            'email.email' => 'Địa chỉ email không đúng định dạng'
        ]);

        // Use password reset service
        $result = $this->passwordResetService->initiateReset($request->email);

        if (!$result['success']) {
            return back()->withErrors(['email' => $result['message']]);
        }

        return redirect()->route('password.verify-otp')->with('success', $result['message']);
    }


    public function showVerifyOtp()
    {
        return view('auth.verify-otp');
    }

    /**
     * Verify OTP for password reset
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ], [
            'otp.required' => 'Vui lòng nhập mã OTP',
            'otp.size' => 'Mã OTP phải có 6 chữ số'
        ]);

        $email = session('reset_email');

        if (!$email) {
            return back()->withErrors(['otp' => 'Phiên làm việc đã hết hạn. Vui lòng yêu cầu OTP mới.']);
        }

        // Use password reset service
        $result = $this->passwordResetService->verifyOTP($email, $request->otp);

        if (!$result['success']) {
            return back()->withErrors(['otp' => $result['message']]);
        }

        return redirect()->route('password.reset', ['token' => $result['reset_token']]);
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

    /**
     * Reset password with token
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)]
        ], [
            'token.required' => 'Token không hợp lệ',
            'email.required' => 'Vui lòng nhập địa chỉ email',
            'email.email' => 'Địa chỉ email không đúng định dạng',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự'
        ]);

        // Use password reset service
        $result = $this->passwordResetService->resetPassword(
            $request->email,
            $request->token,
            $request->password
        );

        if (!$result['success']) {
            return back()->withErrors(['email' => $result['message']]);
        }

        return redirect()->route('login')->with('success', $result['message']);
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

    /**
     * Update user profile with enhanced validation and caching
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $result = $this->profileService->updateProfile($request->all());

        if (!$result['success']) {
            return back()->withErrors($result['errors'] ?? ['profile' => $result['message']])
                ->withInput();
        }

        return redirect()->route('profile.edit')->with('success', $result['message']);
    }

    public function editPassword()
    {
        $user = Auth::user();
        return view('profile.change-password', compact('user'));
    }

    /**
     * Update user password with enhanced security
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $result = $this->profileService->changePassword($request->all());

        if (!$result['success']) {
            return back()->withErrors(['current_password' => $result['message']]);
        }

        return redirect()->route('password.edit')->with('success', $result['message']);
    }
}
