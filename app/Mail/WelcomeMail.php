<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectText;
    public $data;
    public $viewFile;
    public $replyEmail;

    /**
     * Create a new message instance.
     */
    public function __construct($subjectText, $viewFile, $data = [], $replyEmail = "")
    {
        $this->subjectText = $subjectText;
        $this->viewFile = $viewFile;
        $this->data = $data;
        $this->replyEmail = $replyEmail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectText,
            replyTo: [
                new \Illuminate\Mail\Mailables\Address($this->replyEmail)
            ]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->viewFile, // change to your view
            with: $this->data      // pass data to the view
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
