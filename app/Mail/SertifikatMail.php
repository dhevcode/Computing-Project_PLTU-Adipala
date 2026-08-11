<?php

namespace App\Mail;

use App\Models\Mahasiswa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SertifikatMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mahasiswa;
    public $filePath;

    /**
     * Create a new message instance.
     */
    public function __construct(
        Mahasiswa $mahasiswa,
        string $filePath
    ) {
        $this->mahasiswa = $mahasiswa;
        $this->filePath = $filePath;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this
            ->subject('Sertifikat PKL Anda Telah Diterbitkan')
            ->view('emails.sertifikat')
            ->attach($this->filePath, [
                'as' => 'Sertifikat-PKL-' . $this->mahasiswa->nim . '.' .
                    pathinfo($this->filePath, PATHINFO_EXTENSION),
                'mime' => mime_content_type($this->filePath),
            ]);
    }
}