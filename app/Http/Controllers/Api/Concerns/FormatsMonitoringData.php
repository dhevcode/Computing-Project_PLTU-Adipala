<?php

namespace App\Http\Controllers\Api\Concerns;

use App\Models\AppNotification;
use App\Models\Attendance;
use App\Models\AttendanceCorrection;
use App\Models\Company;
use App\Models\CompanyInformation;
use App\Models\Complaint;
use App\Models\DailyEvaluation;
use App\Models\DailyLogbook;
use App\Models\LeaveRequest;
use App\Models\Mahasiswa;
use App\Models\PengajuanMagang;

/**
 * Format data monitoring untuk API. Label mengikuti tampilan web (resources/views/peserta/*).
 * Dipakai oleh API peserta, pembimbing, dan admin agar bentuk datanya sama.
 */
trait FormatsMonitoringData
{
    public const LOGBOOK_STATUS = [
        'draft' => 'Draft',
        'submitted' => 'Menunggu Review',
        'under_review' => 'Sedang Ditinjau',
        'revision_required' => 'Perlu Revisi',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
    ];

    public const LEAVE_STATUS = [
        'draft' => 'Draft',
        'submitted' => 'Menunggu Review',
        'under_review' => 'Sedang Diperiksa',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'cancelled' => 'Dibatalkan',
    ];

    public const LEAVE_TYPES = [
        'personal_leave' => 'Cuti Pribadi',
        'sick_leave' => 'Izin Sakit',
        'academic_leave' => 'Izin Kegiatan Akademik',
        'official_permission' => 'Izin Dinas / Resmi',
        'emergency' => 'Keadaan Darurat',
        'other' => 'Lainnya',
    ];

    public const COMPLAINT_STATUS = [
        'submitted' => 'Aduan Diterima',
        'verified' => 'Terverifikasi',
        'in_progress' => 'Sedang Ditangani',
        'waiting_for_participant' => 'Menunggu Respon Anda',
        'resolved' => 'Selesai Ditangani',
        'closed' => 'Aduan Ditutup',
        'rejected' => 'Ditolak',
    ];

    public const COMPLAINT_CATEGORIES = [
        'technical' => 'Kendala Teknis/Sistem',
        'work_environment' => 'Lingkungan Kerja',
        'schedule' => 'Jadwal Kerja',
        'supervision' => 'Proses Pembimbingan',
        'administration' => 'Administrasi/Dokumen',
        'safety' => 'Keselamatan Kerja (K3)',
        'harassment' => 'Perilaku Tidak Pantas',
        'other' => 'Lainnya',
    ];

    public const COMPLAINT_PRIORITIES = [
        'low' => 'Rendah',
        'medium' => 'Sedang',
        'high' => 'Tinggi',
        'urgent' => 'Mendesak',
    ];

    public const COMPLAINT_CONFIDENTIALITY = [
        'visible_to_company' => 'Hanya Pihak Perusahaan (PLTU)',
        'visible_to_campus' => 'Hanya Pihak Kampus / Dosen',
        'visible_to_both' => 'Perusahaan & Kampus',
        'restricted' => 'Akses Terbatas (Hanya Petugas Khusus)',
    ];

    public const CORRECTION_TYPES = [
        'check_in' => 'Lupa / Error Check-in Masuk',
        'check_out' => 'Lupa / Error Check-out Pulang',
        'check_in_and_check_out' => 'Tidak Bisa Masuk & Pulang',
        'attendance_status' => 'Penugasan Dinas Luar',
        'other' => 'Gangguan Server / Jaringan',
    ];

    public const CORRECTION_STATUS = [
        'submitted' => 'Menunggu Review',
        'under_review' => 'Sedang Ditinjau',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'cancelled' => 'Dibatalkan',
    ];

    public const ATTENDANCE_STATUS = [
        'present' => 'Hadir',
        'late' => 'Terlambat',
        'absent' => 'Alpha',
        'leave' => 'Cuti / Izin',
        'sick' => 'Sakit',
    ];

