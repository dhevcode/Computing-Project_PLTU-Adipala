<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\CompanyInformation;
use App\Models\Company;
use App\Models\AttendancePolicy;
use App\Models\AppNotification;
use App\Models\User;

class AdminPengumumanController extends Controller
{
    public function index()
    {
        $informations = CompanyInformation::orderBy('created_at', 'desc')->paginate(15);
        $company = Company::first();
        $policy = AttendancePolicy::first();

        return view('admin.pengumuman.index', compact('informations', 'company', 'policy'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'category' => 'required|in:general,work_rule,safety,announcement,contact,document',
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,docx,jpg,jpeg,png|max:10240',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = 'info_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/info'), $fileName);
            $attachmentPath = 'uploads/info/' . $fileName;
        }

        CompanyInformation::create([
            'title' => $request->title,
            'category' => $request->category,
            'content' => $request->content,
            'attachment_path' => $attachmentPath,
            'is_published' => true,
            'published_at' => Carbon::now(),
            'created_by' => Auth::id(),
        ]);

        // Kirim notifikasi broadcast ke seluruh akun Peserta jika kategori Pengumuman
        if ($request->category === 'announcement') {
            $pesertaUsers = User::where('role', 'Peserta')->get();
            foreach ($pesertaUsers as $peserta) {
                AppNotification::send(
                    $peserta->id_users,
                    'Pengumuman Baru: ' . $request->title,
                    substr(strip_tags($request->content), 0, 150) . '...',
                    'info',
                    route('peserta.informasi')
                );
            }
        }

        return back()->with('success', 'Informasi / Pengumuman berhasil disimpan.');
    }

    public function updateCompany(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'industry' => 'required|string|max:100',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'attendance_radius_meters' => 'required|integer|min:10',
            'work_start_time' => 'required',
            'work_end_time' => 'required',
        ]);

        $company = Company::first();
        if ($company) {
            $company->update($request->only([
                'name', 'industry', 'address', 'latitude', 'longitude',
                'attendance_radius_meters', 'work_start_time', 'work_end_time',
                'phone', 'email', 'website', 'description'
            ]));

            // Sinkronisasi otomatis ke kebijakan presensi (AttendancePolicy)
            $policy = AttendancePolicy::firstOrCreate(['company_id' => $company->id_company]);
            $tolerance = $policy->late_tolerance_minutes ?: 15;
            $startParsed = Carbon::createFromTimeString($company->work_start_time);
            $deadline = $startParsed->copy()->addMinutes($tolerance)->format('H:i:s');
            $startEarly = $startParsed->copy()->subHours(2)->format('H:i:s');

            $policy->update([
                'check_in_start' => $startEarly,
                'check_in_deadline' => $deadline,
                'check_out_start' => $company->work_end_time,
                'allowed_radius_meters' => $company->attendance_radius_meters,
            ]);
        }

        return back()->with('success', 'Data profil perusahaan dan radius absensi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $info = CompanyInformation::findOrFail($id);
        $info->delete();

        return back()->with('success', 'Informasi berhasil dihapus.');
    }
}
