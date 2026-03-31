<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $booking;
    public $bike;
    public $selected_accessories;
    public $included_accessories;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $booking, $bike, $selected_accessories, $included_accessories)
    {
        $this->user = $user;
        $this->booking = $booking;
        $this->bike = $bike;
        $this->selected_accessories = $selected_accessories;
        $this->included_accessories = $included_accessories;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Details',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.bookingEmail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