    /**
     * Konfigurasi kantor (sama dengan fallback di PesertaController web).
     */
    protected function company(): Company
    {
        return Company::first() ?? new Company([
            'name' => 'PT PLN Indonesia Power UBP Jawa Tengah 2 Adipala',
            'latitude' => -7.680810191412908,
            'longitude' => 109.1366921585062,
            'attendance_radius_meters' => 150,
            'work_start_time' => '08:00:00',
            'work_end_time' => '16:00:00',
        ]);
    }

    protected function formatMahasiswa(?Mahasiswa $mhs, $penempatan = null): ?array
    {
        if (!$mhs) {
            return null;
        }

        $penempatan ??= $mhs->latestPengajuan()->with('divisi')->first();

        return [
            'id' => $mhs->id_mhs,
            'user_id' => $mhs->user_id,
            'nim' => $mhs->nim,
            'nama_lengkap' => $mhs->nama_lengkap,
            'jenis_kelamin' => $mhs->jenis_kelamin,
            'email' => $mhs->email,
            'no_telepon' => $mhs->no_telepon,
            'emergency_contact_name' => $mhs->emergency_contact_name,
            'emergency_contact_phone' => $mhs->emergency_contact_phone,
            'universitas' => $mhs->universitas->nama_universitas ?? null,
            'jurusan' => $mhs->jurusan->nama_jurusan ?? null,
            'penempatan' => $this->formatPenempatan($penempatan),
        ];
    }

    protected function formatPenempatan(?PengajuanMagang $p): ?array
    {
        if (!$p) {
            return null;
        }

        return [
            'id' => $p->id_pengajuan,
            'divisi' => $p->divisi->nama_divisi ?? null,
            'nama_pembimbing' => $p->pembimbing->nama_lengkap ?? $p->nama_pembimbing,
            'pembimbing_id' => $p->pembimbing_id,
            'tanggal_mulai' => static::date($p->tanggal_mulai),
            'tanggal_selesai' => static::date($p->tanggal_selesai),
            'status' => $p->status,
        ];
    }

    protected function formatAttendance(?Attendance $a): ?array
    {
        if (!$a) {
            return null;
        }

        return [
            'id' => $a->id_attendance,
            'mahasiswa_id' => $a->mahasiswa_id,
            'date' => static::date($a->attendance_date),
            'check_in_at' => static::dateTime($a->check_in_at),
            'check_out_at' => static::dateTime($a->check_out_at),
            'check_in_status' => $a->check_in_status,
            'check_in_status_label' => self::ATTENDANCE_STATUS[$a->check_in_status] ?? $a->check_in_status,
            'check_out_status' => $a->check_out_status,
            'work_status' => $a->work_status,
            'late_minutes' => (int) $a->late_minutes,
            'total_work_minutes' => (int) $a->total_work_minutes,
            'distance_in_meters' => $a->distance_in_meters !== null ? (float) $a->distance_in_meters : null,
            'distance_out_meters' => $a->distance_out_meters !== null ? (float) $a->distance_out_meters : null,
            'selfie_in_url' => static::fileUrl($a->selfie_path),
            'selfie_out_url' => static::fileUrl($a->selfie_out_path),
            'verification_method' => $a->verification_method,
            'approval_status' => $a->approval_status,
            'notes' => $a->notes,
        ];
    }

    protected function formatCorrection(AttendanceCorrection $c): array
    {
        return [
            'id' => $c->id_correction,
            'mahasiswa_id' => $c->mahasiswa_id,
            'attendance_id' => $c->attendance_id,
            'date' => static::date($c->correction_date),
            'type' => $c->correction_type,
            'type_label' => self::CORRECTION_TYPES[$c->correction_type] ?? $c->correction_type,
            'requested_check_in' => $c->requested_check_in_at ? \Carbon\Carbon::parse($c->requested_check_in_at)->format('H:i') : null,
            'requested_check_out' => $c->requested_check_out_at ? \Carbon\Carbon::parse($c->requested_check_out_at)->format('H:i') : null,
            'reason' => $c->reason,
            'attachment_url' => static::fileUrl($c->attachment_path),
            'status' => $c->status,
            'status_label' => self::CORRECTION_STATUS[$c->status] ?? $c->status,
            'review_note' => $c->review_note,
            'reviewer' => static::userSummary($c->reviewer),
            'reviewed_at' => static::dateTime($c->reviewed_at),
            'created_at' => static::dateTime($c->created_at),
        ];
    }

