<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.contact'); // Chỉ định view cho trang liên hệ
    }

    public function send(Request $request)
    {
        // Validate input
        $request->validate([
            'branch' => 'required|string|max:255',
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $data = $request->only(['branch', 'fullname', 'email', 'phone', 'subject', 'message']);

        try {
            // Lưu vào database
            DB::table('contact')->insert([
                'branch' => $data['branch'],
                'fullname' => $data['fullname'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'subject' => $data['subject'],
                'message' => $data['message'],
                'created_at' => now(),
            ]);

            // Gửi email
            $this->sendEmail($data);

            return redirect()->back()->with('success', '✅ Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Có lỗi xảy ra khi gửi liên hệ. Vui lòng thử lại!');
        }
    }

    protected function sendEmail($data)
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = env('MAIL_USERNAME'); // Thay đổi email của bạn
        $mail->Password = env('MAIL_PASSWORD'); // Thay đổi password của bạn
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($data['email'], $data['fullname']);
        $mail->addAddress('info@futa.vn', 'FUTA Bus Lines');

        $mail->isHTML(true);
        $mail->Subject = "Liên hệ từ website: " . $data['subject'];
        $mail->Body = "
            <h3>Thông tin liên hệ mới</h3>
            <p><strong>Chi nhánh:</strong> {$data['branch']}</p>
            <p><strong>Họ tên:</strong> {$data['fullname']}</p>
            <p><strong>Email:</strong> {$data['email']}</p>
            <p><strong>Điện thoại:</strong> {$data['phone']}</p>
            <p><strong>Tiêu đề:</strong> {$data['subject']}</p>
            <p><strong>Nội dung:</strong></p>
            <p>{$data['message']}</p>
        ";

        $mail->send();
    }
}
