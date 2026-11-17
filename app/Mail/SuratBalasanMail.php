<?php

namespace App\Mail;

use App\Models\PengajuanMagang;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SuratBalasanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pengajuan;
    public $filePath;

    /** Informasi akun portal peserta (username, password|null, baru) — null bila tidak disertakan. */
    public ?array $akun;

    /**
     * Create a new message instance.
     */
    public function __construct(
        PengajuanMagang $pengajuan,
        string $filePath,
        ?array $akun = null
    ) {
        $this->pengajuan = $pengajuan;
        $this->filePath = $filePath;
        $this->akun = $akun;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Surat Balasan Permohonan Magang/PKL',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.surat-balasan',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath(
                Storage::disk('public')->path($this->filePath)
            )
                ->as('Surat Balasan Magang.pdf')
                ->withMime('application/pdf'),
        ];
    }
}