    protected function formatLogbook(DailyLogbook $l, bool $withComments = false): array
    {
        $data = [
            'id' => $l->id_logbook,
            'mahasiswa_id' => $l->mahasiswa_id,
            'date' => static::date($l->logbook_date),
            'title' => $l->title,
            'start_time' => static::time($l->start_time),
            'end_time' => static::time($l->end_time),
            'activity' => $l->activity,
            'result' => $l->result,
            'obstacle' => $l->obstacle,
            'solution' => $l->solution,
            'next_plan' => $l->next_plan,
            'attachment_url' => static::fileUrl($l->attachment_path),
            'attachment_name' => $l->attachment_name,
            'status' => $l->status,
            'status_label' => self::LOGBOOK_STATUS[$l->status] ?? $l->status,
            'can_edit' => in_array($l->status, ['draft', 'revision_required']),
            'submitted_at' => static::dateTime($l->submitted_at),
            'review_note' => $l->review_note,
            'reviewer' => static::userSummary($l->reviewer),
            'reviewed_at' => static::dateTime($l->reviewed_at),
        ];

        if ($withComments) {
            $data['comments'] = $l->comments->sortBy('created_at')->map(fn ($c) => [
                'id' => $c->id_comment,
                'comment' => $c->comment,
                'author' => static::userSummary($c->user),
                'created_at' => static::dateTime($c->created_at),
            ])->values();
        }

        return $data;
    }

    protected function formatLeave(LeaveRequest $l): array
    {
        return [
            'id' => $l->id_leave,
            'mahasiswa_id' => $l->mahasiswa_id,
            'type' => $l->leave_type,
            'type_label' => LeaveRequest::getLeaveTypeLabel($l->leave_type),
            'start_date' => static::date($l->start_date),
            'end_date' => static::date($l->end_date),
            'days' => $l->start_date && $l->end_date
                ? \Carbon\Carbon::parse($l->start_date)->diffInDays(\Carbon\Carbon::parse($l->end_date)) + 1
                : null,
            'reason' => $l->reason,
            'emergency_contact' => $l->emergency_contact,
            'attachment_url' => static::fileUrl($l->attachment_path),
            'status' => $l->status,
            'status_label' => self::LEAVE_STATUS[$l->status] ?? $l->status,
            'can_cancel' => $l->status === 'submitted',
            'review_note' => $l->review_note,
            'reviewer' => static::userSummary($l->reviewer),
            'reviewed_at' => static::dateTime($l->reviewed_at),
            'submitted_at' => static::dateTime($l->submitted_at ?? $l->created_at),
        ];
    }

