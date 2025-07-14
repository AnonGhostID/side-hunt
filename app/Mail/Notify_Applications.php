<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Notify_Applications extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $status;

    /**
     * Create a new message instance.
     */
    public function __construct($data, $status)
    {
        $this->data = $data;
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update Lamaran - Side-Hunt',
        );
    }
    public function build()
    {
        $serverUrl = request()->getSchemeAndHttpHost();
        $data = $this->data;
        // dd($data);
        if ($this->status == 'interview') {
            return $this->subject('Selamat! Lamaran Anda Lanjut Ke Tahap Selanjutnya')
                ->view('Dewa.notifikasi_ke_email.pekerjaan.lolos_ke_interview')
                ->with([
                    compact('data')
                ]);
        } elseif ($this->status == 'Menunggu Pekerjaan') {
            return $this->subject('Selamat! Lamaran Anda Lolos dan siap bekerja')
                ->view('Dewa.notifikasi_ke_email.pekerjaan.lolos_siap_bekerja')
                ->with([
                    compact('data')
                ]);
        } elseif ($this->status == 'ditolak'||$this->status == 'Gagal') {
            return $this->subject('Terima Kasih atas Partisipasi Anda')
                ->view('Dewa.notifikasi_ke_email.pekerjaan.tolak')
                ->with([
                    compact('data')
                ]);
        }
    }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

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
