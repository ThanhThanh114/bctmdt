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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

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
        $validated = $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string|min:1'
        ], [
            'identifier.required' => 'Vui lòng nhập tên đăng nhập, email hoặc số điện thoại',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu không được để trống'
        ]);

        $identifier = $validated['identifier'];
        $password = $validated['password'];

        // Tìm user theo username, email hoặc phone
        $user = User::where('username', $identifier)
            ->orWhere('email', $identifier)
            ->orWhere('phone', $identifier)
            ->first();

        if (!$user) {
            Log::warning('Login failed: User not found', ['identifier' => $identifier]);
            return back()->withErrors([
                'identifier' => '❌ Tài khoản không tồn tại trong hệ thống! Vui lòng kiểm tra lại hoặc đăng ký tài khoản mới.',
            ])->withInput($request->only('identifier'));
        }

        // Kiểm tra mật khẩu an toàn (hỗ trợ cả plain text cũ và bcrypt)
        $storedPassword = (string) $user->password;

        $isBcryptHash = str_starts_with($storedPassword, '$2y$');
        $passwordValid = false;

        // 1) Tương thích ngược: DB đang lưu plain text
        if ($storedPassword !== '' && !$isBcryptHash && hash_equals($storedPassword, $password)) {
            $passwordValid = true;
            // Nâng cấp: đổi sang bcrypt để đảm bảo an toàn cho lần sau
            $user->password = Hash::make($password);
            $user->save();
        }

        // 2) Chuẩn: DB đã lưu bcrypt
        if (!$passwordValid && $isBcryptHash && Hash::check($password, $storedPassword)) {
            $passwordValid = true;
        }

        if ($passwordValid) {
            Auth::login($user);
            $request->session()->regenerate();

            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => $user->role
            ]);

            // Chuyển hướng dựa trên role
            switch (strtolower($user->role)) {
                case 'admin':
                    return redirect()->route('admin.dashboard')->with('success', 'Chào mừng Admin ' . $user->fullname);
                case 'staff':
                    return redirect()->route('staff.dashboard')->with('success', 'Chào mừng nhân viên ' . $user->fullname);
                case 'bus_owner':
                    return redirect()->route('bus-owner.dashboard')->with('success', 'Chào mừng chủ xe ' . $user->fullname);
                case 'user':
                default:
                    return redirect()->route('home')->with('success', 'Đăng nhập thành công! Chào ' . $user->fullname);
            }
        }

        Log::warning('Login failed: Invalid password', ['identifier' => $identifier]);
        return back()->withErrors([
            'password' => '❌ Mật khẩu không chính xác! Vui lòng thử lại hoặc sử dụng chức năng "Quên mật khẩu".',
        ])->withInput($request->only('identifier'));
    }

    public function register(Request $request)
    {
        // Log dữ liệu nhận được
        Log::info('Registration attempt', [
            'data' => $request->except('password', 'password_confirmation')
        ]);

        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users|regex:/^[a-zA-Z0-9_]+$/',
            'fullname' => 'required|string|max:100|min:3',
            'email' => 'required|string|email|max:100|unique:users',
            'phone' => 'required|string|max:15|unique:users|regex:/^[0-9]{10,11}$/',
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                // Uncomment for stronger password requirements:
                // Password::min(8)
                //     ->letters()
                //     ->mixedCase()
                //     ->numbers()
                //     ->symbols()
            ]
        ], [
            // Username messages
            'username.required' => 'Vui lòng nhập tên đăng nhập',
            'username.max' => 'Tên đăng nhập không được quá 50 ký tự',
            'username.unique' => '❌ Tên đăng nhập đã tồn tại! Vui lòng chọn tên khác.',
            'username.regex' => '❌ Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới',

            // Fullname messages
            'fullname.required' => 'Vui lòng nhập họ và tên',
            'fullname.max' => 'Họ và tên không được quá 100 ký tự',
            'fullname.min' => '❌ Họ và tên phải có ít nhất 3 ký tự',

            // Email messages
            'email.required' => 'Vui lòng nhập địa chỉ email',
            'email.email' => '❌ Địa chỉ email không đúng định dạng (VD: example@gmail.com)',
            'email.max' => 'Email không được quá 100 ký tự',
            'email.unique' => '❌ Email đã được sử dụng! Vui lòng dùng email khác hoặc đăng nhập.',

            // Phone messages
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.max' => 'Số điện thoại không được quá 15 ký tự',
            'phone.unique' => '❌ Số điện thoại đã được đăng ký! Vui lòng dùng số khác hoặc đăng nhập.',
            'phone.regex' => '❌ Số điện thoại phải là 10-11 chữ số (VD: 0901234567)',

            // Password messages
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => '❌ Mật khẩu phải có ít nhất 6 ký tự để đảm bảo an toàn',
            'password.confirmed' => '❌ Mật khẩu xác nhận không khớp! Vui lòng kiểm tra lại.'
        ]);

        try {
            $user = User::create([
                'username' => $validated['username'],
                'fullname' => $validated['fullname'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'user'
            ]);

            Log::info('User registered successfully', ['user_id' => $user->id, 'username' => $user->username]);

            Auth::login($user);

            return redirect()->route('home')->with('success', 'Đăng ký thành công! Chào mừng bạn đến với hệ thống.');

        } catch (\Exception $e) {
            // Log the actual error for debugging
            Log::error('Registration error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại sau.');
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

        // Lưu email vào session để verify
        session(['reset_email' => $user->email]);

        // Gửi OTP qua PHPMailer (không block nếu fail)
        try {
            \Log::info('Attempting to send OTP to: ' . $user->email);
            $this->sendOTPEmail($user->email, $user->fullname, $otp);
            \Log::info('OTP sent successfully to: ' . $user->email);
            $message = 'Mã OTP đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư.';
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            $message = 'Mã OTP: <strong>' . $otp . '</strong> (Email tạm thời không gửi được, vui lòng dùng mã này)';
        }

        return redirect()->route('password.verify-otp')->with('success', $message);
    }

    private function sendOTPEmail($email, $fullname, $otp)
    {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom(env('MAIL_FROM_ADDRESS', 'noreply@example.com'), 'Hệ thống đặt vé xe');
        $mail->addAddress($email, $fullname);

        $mail->isHTML(true);
        $mail->Subject = 'Mã OTP khôi phục mật khẩu';
        $mail->Body = $this->getOTPEmailTemplate($fullname, $otp);

        $mail->send();
    }

    private function getOTPEmailTemplate($fullname, $otp)
    {
        return '
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 0 20px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="background: linear-gradient(135deg, #FF7B39 0%, #FF5722 100%); padding: 30px; text-align: center;">
                            <h1 style="color: white; margin: 0; font-size: 24px;">🔐 Khôi phục mật khẩu</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <p style="font-size: 16px; color: #333;">Xin chào <strong>' . htmlspecialchars($fullname) . '</strong>,</p>
                            <p style="font-size: 14px; color: #666; line-height: 1.6;">Bạn đã yêu cầu khôi phục mật khẩu. Vui lòng sử dụng mã OTP dưới đây để xác nhận:</p>
                            
                            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin: 20px 0; border-radius: 5px; text-align: center;">
                                <p style="margin: 0; font-size: 14px; color: #856404;">Mã OTP của bạn:</p>
                                <p style="margin: 10px 0 0 0; font-size: 32px; color: #FF5722; font-weight: bold; letter-spacing: 5px;">' . $otp . '</p>
                            </div>

                            <div style="background: #fff3cd; border: 1px solid #ffc107; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0;">
                                <strong>⚠️ Lưu ý:</strong>
                                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                                    <li>Mã OTP có hiệu lực trong <strong>5 phút</strong></li>
                                    <li>Không chia sẻ mã này cho bất kỳ ai</li>
                                    <li>Nếu bạn không yêu cầu khôi phục mật khẩu, vui lòng bỏ qua email này</li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="background: #f8f9fa; padding: 20px; text-align: center; color: #666; font-size: 14px;">
                            <p style="margin: 0;">Email tự động từ hệ thống - Vui lòng không trả lời</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
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

        $email = session('reset_email');

        if (!$email) {
            return back()->withErrors(['otp' => 'Phiên làm việc đã hết hạn. Vui lòng yêu cầu OTP mới.']);
        }

        $user = User::where('email', $email)
            ->where('reset_token', $request->otp)
            ->where('reset_token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Mã OTP không hợp lệ hoặc đã hết hạn.']);
        }

        // OTP valid, lưu token để reset password
        session(['reset_token' => $request->otp]);

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