    /**
     * @param bool $includeInternal true hanya untuk petugas (admin/pembimbing).
     */
    protected function formatComplaint(Complaint $c, ?int $viewerUserId = null, bool $includeInternal = false): array
    {
        $responses = $c->responses
            ->when(!$includeInternal, fn ($r) => $r->where('is_internal_note', false))
            ->sortBy('created_at')
            ->map(fn ($r) => [
                'id' => $r->id_response,
                'message' => $r->message,
                'is_internal_note' => (bool) $r->is_internal_note,
                'is_mine' => $viewerUserId !== null && (int) $r->user_id === $viewerUserId,
                'author' => static::userSummary($r->user),
                'created_at' => static::dateTime($r->created_at),
            ])->values();

        return [
            'id' => $c->id_complaint,
            'mahasiswa_id' => $c->mahasiswa_id,
            'number' => $c->complaint_number,
            'title' => $c->title,
            'category' => $c->category,
            'category_label' => self::COMPLAINT_CATEGORIES[$c->category] ?? $c->category,
            'description' => $c->description,
            'priority' => $c->priority,
            'priority_label' => self::COMPLAINT_PRIORITIES[$c->priority] ?? $c->priority,
            'confidentiality' => $c->confidentiality,
            'confidentiality_label' => self::COMPLAINT_CONFIDENTIALITY[$c->confidentiality] ?? $c->confidentiality,
            'attachment_url' => static::fileUrl($c->attachment_path),
            'status' => $c->status,
            'status_label' => self::COMPLAINT_STATUS[$c->status] ?? $c->status,
            'can_reply' => !in_array($c->status, ['closed', 'resolved']),
            'submitted_at' => static::dateTime($c->submitted_at ?? $c->created_at),
            'resolved_at' => static::dateTime($c->resolved_at),
            'responses' => $responses,
        ];
    }

    protected function formatEvaluation(DailyEvaluation $e, bool $includeInternal = false): array
    {
        $data = [
            'id' => $e->id_evaluation,
            'mahasiswa_id' => $e->mahasiswa_id,
            'date' => static::date($e->evaluation_date),
            'total_score' => (float) $e->total_score,
            'participant_note' => $e->participant_note,
            'status' => $e->status,
            'evaluator' => static::userSummary($e->evaluator),
            'published_at' => static::dateTime($e->published_at),
            'details' => $e->details->map(fn ($d) => [
                'criterion_id' => $d->criterion_id,
                'criterion' => $d->criterion->criterion_name ?? null,
                'weight' => $d->criterion->weight ?? null,
                'maximum_score' => $d->criterion->maximum_score ?? null,
                'score' => (float) $d->score,
                'notes' => $d->notes,
            ])->values(),
        ];

        if ($includeInternal) {
            $data['internal_note'] = $e->internal_note;
        }

        return $data;
    }

    protected function formatInformation(CompanyInformation $i): array
    {
        return [
            'id' => $i->id_info,
            'title' => $i->title,
            'category' => $i->category,
            'category_label' => CompanyInformation::getCategoryLabel($i->category),
            'content' => $i->content,
            'attachment_url' => static::fileUrl($i->attachment_path),
            'is_published' => (bool) $i->is_published,
            'published_at' => static::dateTime($i->published_at ?? $i->created_at),
            'created_at' => static::dateTime($i->created_at),
        ];
    }

    protected function formatNotification(AppNotification $n): array
    {
        return [
            'id' => $n->id_notification,
            'type' => $n->type,
            'title' => $n->title,
            'message' => $n->message,
            'target' => static::notificationTarget($n->link),
            'link' => $n->link,
            'is_read' => (bool) $n->is_read,
            'read_at' => static::dateTime($n->read_at),
            'created_at' => static::dateTime($n->created_at),
        ];
    }

    /**
     * Ubah link web (mis. http://host/peserta/absensi) menjadi nama halaman untuk aplikasi mobile.
     */
    protected static function notificationTarget(?string $link): ?string
    {
        if (!$link) {
            return null;
        }

        $path = trim((string) parse_url($link, PHP_URL_PATH), '/');
        $segments = explode('/', $path);
        $page = $segments[1] ?? $segments[0] ?? null;

        return match ($page) {
            'absensi' => 'absensi',
            'logbook' => 'logbook',
            'aduan' => 'aduan',
            'cuti' => 'cuti',
            'nilai' => 'nilai',
            'informasi', 'pengumuman' => 'informasi',
            'notifikasi' => 'notifikasi',
            'profil', 'profile' => 'profil',
            'dashboard' => 'dashboard',
            default => null,
        };
    }

    protected static function options(array $map): array
    {
        return collect($map)->map(fn ($label, $value) => ['value' => $value, 'label' => $label])->values()->all();
    }
}
