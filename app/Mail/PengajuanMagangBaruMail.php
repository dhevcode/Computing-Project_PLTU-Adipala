<?php

namespace App\Mail;

use App\Models\Mahasiswa;
use App\Models\PengajuanMagang;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengajuanMagangBaruMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mahasiswa;
    public $pengajuan;

    /**
     * Create a new message instance.
     */
    public function __construct(Mahasiswa $mahasiswa, PengajuanMagang $pengajuan)
    {
        $this->mahasiswa = $mahasiswa;
        $this->pengajuan = $pengajuan;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $nama = $this->mahasiswa->nama_lengkap ?? 'Peserta';
        $univ = $this->mahasiswa->universitas->nama_universitas ?? 'Perguruan Tinggi';

        return new Envelope(
            subject: "pengajuan magang '{$nama}' dari universitas '{$univ}'.",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pengajuan_magang_baru',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        $attachments = [];
        $cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $this->mahasiswa->nama_lengkap ?? 'peserta');

        // 1. Berkas Surat Permohonan / Pengantar
        if (!empty($this->pengajuan->surat_permohonan)) {
            $suratPath = public_path('uploads/surat_permohonan/' . $this->pengajuan->surat_permohonan);
            if (file_exists($suratPath)) {
                $ext = pathinfo($suratPath, PATHINFO_EXTENSION);
                $attachments[] = Attachment::fromPath($suratPath)
                    ->as("Surat_Permohonan_{$cleanName}.{$ext}");
            }
        }

        // 2. Berkas Proposal Magang
        if (!empty($this->pengajuan->proposal)) {
            $proposalPath = public_path('uploads/proposal/' . $this->pengajuan->proposal);
            if (file_exists($proposalPath)) {
                $ext = pathinfo($proposalPath, PATHINFO_EXTENSION);
                $attachments[] = Attachment::fromPath($proposalPath)
                    ->as("Proposal_Magang_{$cleanName}.{$ext}");
            }
        }

        // 3. Berkas CV
        if (!empty($this->pengajuan->cv)) {
            $cvPath = public_path('uploads/cv/' . $this->pengajuan->cv);
            if (file_exists($cvPath)) {
                $ext = pathinfo($cvPath, PATHINFO_EXTENSION);
                $attachments[] = Attachment::fromPath($cvPath)
                    ->as("CV_{$cleanName}.{$ext}");
            }
        }

        return $attachments;
    }
}

