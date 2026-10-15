<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modifikasi tabel users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'id_mhs')) {
                $table->unsignedBigInteger('id_mhs')->nullable()->after('id_divisi');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 30)->nullable()->after('email');
            }
        });

        // Ubah role kolom users agar bisa menampung 'Peserta', 'Admin', 'Super Admin'
        try {
            DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'Admin'");
        } catch (\Exception $e) {
            // fallback jika bukan MySQL
        }

        // 2. Modifikasi tabel mahasiswa
        Schema::table('mahasiswa', function (Blueprint $table) {
            if (!Schema::hasColumn('mahasiswa', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id_mhs');
            }
            if (!Schema::hasColumn('mahasiswa', 'emergency_contact_name')) {
                $table->string('emergency_contact_name', 150)->nullable()->after('no_telepon');
            }
            if (!Schema::hasColumn('mahasiswa', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone', 30)->nullable()->after('emergency_contact_name');
            }
        });

        // 3. Tabel companies
        if (!Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table) {
                $table->id('id_company');
                $table->string('name', 200);
                $table->text('description')->nullable();
                $table->string('industry', 100)->default('Pembangkit Listrik Tenaga Uap');
                $table->text('address');
                $table->decimal('latitude', 10, 7)->default(-7.6808102);
                $table->decimal('longitude', 10, 7)->default(109.1366922);
                $table->string('phone', 50)->nullable();
                $table->string('email', 100)->nullable();
                $table->string('website', 150)->nullable();
                $table->string('logo_path', 255)->nullable();
                $table->time('work_start_time')->default('08:00:00');
                $table->time('work_end_time')->default('16:00:00');
                $table->string('timezone', 50)->default('Asia/Jakarta');
                $table->integer('attendance_radius_meters')->default(150);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 4. Tabel attendance_policies
        if (!Schema::hasTable('attendance_policies')) {
            Schema::create('attendance_policies', function (Blueprint $table) {
                $table->id('id_policy');
                $table->unsignedBigInteger('company_id')->nullable();
                $table->time('check_in_start')->default('06:30:00');
                $table->time('check_in_deadline')->default('08:15:00');
                $table->time('check_out_start')->default('16:00:00');
                $table->time('check_out_deadline')->default('20:00:00');
                $table->string('work_days', 100)->default('Senin,Selasa,Rabu,Kamis,Jumat');
                $table->integer('allowed_radius_meters')->default(150);
                $table->integer('location_accuracy_limit')->default(50);
                $table->integer('late_tolerance_minutes')->default(15);
                $table->string('attendance_method', 50)->default('qr_geofence');
                $table->boolean('require_selfie')->default(false);
                $table->boolean('allow_manual_correction')->default(true);
                $table->timestamps();
            });
        }

        // 5. Tabel attendance_qr_sessions
        if (!Schema::hasTable('attendance_qr_sessions')) {
            Schema::create('attendance_qr_sessions', function (Blueprint $table) {
                $table->id('id_qr_session');
                $table->unsignedBigInteger('company_id')->nullable();
                $table->string('token_hash', 100)->unique();
                $table->dateTime('valid_from');
                $table->dateTime('valid_until');
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->integer('radius_meters')->default(150);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 6. Tabel attendances
        if (!Schema::hasTable('attendances')) {
            Schema::create('attendances', function (Blueprint $table) {
                $table->id('id_attendance');
                $table->unsignedBigInteger('mahasiswa_id')->index();
                $table->date('attendance_date');
                $table->dateTime('check_in_at')->nullable();
                $table->dateTime('check_out_at')->nullable();
                $table->string('check_in_status', 30)->default('present'); // present, late, absent, leave, sick
                $table->string('check_out_status', 30)->nullable();
                $table->string('work_status', 30)->default('wfo'); // wfo, wfh, official_duty
                $table->integer('late_minutes')->default(0);
                $table->integer('total_work_minutes')->default(0);
                $table->decimal('latitude_in', 10, 7)->nullable();
                $table->decimal('longitude_in', 10, 7)->nullable();
                $table->decimal('distance_in_meters', 8, 2)->nullable();
                $table->decimal('accuracy_in', 8, 2)->nullable();
                $table->decimal('latitude_out', 10, 7)->nullable();
                $table->decimal('longitude_out', 10, 7)->nullable();
                $table->decimal('distance_out_meters', 8, 2)->nullable();
                $table->string('qr_token', 100)->nullable();
                $table->string('selfie_path', 255)->nullable();
                $table->string('verification_method', 50)->default('qr_geofence');
                $table->string('approval_status', 30)->default('verified'); // verified, pending, rejected, manual_review
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->dateTime('reviewed_at')->nullable();
                $table->timestamps();

                $table->unique(['mahasiswa_id', 'attendance_date'], 'unique_mhs_daily_attendance');
            });
        }

        // 7. Tabel attendance_corrections
        if (!Schema::hasTable('attendance_corrections')) {
            Schema::create('attendance_corrections', function (Blueprint $table) {
                $table->id('id_correction');
                $table->unsignedBigInteger('attendance_id')->nullable()->index();
                $table->unsignedBigInteger('mahasiswa_id')->index();
                $table->date('correction_date');
                $table->string('correction_type', 50); // check_in, check_out, check_in_and_check_out, attendance_status, other
                $table->dateTime('requested_check_in_at')->nullable();
                $table->dateTime('requested_check_out_at')->nullable();
                $table->text('reason');
                $table->string('attachment_path', 255)->nullable();
                $table->string('status', 30)->default('submitted'); // submitted, under_review, approved, rejected, cancelled
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->dateTime('reviewed_at')->nullable();
                $table->text('review_note')->nullable();
                $table->timestamps();
            });
        }

        // 8. Tabel daily_logbooks
        if (!Schema::hasTable('daily_logbooks')) {
            Schema::create('daily_logbooks', function (Blueprint $table) {
                $table->id('id_logbook');
                $table->unsignedBigInteger('mahasiswa_id')->index();
                $table->date('logbook_date');
                $table->string('title', 200);
                $table->time('start_time')->default('08:00:00');
                $table->time('end_time')->default('16:00:00');
                $table->text('activity');
                $table->text('result')->nullable();
                $table->text('obstacle')->nullable();
                $table->text('solution')->nullable();
                $table->text('next_plan')->nullable();
                $table->string('attachment_path', 255)->nullable();
                $table->string('attachment_name', 200)->nullable();
                $table->string('status', 30)->default('draft'); // draft, submitted, under_review, revision_required, approved, rejected
                $table->dateTime('submitted_at')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->dateTime('reviewed_at')->nullable();
                $table->text('review_note')->nullable();
                $table->timestamps();
            });
        }

        // 9. Tabel logbook_comments
        if (!Schema::hasTable('logbook_comments')) {
            Schema::create('logbook_comments', function (Blueprint $table) {
                $table->id('id_comment');
                $table->unsignedBigInteger('logbook_id')->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->text('comment');
                $table->timestamps();
            });
        }

        // 10. Tabel complaints (Aduan Masalah)
        if (!Schema::hasTable('complaints')) {
            Schema::create('complaints', function (Blueprint $table) {
                $table->id('id_complaint');
                $table->unsignedBigInteger('mahasiswa_id')->index();
                $table->string('complaint_number', 50)->unique();
                $table->string('category', 50); // technical, work_environment, schedule, supervision, administration, safety, harassment, other
                $table->string('title', 200);
                $table->text('description');
                $table->string('priority', 20)->default('medium'); // low, medium, high, urgent
                $table->string('confidentiality', 50)->default('visible_to_company'); // visible_to_company, visible_to_campus, visible_to_both, restricted
                $table->string('attachment_path', 255)->nullable();
                $table->string('status', 30)->default('submitted'); // submitted, verified, in_progress, waiting_for_participant, resolved, closed, rejected
                $table->unsignedBigInteger('assigned_to')->nullable();
                $table->dateTime('submitted_at')->nullable();
                $table->dateTime('resolved_at')->nullable();
                $table->timestamps();
            });
        }

        // 11. Tabel complaint_responses
        if (!Schema::hasTable('complaint_responses')) {
            Schema::create('complaint_responses', function (Blueprint $table) {
                $table->id('id_response');
                $table->unsignedBigInteger('complaint_id')->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->text('message');
                $table->boolean('is_internal_note')->default(false);
                $table->timestamps();
            });
        }

        // 12. Tabel evaluation_criteria (Kriteria Penilaian)
        if (!Schema::hasTable('evaluation_criteria')) {
            Schema::create('evaluation_criteria', function (Blueprint $table) {
                $table->id('id_criterion');
                $table->string('criterion_name', 100);
                $table->text('description')->nullable();
                $table->integer('weight')->default(10);
                $table->integer('maximum_score')->default(100);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 13. Tabel daily_evaluations
        if (!Schema::hasTable('daily_evaluations')) {
            Schema::create('daily_evaluations', function (Blueprint $table) {
                $table->id('id_evaluation');
                $table->unsignedBigInteger('mahasiswa_id')->index();
                $table->date('evaluation_date');
                $table->decimal('total_score', 5, 2)->default(0);
                $table->text('participant_note')->nullable();
                $table->text('internal_note')->nullable();
                $table->unsignedBigInteger('evaluator_id')->nullable();
                $table->string('status', 30)->default('published'); // draft, published, revised, archived
                $table->dateTime('published_at')->nullable();
                $table->timestamps();
            });
        }

        // 14. Tabel evaluation_details
        if (!Schema::hasTable('evaluation_details')) {
            Schema::create('evaluation_details', function (Blueprint $table) {
                $table->id('id_detail');
                $table->unsignedBigInteger('daily_evaluation_id')->index();
                $table->unsignedBigInteger('criterion_id')->index();
                $table->decimal('score', 5, 2)->default(0);
                $table->string('notes', 255)->nullable();
                $table->timestamps();
            });
        }

        // 15. Tabel leave_requests (Cuti & Izin)
        if (!Schema::hasTable('leave_requests')) {
            Schema::create('leave_requests', function (Blueprint $table) {
                $table->id('id_leave');
                $table->unsignedBigInteger('mahasiswa_id')->index();
                $table->string('leave_type', 50); // personal_leave, sick_leave, academic_leave, official_permission, emergency, other
                $table->date('start_date');
                $table->date('end_date');
                $table->text('reason');
                $table->string('emergency_contact', 100)->nullable();
                $table->string('attachment_path', 255)->nullable();
                $table->string('status', 30)->default('submitted'); // draft, submitted, under_review, approved, rejected, cancelled
                $table->dateTime('submitted_at')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->dateTime('reviewed_at')->nullable();
                $table->text('review_note')->nullable();
                $table->timestamps();
            });
        }

        // 16. Tabel company_information (Informasi Perusahaan, Tata Tertib, Pengumuman, Dokumen)
        if (!Schema::hasTable('company_information')) {
            Schema::create('company_information', function (Blueprint $table) {
                $table->id('id_info');
                $table->string('title', 200);
                $table->string('category', 50); // general, work_rule, safety, announcement, contact, document
                $table->text('content');
                $table->string('attachment_path', 255)->nullable();
                $table->boolean('is_published')->default(true);
                $table->dateTime('published_at')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 17. Tabel app_notifications (Notifikasi Pengguna)
        if (!Schema::hasTable('app_notifications')) {
            Schema::create('app_notifications', function (Blueprint $table) {
                $table->id('id_notification');
                $table->unsignedBigInteger('user_id')->index();
                $table->string('type', 50)->default('info'); // info, success, warning, danger
                $table->string('title', 150);
                $table->text('message');
                $table->string('link', 255)->nullable();
                $table->boolean('is_read')->default(false);
                $table->dateTime('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_notifications');
        Schema::dropIfExists('company_information');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('evaluation_details');
        Schema::dropIfExists('daily_evaluations');
        Schema::dropIfExists('evaluation_criteria');
        Schema::dropIfExists('complaint_responses');
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('logbook_comments');
        Schema::dropIfExists('daily_logbooks');
        Schema::dropIfExists('attendance_corrections');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('attendance_qr_sessions');
        Schema::dropIfExists('attendance_policies');
        Schema::dropIfExists('companies');

        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'emergency_contact_name', 'emergency_contact_phone']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['id_mhs', 'phone']);
        });
    }
};
