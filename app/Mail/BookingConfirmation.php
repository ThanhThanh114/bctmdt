<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $bookings;
    public $bookingCode;
    public $totalAmount;
    public $discountAmount;

    /**
     * Create a new message instance.
     */
    public function __construct($bookings, $bookingCode, $totalAmount = 0, $discountAmount = 0)
    {
        $this->bookings = $bookings;
        $this->bookingCode = $bookingCode;
        $this->totalAmount = $totalAmount;
        $this->discountAmount = $discountAmount;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Xác nhận đặt vé thành công - ' . $this->bookingCode)
            ->view('emails.booking-confirmation');
    }
}
