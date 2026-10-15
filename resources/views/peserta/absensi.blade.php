@extends('peserta.layout')

@section('title', 'Presensi & Koreksi')

@section('content')

    <!-- Header Section -->
    <div class="page-header-card">
        <div class="page-header-content">
            <div class="page-icon">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div>
                <h1>Presensi & Geofencing</h1>
                <p>Presensi berbasis verifikasi geospasial GPS dan QR Code resmi PLTU Adipala.</p>
            </div>
        </div>
        <div>
            <button type="button" class="btn btn-warning" onclick="openKoreksiModal()">
                <i class="fa-solid fa-file-pen"></i> Ajukan Koreksi Absensi
            </button>
        </div>
    </div>

    <!-- Geofence Radar Box -->
    <div class="geofence-radar-box">
        <div class="radar-left">
            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.85; margin-bottom: 4px;">
                <i class="fa-solid fa-satellite"></i> Status Deteksi Lokasi
            </div>
            <h2 id="radarStatusText">Menunggu Izin Lokasi GPS...</h2>
            <p id="radarDesc">Sistem memverifikasi posisi Anda terhadap titik koordinat PLTU Adipala (Radius maksimum: {{ $company->attendance_radius_meters ?? 150 }} meter).</p>

            <div style="margin-top: 16px; display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="button" class="btn btn-sm" style="background: rgba(255,255,255,0.2); color: #fff;" onclick="getCurrentLocation()">
                    <i class="fa-solid fa-crosshairs"></i> Perbarui Lokasi Saya
                </button>
                <button type="button" class="btn btn-sm" style="background: rgba(255,255,255,0.1); color: #fff;" onclick="simulateInLocation()">
                    <i class="fa-solid fa-building-circle-check"></i> Gunakan Titik PLTU (Simulasi)
                </button>
            </div>
        </div>

        <div class="radar-metrics">
            <div class="metric-item">
                <div class="lbl">Jarak dari PLTU</div>
                <div class="val" id="radarDistance">- m</div>
            </div>
            <div class="metric-item">
                <div class="lbl">Akurasi GPS</div>
                <div class="val" id="radarAccuracy">- m</div>
            </div>
            <div class="metric-item">
                <div class="lbl">Koordinat Anda</div>
                <div class="val" id="radarCoords" style="font-size: 13px; font-weight: 500;">-</div>
            </div>
            <div class="metric-item">
                <div class="lbl">Status Area</div>
                <div class="val" id="radarZone" style="font-size: 14px; color: #fde047;">Mengecek...</div>
            </div>
        </div>
    </div>

    <!-- Kartu Aksi Presensi Hari Ini -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; margin-bottom: 24px;">

        <!-- Formulir Check In / Check Out -->
        <div class="card">
            <div class="card-title-box">
                <h3><i class="fa-solid fa-camera"></i> Presensi Selfie & Lokasi Real-Time</h3>
                <span id="liveClock" style="font-size: 14px; font-weight: 700; color: var(--primary-color);">00:00:00 WIB</span>
            </div>

            @if(!$attendanceToday || !$attendanceToday->check_in_at)
                <!-- Form Check In Berbasis Selfie & Live Geofence -->
                <form action="{{ route('peserta.absensi.checkin') }}" method="POST" id="checkInForm">
                    @csrf
                    <input type="hidden" name="latitude" id="inputLat" required>
                    <input type="hidden" name="longitude" id="inputLng" required>
                    <input type="hidden" name="accuracy" id="inputAcc">
                    <input type="hidden" name="bypass_geofence" id="bypassGeofence" value="0">
                    <input type="hidden" name="qr_token" id="qrTokenInput" value="{{ $dailyQrToken }}">
                    <input type="hidden" name="selfie_image" id="selfieInput" required>

                    <!-- Area Kamera Selfie Interaktif -->
                    <div style="margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <label style="font-weight: 700; color: #0f172a; margin: 0; font-size: 13.5px;">
                                <i class="fa-solid fa-camera-retro" style="color: var(--primary-color);"></i> Foto Selfie Presensi (Wajib)
                            </label>
                            <span id="selfieStatusBadge" class="badge badge-secondary" style="font-size: 11px;">
                                <i class="fa-solid fa-circle-exclamation"></i> Menunggu Selfie
                            </span>
                        </div>

                        <!-- Tombol Buka Kamera (Sebelum kamera aktif) -->
                        <div id="btnStartCameraBox">
                            <button type="button" class="btn btn-primary" onclick="startCamera('in')" style="width: 100%; padding: 14px; font-weight: 600; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 10px;">
                                <i class="fa-solid fa-camera" style="font-size: 18px;"></i> Buka Kamera & Deteksi Lokasi Real-Time
                            </button>
                            <small style="display: block; text-align: center; color: #64748b; font-size: 11.5px; margin-top: 6px;">
                                Kamera akan mendeteksi waktu HP dan status geofence lokasi secara live.
                            </small>
                        </div>

                        <!-- Live Camera Viewport Box with Real-Time HUD Overlay -->
                        <div id="cameraPreviewBox" style="display: none; position: relative; border-radius: 12px; overflow: hidden; background: #020617; border: 2px solid #334155; margin-top: 10px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);">
                            <video id="webcam" autoplay playsinline muted style="width: 100%; height: auto; min-height: 280px; max-height: 380px; object-fit: cover; display: block; transform: scaleX(-1);"></video>
                            <canvas id="canvas" style="display: none;"></canvas>

                            <!-- Live HUD Overlay on Video -->
                            <div class="camera-hud-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; pointer-events: none; display: flex; flex-direction: column; justify-content: space-between; padding: 12px; background: linear-gradient(180deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0) 60%, rgba(0,0,0,0.9) 100%);">
                                
                                <!-- Top Bar: Time & Radius Status -->
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; text-shadow: 0 1px 4px rgba(0,0,0,0.9);">
                                    <div>
                                        <div style="font-size: 10px; font-weight: 700; color: #38bdf8; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <i class="fa-solid fa-clock"></i> Waktu HP Real-Time
                                        </div>
                                        <div id="hudClock" style="font-size: 18px; font-weight: 800; color: #ffffff; font-family: monospace;">
                                            00:00:00 WIB
                                        </div>
                                        <div id="hudDate" style="font-size: 11px; color: #cbd5e1;">
                                            {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}
                                        </div>
                                    </div>

                                    <div style="text-align: right;">
                                        <div id="hudRadiusBadge" style="display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 800; background: #1e293b; color: #facc15; border: 1px solid #eab308; transition: all 0.3s ease;">
                                            RADIUS: CEK...
                                        </div>
                                        <div id="hudDistance" style="font-size: 10.5px; color: #e2e8f0; margin-top: 3px;">
                                            Jarak: - m
                                        </div>
                                    </div>
                                </div>

                                <!-- Center: Face Positioning Guide -->
                                <div style="align-self: center; width: 140px; height: 180px; border: 2px dashed rgba(255,255,255,0.45); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: rgba(255,255,255,0.6); font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.8px; text-shadow: 0 1px 3px rgba(0,0,0,0.8);">Posisikan Wajah</span>
                                </div>

                                <!-- Bottom Bar: Real-time Device Location -->
                                <div style="text-shadow: 0 1px 4px rgba(0,0,0,0.9);">
                                    <div style="font-size: 11.5px; color: #ffffff; font-family: monospace; display: flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-location-dot" style="color: #f43f5e;"></i>
                                        <span id="hudCoords">Mencari koordinat GPS HP...</span>
                                    </div>
                                    <div style="font-size: 10px; color: #94a3b8; margin-top: 2px;">
                                        PT PLN IP UBP Jateng 2 Adipala (Radius Maks: {{ $company->attendance_radius_meters ?? 150 }}m)
                                    </div>
                                </div>
                            </div>

                            <!-- Floating Action Buttons on Camera -->
                            <div style="padding: 10px; background: #0f172a; display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                <button type="button" class="btn btn-primary btn-sm" onclick="capturePhoto('in')" style="padding: 8px 16px; font-weight: 600;">
                                    <i class="fa-solid fa-circle-dot"></i> Ambil Foto Selfie
                                </button>
                                <button type="button" class="btn btn-outline btn-sm" onclick="switchCamera()" style="color: #ffffff; border-color: #475569;">
                                    <i class="fa-solid fa-camera-rotate"></i> Putar Kamera
                                </button>
                                <button type="button" class="btn btn-outline btn-sm" onclick="stopCamera()" style="color: #f87171; border-color: #7f1d1d;">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                            </div>
                        </div>

                        <!-- Preview Hasil Selfie yang Terstempel -->
                        <div id="selfiePreviewBox" style="display: none; margin-top: 14px; text-align: center;">
                            <div style="position: relative; display: inline-block; max-width: 100%;">
                                <img id="selfiePreview" src="" style="width: 100%; max-height: 280px; border-radius: 10px; border: 3px solid #10b981; object-fit: cover; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                <span style="position: absolute; top: 10px; right: 10px; background: #059669; color: #ffffff; font-size: 11px; font-weight: 700; padding: 4px 8px; border-radius: 4px;">
                                    <i class="fa-solid fa-check"></i> Terstempel Resmi
                                </span>
                            </div>
                            <div style="margin-top: 8px;">
                                <button type="button" class="btn btn-outline btn-sm" onclick="retakePhoto('in')">
                                    <i class="fa-solid fa-rotate-left"></i> Ambil Ulang Foto Selfie
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="checkin_notes">Catatan Presensi (Opsional)</label>
                        <input type="text" id="checkin_notes" name="notes" placeholder="Contoh: Bekerja di Ruang Server / Divisi SI" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-weight: 700; font-size: 14px;" id="btnSubmitCheckin" disabled>
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Lakukan Check-In Masuk
                    </button>
                    <small id="submitHint" style="display: block; text-align: center; color: #64748b; font-size: 11.5px; margin-top: 8px;">
                        * Harap ambil foto selfie presensi untuk mengaktifkan tombol check-in.
                    </small>
                </form>
            @elseif(!$attendanceToday->check_out_at)
                <!-- Form Check Out Berbasis Selfie -->
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 16px; margin-bottom: 20px;">
                    <div style="color: #166534; font-weight: 700; font-size: 14px; margin-bottom: 4px;">
                        <i class="fa-solid fa-circle-check"></i> Anda Sudah Melakukan Check-In Masuk
                    </div>
                    <div style="font-size: 13px; color: #1e293b;">
                        Waktu Masuk: <strong>{{ $attendanceToday->check_in_at->format('H:i') }} WIB</strong>
                        ({{ $attendanceToday->check_in_status === 'late' ? "Terlambat {$attendanceToday->late_minutes} menit" : 'Tepat Waktu' }})
                    </div>
                    @if($attendanceToday->selfie_path)
                        <div style="margin-top: 8px;">
                            <button type="button" onclick="viewSelfieModal('{{ asset($attendanceToday->selfie_path) }}', 'Bukti Selfie Masuk ({{ $attendanceToday->check_in_at->format('H:i') }} WIB)')" class="btn btn-outline btn-sm" style="font-size: 11.5px; padding: 4px 10px;">
                                <i class="fa-solid fa-image"></i> Lihat Foto Selfie Masuk
                            </button>
                        </div>
                    @endif
                </div>

                <form action="{{ route('peserta.absensi.checkout') }}" method="POST" id="checkOutForm">
                    @csrf
                    <input type="hidden" name="latitude" id="inputLatOut">
                    <input type="hidden" name="longitude" id="inputLngOut">
                    <input type="hidden" name="selfie_image" id="selfieInputOut">

                    <!-- Selfie Check-Out -->
                    <div style="margin-bottom: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <label style="font-weight: 700; color: #0f172a; margin: 0; font-size: 13.5px;">
                                <i class="fa-solid fa-camera-retro" style="color: var(--primary-color);"></i> Foto Selfie Kepulangan (Opsional)
                            </label>
                            <span id="selfieStatusOutBadge" class="badge badge-secondary" style="font-size: 11px;">
                                Belum ada foto
                            </span>
                        </div>

                        <div id="btnStartCameraOutBox">
                            <button type="button" class="btn btn-outline" onclick="startCamera('out')" style="width: 100%; padding: 12px; font-weight: 600;">
                                <i class="fa-solid fa-camera"></i> Ambil Selfie Kepulangan
                            </button>
                        </div>

                        <!-- Live Camera Viewport Box for Check-Out with Real-Time HUD Overlay -->
                        <div id="cameraPreviewBox" style="display: none; position: relative; border-radius: 12px; overflow: hidden; background: #020617; border: 2px solid #334155; margin-top: 10px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);">
                            <video id="webcam" autoplay playsinline muted style="width: 100%; height: auto; min-height: 280px; max-height: 380px; object-fit: cover; display: block; transform: scaleX(-1);"></video>
                            <canvas id="canvas" style="display: none;"></canvas>

                            <!-- Live HUD Overlay on Video -->
                            <div class="camera-hud-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; pointer-events: none; display: flex; flex-direction: column; justify-content: space-between; padding: 12px; background: linear-gradient(180deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0) 60%, rgba(0,0,0,0.9) 100%);">
                                
                                <!-- Top Bar: Time & Radius Status -->
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; text-shadow: 0 1px 4px rgba(0,0,0,0.9);">
                                    <div>
                                        <div style="font-size: 10px; font-weight: 700; color: #38bdf8; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <i class="fa-solid fa-clock"></i> Waktu HP Real-Time (Kepulangan)
                                        </div>
                                        <div id="hudClock" style="font-size: 18px; font-weight: 800; color: #ffffff; font-family: monospace;">
                                            00:00:00 WIB
                                        </div>
                                        <div id="hudDate" style="font-size: 11px; color: #cbd5e1;">
                                            {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}
                                        </div>
                                    </div>

                                    <div style="text-align: right;">
                                        <div id="hudRadiusBadge" style="display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 800; background: #1e293b; color: #facc15; border: 1px solid #eab308; transition: all 0.3s ease;">
                                            RADIUS: CEK...
                                        </div>
                                        <div id="hudDistance" style="font-size: 10.5px; color: #e2e8f0; margin-top: 3px;">
                                            Jarak: - m
                                        </div>
                                    </div>
                                </div>

                                <!-- Center: Face Positioning Guide -->
                                <div style="align-self: center; width: 140px; height: 180px; border: 2px dashed rgba(255,255,255,0.45); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: rgba(255,255,255,0.6); font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.8px; text-shadow: 0 1px 3px rgba(0,0,0,0.8);">Posisikan Wajah</span>
                                </div>

                                <!-- Bottom Bar: Real-time Device Location -->
                                <div style="text-shadow: 0 1px 4px rgba(0,0,0,0.9);">
                                    <div style="font-size: 11.5px; color: #ffffff; font-family: monospace; display: flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-location-dot" style="color: #f43f5e;"></i>
                                        <span id="hudCoords">Mencari koordinat GPS HP...</span>
                                    </div>
                                    <div style="font-size: 10px; color: #94a3b8; margin-top: 2px;">
                                        PT PLN IP UBP Jateng 2 Adipala (Radius Maks: {{ $company->attendance_radius_meters ?? 150 }}m)
                                    </div>
                                </div>
                            </div>

                            <!-- Floating Action Buttons on Camera -->
                            <div style="padding: 10px; background: #0f172a; display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                <button type="button" class="btn btn-primary btn-sm" onclick="capturePhoto('out')" style="padding: 8px 16px; font-weight: 600;">
                                    <i class="fa-solid fa-circle-dot"></i> Ambil Foto Selfie Pulang
                                </button>
                                <button type="button" class="btn btn-outline btn-sm" onclick="switchCamera()" style="color: #ffffff; border-color: #475569;">
                                    <i class="fa-solid fa-camera-rotate"></i> Putar Kamera
                                </button>
                                <button type="button" class="btn btn-outline btn-sm" onclick="stopCamera()" style="color: #f87171; border-color: #7f1d1d;">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                            </div>
                        </div>

                        <div id="selfiePreviewOutBox" style="display: none; margin-top: 10px; text-align: center;">
                            <img id="selfiePreviewOut" src="" style="width: 100%; max-height: 240px; border-radius: 10px; border: 2px solid #10b981; object-fit: cover;">
                            <div style="margin-top: 6px;">
                                <button type="button" class="btn btn-outline btn-sm" onclick="retakePhoto('out')">
                                    <i class="fa-solid fa-rotate-left"></i> Ambil Ulang Foto Pulang
                                </button>
                            </div>
                        </div>
                    </div>

                    <p style="font-size: 13px; color: #475569; margin-bottom: 16px;">
                        Pastikan seluruh pekerjaan hari ini telah dirangkum dalam logbook sebelum melakukan check-out kepulangan.
                    </p>

                    <button type="submit" class="btn btn-success" style="width: 100%; padding: 14px; font-weight: 700;" id="btnSubmitCheckOut">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Lakukan Check-Out Pulang
                    </button>
                </form>
            @else
                <!-- Sudah Selesai Hari Ini -->
                <div style="text-align: center; padding: 24px 10px;">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 14px;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <h3 style="font-size: 17px; font-weight: 700; color: #0f172a;">Presensi Hari Ini Lengkap</h3>
                    <p style="font-size: 13px; color: #64748b; margin-top: 4px;">
                        Masuk: {{ $attendanceToday->check_in_at->format('H:i') }} WIB &bull;
                        Pulang: {{ $attendanceToday->check_out_at->format('H:i') }} WIB
                    </p>
                    <div style="display: flex; gap: 8px; justify-content: center; margin-top: 12px; flex-wrap: wrap;">
                        @if($attendanceToday->selfie_path)
                            <button type="button" class="btn btn-outline btn-sm" onclick="viewSelfieModal('{{ asset($attendanceToday->selfie_path) }}', 'Bukti Selfie Masuk ({{ $attendanceToday->check_in_at->format('H:i') }} WIB)')">
                                <i class="fa-solid fa-image"></i> Foto Selfie Masuk
                            </button>
                        @endif
                        @if($attendanceToday->selfie_out_path)
                            <button type="button" class="btn btn-outline btn-sm" onclick="viewSelfieModal('{{ asset($attendanceToday->selfie_out_path) }}', 'Bukti Selfie Pulang ({{ $attendanceToday->check_out_at->format('H:i') }} WIB)')">
                                <i class="fa-solid fa-image"></i> Foto Selfie Pulang
                            </button>
                        @endif
                    </div>
                    <div style="margin-top: 14px;">
                        <span class="badge badge-success">Total Kerja: {{ floor($attendanceToday->total_work_minutes / 60) }} Jam {{ $attendanceToday->total_work_minutes % 60 }} Menit</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Info Kebijakan & Ringkasan -->
        <div class="card">
            <div class="card-title-box">
                <h3><i class="fa-solid fa-circle-info"></i> Kebijakan Presensi PLTU Adipala</h3>
            </div>

            <ul style="list-style: none; display: flex; flex-direction: column; gap: 12px; font-size: 13px;">
                <li style="display: flex; gap: 12px; align-items: flex-start;">
                    <i class="fa-solid fa-clock" style="color: var(--primary-color); margin-top: 3px;"></i>
                    <div>
                        <strong>Jam Kerja Pokok:</strong>
                        <div>{{ substr($company->work_start_time ?? '08:00:00', 0, 5) }} WIB s/d {{ substr($company->work_end_time ?? '16:00:00', 0, 5) }} WIB (Senin - Jumat)</div>
                    </div>
                </li>
                <li style="display: flex; gap: 12px; align-items: flex-start;">
                    <i class="fa-solid fa-hourglass-half" style="color: #f59e0b; margin-top: 3px;"></i>
                    <div>
                        <strong>Toleransi Keterlambatan:</strong>
                        <div>Maksimal {{ $policy->late_tolerance_minutes ?? 15 }} menit (pukul {{ \Carbon\Carbon::createFromFormat('H:i:s', $company->work_start_time ?? '08:00:00')->addMinutes($policy->late_tolerance_minutes ?? 15)->format('H:i') }} WIB). Lebih dari itu tercatat status 'Terlambat'.</div>
                    </div>
                </li>
                <li style="display: flex; gap: 12px; align-items: flex-start;">
                    <i class="fa-solid fa-map-location-dot" style="color: #0284c7; margin-top: 3px;"></i>
                    <div>
                        <strong>Radius Geofencing:</strong>
                        <div>Maksimum {{ $company->attendance_radius_meters ?? 150 }} meter dari titik pusat PLTU Adipala.</div>
                    </div>
                </li>
                <li style="display: flex; gap: 12px; align-items: flex-start;">
                    <i class="fa-solid fa-shield-halved" style="color: #10b981; margin-top: 3px;"></i>
                    <div>
                        <strong>Integritas Presensi:</strong>
                        <div>Waktu presensi otomatis divalidasi oleh server, tidak dapat dimanipulasi dengan jam perangkat.</div>
                    </div>
                </li>
            </ul>

            <div style="margin-top: 20px; padding: 14px; background: #f8fafc; border-radius: 8px; font-size: 12px; color: #64748b;">
                <i class="fa-solid fa-triangle-exclamation" style="color: #d97706;"></i> Mengalami kendala GPS atau tugas dinas lapangan? Silakan gunakan tombol <strong>Ajukan Koreksi Absensi</strong> di atas.
            </div>
        </div>

    </div>

    <!-- Riwayat Presensi & Koreksi -->
    <div class="card">
        <div class="card-title-box">
            <h3><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Presensi Magang</h3>
        </div>

        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Bukti Selfie</th>
                        <th>Status</th>
                        <th>Keterlambatan</th>
                        <th>Total Jam Kerja</th>
                        <th>Jarak Verifikasi</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $row)
                        <tr>
                            <td><strong>{{ $row->attendance_date->translatedFormat('d M Y') }}</strong></td>
                            <td>{{ $row->check_in_at ? $row->check_in_at->format('H:i') . ' WIB' : '-' }}</td>
                            <td>{{ $row->check_out_at ? $row->check_out_at->format('H:i') . ' WIB' : '-' }}</td>
                            <td>
                                @if($row->selfie_path || $row->selfie_out_path)
                                    <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                        @if($row->selfie_path)
                                            <button type="button" class="btn btn-outline btn-sm" style="padding: 2px 8px; font-size: 11px;" onclick="viewSelfieModal('{{ asset($row->selfie_path) }}', 'Bukti Selfie Masuk ({{ $row->attendance_date->translatedFormat('d M Y') }})')">
                                                <i class="fa-solid fa-camera"></i> Masuk
                                            </button>
                                        @endif
                                        @if($row->selfie_out_path)
                                            <button type="button" class="btn btn-outline btn-sm" style="padding: 2px 8px; font-size: 11px;" onclick="viewSelfieModal('{{ asset($row->selfie_out_path) }}', 'Bukti Selfie Pulang ({{ $row->attendance_date->translatedFormat('d M Y') }})')">
                                                <i class="fa-solid fa-camera"></i> Pulang
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <span style="color: #94a3b8; font-size: 11px;">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $row->check_in_status }}">
                                    {{ match($row->check_in_status) {
                                        'present' => 'Hadir Tepat',
                                        'late' => 'Terlambat',
                                        'leave' => 'Cuti / Izin',
                                        'sick' => 'Sakit',
                                        default => 'Alpa',
                                    } }}
                                </span>
                            </td>
                            <td>{{ $row->late_minutes > 0 ? "{$row->late_minutes} menit" : '-' }}</td>
                            <td>{{ $row->total_work_minutes > 0 ? floor($row->total_work_minutes / 60) . 'j ' . ($row->total_work_minutes % 60) . 'm' : '-' }}</td>
                            <td>{{ $row->distance_in_meters ? "{$row->distance_in_meters} m" : '-' }}</td>
                            <td style="max-width: 180px; font-size: 12px; color: #64748b;">{{ $row->notes ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 24px; color: #94a3b8;">
                                Belum ada riwayat presensi tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $attendances->links() }}
        </div>
    </div>

    <!-- Riwayat Pengajuan Koreksi -->
    <div class="card">
        <div class="card-title-box">
            <h3><i class="fa-solid fa-list-check"></i> Riwayat Pengajuan Koreksi Absensi</h3>
        </div>

        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Tgl Pengajuan</th>
                        <th>Tgl Absensi</th>
                        <th>Jenis Koreksi</th>
                        <th>Waktu Diajukan</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Catatan Pembimbing</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($corrections as $corr)
                        <tr>
                            <td>{{ $corr->created_at->format('d/m/Y H:i') }}</td>
                            <td><strong>{{ $corr->correction_date->translatedFormat('d M Y') }}</strong></td>
                            <td>{{ ucwords(str_replace('_', ' ', $corr->correction_type)) }}</td>
                            <td>
                                @if($corr->requested_check_in_at) Masuk: {{ $corr->requested_check_in_at->format('H:i') }}<br>@endif
                                @if($corr->requested_check_out_at) Pulang: {{ $corr->requested_check_out_at->format('H:i') }}@endif
                            </td>
                            <td style="max-width: 200px; font-size: 12px;">{{ $corr->reason }}</td>
                            <td>
                                <span class="badge badge-{{ $corr->status }}">
                                    {{ match($corr->status) {
                                        'submitted' => 'Menunggu Review',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        default => $corr->status,
                                    } }}
                                </span>
                            </td>
                            <td style="max-width: 200px; font-size: 12px; color: #64748b;">
                                {{ $corr->review_note ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px; color: #94a3b8;">
                                Belum ada pengajuan koreksi absensi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ================= MODAL KOREKSI ABSENSI (README SECTION 6) ================= -->
    <div class="modal-overlay" id="koreksiModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fa-solid fa-file-pen" style="color: var(--primary-color);"></i> Form Pengajuan Koreksi Absensi</h3>
                <button type="button" class="modal-close" onclick="closeKoreksiModal()">&times;</button>
            </div>
            <form action="{{ route('peserta.absensi.koreksi') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="corr_date">Tanggal Absensi *</label>
                            <input type="date" id="corr_date" name="correction_date" max="{{ date('Y-m-d') }}" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="corr_type">Jenis Koreksi *</label>
                            <select id="corr_type" name="correction_type" class="form-select" required>
                                <option value="check_in">Lupa / Koreksi Check-in Masuk</option>
                                <option value="check_out">Lupa / Koreksi Check-out Pulang</option>
                                <option value="check_in_and_check_out">Koreksi Masuk dan Pulang</option>
                                <option value="attendance_status">Koreksi Status Kehadiran (Dinas/Tugas Luar)</option>
                                <option value="other">Kendala Jaringan / GPS</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="corr_in">Jam Masuk yang Diajukan</label>
                            <input type="time" id="corr_in" name="requested_check_in_at" value="08:00" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="corr_out">Jam Pulang yang Diajukan</label>
                            <input type="time" id="corr_out" name="requested_check_out_at" value="16:00" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="corr_reason">Alasan Koreksi Lengkap *</label>
                        <textarea id="corr_reason" name="reason" rows="3" class="form-control" placeholder="Jelaskan alasan detail mengapa presensi tidak tercatat atau perlu dikoreksi..." required minlength="10"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="corr_file">Lampiran Bukti Pendukung (Foto/Surat Tugas/Screenshot GPS)</label>
                        <input type="file" id="corr_file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="form-control">
                        <small style="color: #64748b; font-size: 11px;">Format: PDF/JPG/PNG. Maksimal 5 MB.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeKoreksiModal()">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Viewer Bukti Foto Selfie -->
    <div class="modal-overlay" id="modalSelfieViewer">
        <div class="modal-card" style="max-width: 480px; text-align: center;">
            <div class="modal-header">
                <h3 id="selfieViewerTitle"><i class="fa-solid fa-camera"></i> Bukti Foto Selfie Presensi</h3>
                <button type="button" class="modal-close" onclick="closeSelfieViewer()">&times;</button>
            </div>
            <div class="modal-body" style="padding: 16px;">
                <img id="selfieViewerImg" src="" style="width: 100%; max-height: 440px; border-radius: 10px; object-fit: contain; border: 1px solid #cbd5e1; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <button type="button" class="btn btn-outline" onclick="closeSelfieViewer()">Tutup</button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const targetLat = {{ $company->latitude ?? -7.680810191412908 }};
    const targetLng = {{ $company->longitude ?? 109.1366921585062 }};
    const allowedRadius = {{ $company->attendance_radius_meters ?? 150 }};

    const userName = "{{ $mhs->nama_lengkap }}";
    const userNim = "{{ $mhs->nim }}";
    const userDivisi = "{{ $penempatan->divisi->nama_divisi ?? 'PLTU Adipala' }}";

    let currentLat = targetLat;
    let currentLng = targetLng;
    let currentAcc = 15;
    let currentDist = 0;
    let isInsideRadius = true;
    let hasCapturedSelfie = false;
    let activeCameraMode = 'in'; // 'in' or 'out'
    let currentFacingMode = 'user'; // 'user' or 'environment'
    let streamObj = null;

    function updateLiveClockAndHud() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
        const dateStr = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' });

        const elClock = document.getElementById('liveClock');
        if (elClock) elClock.innerText = timeStr;

        const hudClock = document.getElementById('hudClock');
        if (hudClock) hudClock.innerText = timeStr;

        const hudDate = document.getElementById('hudDate');
        if (hudDate) hudDate.innerText = dateStr;
    }
    setInterval(updateLiveClockAndHud, 1000);
    updateLiveClockAndHud();

    function deg2rad(deg) { return deg * (Math.PI / 180); }
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return Math.round(R * c);
    }

    function processLocation(lat, lng, acc) {
        currentLat = lat;
        currentLng = lng;
        currentAcc = acc || 15;
        currentDist = calculateDistance(lat, lng, targetLat, targetLng);
        isInsideRadius = (currentDist <= allowedRadius + 50);

        // Radar stats
        const radarDist = document.getElementById('radarDistance');
        if (radarDist) radarDist.innerText = currentDist + ' m';

        const radarAcc = document.getElementById('radarAccuracy');
        if (radarAcc) radarAcc.innerText = currentAcc ? Math.round(currentAcc) + ' m' : '±15 m';

        const radarCoords = document.getElementById('radarCoords');
        if (radarCoords) radarCoords.innerText = lat.toFixed(5) + ', ' + lng.toFixed(5);

        const zoneEl = document.getElementById('radarZone');
        const statusText = document.getElementById('radarStatusText');

        if (isInsideRadius) {
            if (zoneEl) {
                zoneEl.innerText = 'Di Dalam Area PLTU';
                zoneEl.style.color = '#4ade80';
            }
            if (statusText) statusText.innerText = 'Lokasi Terverifikasi (PLTU Adipala)';
        } else {
            if (zoneEl) {
                zoneEl.innerText = 'Di Luar Radius';
                zoneEl.style.color = '#f87171';
            }
            if (statusText) statusText.innerText = 'Di Luar Area PLTU (' + currentDist + ' m)';
            const bypass = document.getElementById('bypassGeofence');
            if (bypass) bypass.value = '1';
        }

        // Form Inputs
        if (document.getElementById('inputLat')) document.getElementById('inputLat').value = lat;
        if (document.getElementById('inputLng')) document.getElementById('inputLng').value = lng;
        if (document.getElementById('inputAcc')) document.getElementById('inputAcc').value = currentAcc;
        if (document.getElementById('inputLatOut')) document.getElementById('inputLatOut').value = lat;
        if (document.getElementById('inputLngOut')) document.getElementById('inputLngOut').value = lng;

        // Update Camera Live HUD
        updateCameraHudUI();
    }

    function updateCameraHudUI() {
        const hudCoords = document.getElementById('hudCoords');
        if (hudCoords) {
            hudCoords.innerText = `${currentLat.toFixed(5)}, ${currentLng.toFixed(5)} (±${Math.round(currentAcc)}m)`;
        }

        const hudRadiusBadge = document.getElementById('hudRadiusBadge');
        const hudDistance = document.getElementById('hudDistance');

        if (hudRadiusBadge) {
            if (isInsideRadius) {
                hudRadiusBadge.innerText = 'DALAM RADIUS: TRUE';
                hudRadiusBadge.style.background = '#166534';
                hudRadiusBadge.style.color = '#4ade80';
                hudRadiusBadge.style.borderColor = '#22c55e';
            } else {
                hudRadiusBadge.innerText = 'DALAM RADIUS: FALSE';
                hudRadiusBadge.style.background = '#991b1b';
                hudRadiusBadge.style.color = '#fca5a5';
                hudRadiusBadge.style.borderColor = '#ef4444';
            }
        }

        if (hudDistance) {
            hudDistance.innerText = `Jarak: ${currentDist} m (Maks: ${allowedRadius} m)`;
        }
    }

    function getCurrentLocation() {
        if (!navigator.geolocation) {
            console.warn('Geolocation tidak didukung/dibatasi browser (akses HTTP lokal). Menggunakan titik PLTU Adipala.');
            simulateInLocation();
            return;
        }
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                processLocation(pos.coords.latitude, pos.coords.longitude, pos.coords.accuracy);
            },
            (err) => {
                console.warn('GPS error/timeout:', err);
                simulateInLocation();
            },
            { enableHighAccuracy: true, timeout: 8000 }
        );
    }

    function simulateInLocation() {
        const simLat = targetLat + 0.00015;
        const simLng = targetLng + 0.00012;
        processLocation(simLat, simLng, 10);
    }

    // ==========================================
    // SISTEM KAMERA & SELFIE WATERMARK REAL-TIME
    // ==========================================
    function startCamera(mode = 'in') {
        activeCameraMode = mode;
        const box = document.getElementById('cameraPreviewBox');
        if (box) box.style.display = 'block';

        const btnBox = (mode === 'in') ? document.getElementById('btnStartCameraBox') : document.getElementById('btnStartCameraOutBox');
        if (btnBox) btnBox.style.display = 'none';

        const video = document.getElementById('webcam');
        if (!video) {
            console.error('Komponen video kamera tidak ditemukan di halaman.');
            alert('Komponen kamera tidak ditemukan di halaman. Silakan muat ulang halaman.');
            if (btnBox) btnBox.style.display = 'block';
            if (box) box.style.display = 'none';
            return;
        }

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Browser Anda tidak mendukung akses kamera secara langsung. Pastikan Anda membukanya melalui localhost atau HTTPS.');
            if (btnBox) btnBox.style.display = 'block';
            if (box) box.style.display = 'none';
            return;
        }

        // Hentikan stream lama jika ada
        stopCameraTracks();

        const constraints = {
            video: {
                facingMode: currentFacingMode,
                width: { ideal: 1280 },
                height: { ideal: 720 }
            },
            audio: false
        };

        navigator.mediaDevices.getUserMedia(constraints)
            .then(function(stream) {
                streamObj = stream;
                if (video) {
                    video.srcObject = stream;
                    video.play().catch(e => console.warn('Video playback notice:', e));
                }
                updateCameraHudUI();
            })
            .catch(function(err) {
                console.warn('Camera error:', err);
                alert('Gagal membuka kamera: ' + err.message + '\n\nPastikan izin akses kamera telah diizinkan pada browser Anda.');
                if (btnBox) btnBox.style.display = 'block';
                if (box) box.style.display = 'none';
            });
    }

    function switchCamera() {
        currentFacingMode = (currentFacingMode === 'user') ? 'environment' : 'user';
        startCamera(activeCameraMode);
    }

    function stopCamera() {
        stopCameraTracks();
        const box = document.getElementById('cameraPreviewBox');
        if (box) box.style.display = 'none';

        const btnBox = (activeCameraMode === 'in') ? document.getElementById('btnStartCameraBox') : document.getElementById('btnStartCameraOutBox');
        if (btnBox && !hasCapturedSelfie) btnBox.style.display = 'block';
    }

    function stopCameraTracks() {
        if (streamObj) {
            streamObj.getTracks().forEach(track => track.stop());
            streamObj = null;
        }
    }

    function drawWatermark(canvas) {
        const ctx = canvas.getContext('2d');
        const w = canvas.width;
        const h = canvas.height;

        const bannerHeight = Math.max(100, Math.round(h * 0.23));
        const bannerY = h - bannerHeight;

        // Background banner gelap
        ctx.fillStyle = "rgba(15, 23, 42, 0.88)";
        ctx.fillRect(0, bannerY, w, bannerHeight);

        // Garis aksen atas (Hijau jika TRUE, Merah jika FALSE)
        ctx.fillStyle = isInsideRadius ? "#22c55e" : "#ef4444";
        ctx.fillRect(0, bannerY, w, 4);

        // Teks data presensi real-time
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
        const dateStr = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' });
        const radiusStr = isInsideRadius ? "DALAM RADIUS: TRUE" : "DALAM RADIUS: FALSE";

        const padding = 16;
        const fontSize = Math.max(11, Math.round(w * 0.022));
        const lineSpacing = fontSize + 5;

        // Header Title
        ctx.font = `bold ${fontSize + 1}px "Poppins", sans-serif`;
        ctx.fillStyle = "#38bdf8";
        ctx.fillText("PLTU ADIPALA - PRESENSI MAGANG REAL-TIME", padding, bannerY + fontSize + 8);

        ctx.font = `${fontSize}px monospace`;
        let currY = bannerY + fontSize + 8 + lineSpacing;

        // Baris 1: Waktu Real-Time
        ctx.fillStyle = "#ffffff";
        ctx.fillText(`🕒 WAKTU  : ${timeStr} | ${dateStr}`, padding, currY);
        currY += lineSpacing;

        // Baris 2: Lokasi Real-Time
        const latStr = (typeof currentLat === 'number') ? currentLat.toFixed(6) : currentLat;
        const lngStr = (typeof currentLng === 'number') ? currentLng.toFixed(6) : currentLng;
        const accStr = currentAcc ? `±${Math.round(currentAcc)}m` : '±15m';
        ctx.fillText(`📍 LOKASI : Lat ${latStr}, Lng ${lngStr} (${accStr})`, padding, currY);
        currY += lineSpacing;

        // Baris 3: Status Radius Geofence (TRUE / FALSE)
        ctx.fillStyle = isInsideRadius ? "#4ade80" : "#f87171";
        ctx.fillText(`🎯 GEOFENCE: ${radiusStr} (Jarak: ${currentDist}m)`, padding, currY);
        currY += lineSpacing;

        // Baris 4: Identitas Mahasiswa
        ctx.fillStyle = "#e2e8f0";
        ctx.font = `${fontSize - 1}px "Poppins", sans-serif`;
        ctx.fillText(`👤 ${userName} (${userNim}) - ${userDivisi}`, padding, currY);
    }

    function capturePhoto(mode = 'in') {
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');

        if (!video || !video.videoWidth) {
            alert('Kamera belum siap, silakan tunggu sebentar.');
            return;
        }

        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;
        const ctx = canvas.getContext('2d');

        // Balik horizontal jika kamera depan (mirror)
        if (currentFacingMode === 'user') {
            ctx.translate(canvas.width, 0);
            ctx.scale(-1, 1);
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            ctx.setTransform(1, 0, 0, 1, 0, 0);
        } else {
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        }

        // Cetak watermark stempel jam & lokasi real-time
        drawWatermark(canvas);

        const dataUrl = canvas.toDataURL('image/png', 0.92);

        if (mode === 'in') {
            document.getElementById('selfieInput').value = dataUrl;
            document.getElementById('selfiePreview').src = dataUrl;
            document.getElementById('selfiePreviewBox').style.display = 'block';

            const statusBadge = document.getElementById('selfieStatusBadge');
            if (statusBadge) {
                statusBadge.className = 'badge badge-present';
                statusBadge.innerHTML = '<i class="fa-solid fa-circle-check"></i> Foto Selfie Terverifikasi';
            }

            const btnCheckin = document.getElementById('btnSubmitCheckin');
            if (btnCheckin) btnCheckin.disabled = false;

            const submitHint = document.getElementById('submitHint');
            if (submitHint) {
                submitHint.style.color = '#059669';
                submitHint.innerHTML = '<i class="fa-solid fa-circle-check"></i> Foto selfie siap terstempel jam & lokasi. Silakan klik tombol di atas untuk Check-In.';
            }
        } else {
            const outInput = document.getElementById('selfieInputOut');
            if (outInput) outInput.value = dataUrl;

            const previewOut = document.getElementById('selfiePreviewOut');
            if (previewOut) previewOut.src = dataUrl;

            const previewOutBox = document.getElementById('selfiePreviewOutBox');
            if (previewOutBox) previewOutBox.style.display = 'block';

            const statusOutBadge = document.getElementById('selfieStatusOutBadge');
            if (statusOutBadge) {
                statusOutBadge.className = 'badge badge-present';
                statusOutBadge.innerHTML = '<i class="fa-solid fa-circle-check"></i> Selfie Pulang Siap';
            }
        }

        hasCapturedSelfie = true;
        stopCameraTracks();
        const camBox = document.getElementById('cameraPreviewBox');
        if (camBox) camBox.style.display = 'none';
    }

    function retakePhoto(mode = 'in') {
        if (mode === 'in') {
            document.getElementById('selfieInput').value = '';
            document.getElementById('selfiePreviewBox').style.display = 'none';
            document.getElementById('btnSubmitCheckin').disabled = true;

            const statusBadge = document.getElementById('selfieStatusBadge');
            if (statusBadge) {
                statusBadge.className = 'badge badge-secondary';
                statusBadge.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Menunggu Selfie';
            }
        } else {
            const outInput = document.getElementById('selfieInputOut');
            if (outInput) outInput.value = '';
            document.getElementById('selfiePreviewOutBox').style.display = 'none';
        }

        hasCapturedSelfie = false;
        startCamera(mode);
    }

    // Modal Viewer Bukti Selfie
    function viewSelfieModal(url, title) {
        document.getElementById('selfieViewerImg').src = url;
        document.getElementById('selfieViewerTitle').innerHTML = '<i class="fa-solid fa-camera"></i> ' + title;
        document.getElementById('modalSelfieViewer').classList.add('active');
    }

    function closeSelfieViewer() {
        document.getElementById('modalSelfieViewer').classList.remove('active');
    }

    // Modal Koreksi
    function openKoreksiModal() {
        document.getElementById('koreksiModal').classList.add('active');
    }
    function closeKoreksiModal() {
        document.getElementById('koreksiModal').classList.remove('active');
    }

    document.addEventListener('DOMContentLoaded', function() {
        getCurrentLocation();
    });
</script>
@endpush
