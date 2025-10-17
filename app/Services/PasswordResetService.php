<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class PasswordResetService
{
    /**
     * Initiate password reset process
     *
     * @param string $email
     * @return array
     */
    public function initiateReset(string $email): array
    {
        // Find user by email
        $user = User::where('email', $email)->first();

        if (!$user) {
            Log::warning('Password reset attempted for non-existent email', ['email' => $email]);
            return [
                'success' => false,
                'message' => 'Không tìm thấy tài khoản với email này.'
            ];
        }

        // Generate OTP
        $otp = $this->generateOTP();

        // Store reset data in cache (more secure than database)
        $cacheKey = 'password_reset:' . $user->id;
        $resetData = [
            'email' => $user->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(5),
            'created_at' => now()
        ];

        Cache::put($cacheKey, $resetData, now()->addMinutes(5));

        // Send OTP via email
        try {
            $this->sendOTPEmail($user->email, $user->fullname, $otp);
            Log::info('OTP sent successfully for password reset', ['email' => $user->email]);

            return [
                'success' => true,
                'message' => 'Mã OTP đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư.'
            ];
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage(), [
                'email' => $user->email,
                'trace' => $e->getTraceAsString()
            ]);

            // For development, show OTP in response
            if (app()->environment(['local', 'testing'])) {
                return [
                    'success' => true,
                    'message' => 'Mã OTP: <strong>' . $otp . '</strong> (Email tạm thời không gửi được, vui lòng dùng mã này)',
                    'dev_otp' => $otp
                ];
            }

            return [
                'success' => false,
                'message' => 'Không thể gửi email. Vui lòng thử lại sau.'
            ];
        }
    }

    /**
     * Verify OTP for password reset
     *
     * @param string $email
     * @param string $otp
     * @return array
     */
    public function verifyOTP(string $email, string $otp): array
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Email không tồn tại trong hệ thống.'
            ];
        }

        $cacheKey = 'password_reset:' . $user->id;
        $resetData = Cache::get($cacheKey);

        if (!$resetData) {
            return [
                'success' => false,
                'message' => 'Phiên làm việc đã hết hạn. Vui lòng yêu cầu OTP mới.'
            ];
        }

        if ($resetData['expires_at']->isPast()) {
            Cache::forget($cacheKey);
            return [
                'success' => false,
                'message' => 'Mã OTP đã hết hạn. Vui lòng yêu cầu mã mới.'
            ];
        }

        if ($resetData['otp'] !== $otp) {
            return [
                'success' => false,
                'message' => 'Mã OTP không chính xác.'
            ];
        }

        // Generate secure reset token
        $resetToken = Str::random(60);

        // Update cache with reset token
        $resetData['reset_token'] = $resetToken;
        $resetData['otp_verified_at'] = now();
        Cache::put($cacheKey, $resetData, now()->addMinutes(15));

        return [
            'success' => true,
            'reset_token' => $resetToken,
            'message' => 'Xác thực OTP thành công.'
        ];
    }

    /**
     * Reset password with token
     *
     * @param string $email
     * @param string $token
     * @param string $password
     * @return array
     */
    public function resetPassword(string $email, string $token, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Email không tồn tại trong hệ thống.'
            ];
        }

        $cacheKey = 'password_reset:' . $user->id;
        $resetData = Cache::get($cacheKey);

        if (!$resetData || !isset($resetData['reset_token'])) {
            return [
                'success' => false,
                'message' => 'Token không hợp lệ. Vui lòng yêu cầu đặt lại mật khẩu mới.'
            ];
        }

        if ($resetData['reset_token'] !== $token) {
            return [
                'success' => false,
                'message' => 'Token không chính xác.'
            ];
        }

        if ($resetData['expires_at']->isPast()) {
            Cache::forget($cacheKey);
            return [
                'success' => false,
                'message' => 'Token đã hết hạn. Vui lòng yêu cầu đặt lại mật khẩu mới.'
            ];
        }

        // Update password
        $user->update([
            'password' => bcrypt($password),
            'reset_token' => null,
            'reset_token_expires_at' => null
        ]);

        // Clear reset cache
        Cache::forget($cacheKey);

        Log::info('Password reset successfully', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        return [
            'success' => true,
            'message' => 'Mật khẩu đã được đặt lại thành công!'
        ];
    }

    /**
     * Generate secure OTP
     *
     * @return string
     */
    private function generateOTP(): string
    {
        return str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Send OTP email using PHPMailer
     *
     * @param string $email
     * @param string $fullname
     * @param string $otp
     * @return void
     * @throws PHPMailerException
     */
    private function sendOTPEmail(string $email, string $fullname, string $otp): void
    {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = config('mail.mailers.smtp.host');
        $mail->SMTPAuth = config('mail.mailers.smtp.auth');
        $mail->Username = config('mail.mailers.smtp.username');
        $mail->Password = config('mail.mailers.smtp.password');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = config('mail.mailers.smtp.port', 587);
        $mail->CharSet = 'UTF-8';

        $mail->setFrom(config('mail.from.address'), config('mail.from.name', 'Hệ thống đặt vé xe'));
        $mail->addAddress($email, $fullname);

        $mail->isHTML(true);
        $mail->Subject = 'Mã OTP khôi phục mật khẩu';
        $mail->Body = $this->getOTPEmailTemplate($fullname, $otp);

        $mail->send();
    }

    /**
     * Get HTML email template for OTP
     *
     * @param string $fullname
     * @param string $otp
     * @return string
     */
    private function getOTPEmailTemplate(string $fullname, string $otp): string
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
}
