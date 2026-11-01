<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatbotFaq;
use App\Models\ChatbotLog;
use Illuminate\Support\Facades\DB;

class AdminChatbotController extends Controller
{
    /**
     * Tampilkan halaman pengelolaan Chatbot FAQ & Log Riwayat Percakapan.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'faq');

        // Statistik
        $totalFaq = ChatbotFaq::count();
        $totalFaqAktif = ChatbotFaq::where('is_active', true)->count();
        $totalInteraksi = ChatbotLog::count();
        $interaksiGuest = ChatbotLog::where('user_type', 'guest')->count();
        $interaksiStudent = ChatbotLog::where('user_type', 'student')->count();

        // FAQ Terpopuler (Top 5 berdasarkan click_count)
        $topFaqs = ChatbotFaq::orderByDesc('click_count')->take(5)->get();

        // Query Daftar FAQ
        $faqQuery = ChatbotFaq::query();
        if ($request->filled('search_faq')) {
            $search = $request->search_faq;
            $faqQuery->where(function($q) use ($search) {
                $q->where('pertanyaan', 'like', "%{$search}%")
                  ->orWhere('jawaban', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
            });
        }
        if ($request->filled('role_access') && $request->role_access !== 'all_filter') {
            $faqQuery->where('role_access', $request->role_access);
        }
        if ($request->filled('status_filter')) {
            $faqQuery->where('is_active', $request->status_filter === 'active');
        }
        $faqs = $faqQuery->orderBy('urutan', 'asc')->orderBy('id_faq', 'asc')->paginate(15, ['*'], 'faq_page')->withQueryString();

        // Query Log Riwayat Percakapan
        $logQuery = ChatbotLog::with(['faq', 'user']);
        if ($request->filled('search_log')) {
            $search = $request->search_log;
            $logQuery->where(function($q) use ($search) {
                $q->where('pertanyaan', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('nama_lengkap', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('user_type_filter')) {
            $logQuery->where('user_type', $request->user_type_filter);
        }
        $logs = $logQuery->orderByDesc('id_log')->paginate(15, ['*'], 'log_page')->withQueryString();

        return view('admin.chatbot.index', compact(
            'tab',
            'faqs',
            'logs',
            'totalFaq',
            'totalFaqAktif',
            'totalInteraksi',
            'interaksiGuest',
            'interaksiStudent',
            'topFaqs'
        ));
    }

    /**
     * Tambah FAQ baru ke dalam sistem.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode'        => 'required|string|max:20|unique:chatbot_faqs,kode',
            'pertanyaan'  => 'required|string|max:255',
            'jawaban'     => 'required|string',
            'kategori'    => 'nullable|string|max:50',
            'role_access' => 'required|in:all,guest,student',
            'urutan'      => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ], [
            'kode.required'       => 'Kode FAQ wajib diisi (contoh: FAQ-11).',
            'kode.unique'         => 'Kode FAQ tersebut sudah digunakan.',
            'pertanyaan.required' => 'Pertanyaan FAQ wajib diisi.',
            'jawaban.required'    => 'Isi jawaban FAQ wajib diisi.',
            'role_access.required'=> 'Pilih hak akses role untuk pertanyaan ini.',
        ]);

        ChatbotFaq::create([
            'kode'        => strtoupper(trim($request->kode)),
            'pertanyaan'  => trim($request->pertanyaan),
            'jawaban'     => trim($request->jawaban),
            'kategori'    => trim($request->kategori ?: 'Umum'),
            'role_access' => $request->role_access,
            'urutan'      => $request->urutan ?: (ChatbotFaq::max('urutan') + 1),
            'is_active'   => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('admin.chatbot.index', ['tab' => 'faq'])
            ->with('success', 'FAQ baru berhasil ditambahkan ke database!');
    }

    /**
     * Update data FAQ yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $faq = ChatbotFaq::findOrFail($id);

        $request->validate([
            'kode'        => 'required|string|max:20|unique:chatbot_faqs,kode,' . $faq->id_faq . ',id_faq',
            'pertanyaan'  => 'required|string|max:255',
            'jawaban'     => 'required|string',
            'kategori'    => 'nullable|string|max:50',
            'role_access' => 'required|in:all,guest,student',
            'urutan'      => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ], [
            'kode.required'       => 'Kode FAQ wajib diisi.',
            'kode.unique'         => 'Kode FAQ tersebut sudah digunakan oleh FAQ lain.',
            'pertanyaan.required' => 'Pertanyaan FAQ wajib diisi.',
            'jawaban.required'    => 'Isi jawaban FAQ wajib diisi.',
            'role_access.required'=> 'Pilih hak akses role.',
        ]);

        $faq->update([
            'kode'        => strtoupper(trim($request->kode)),
            'pertanyaan'  => trim($request->pertanyaan),
            'jawaban'     => trim($request->jawaban),
            'kategori'    => trim($request->kategori ?: 'Umum'),
            'role_access' => $request->role_access,
            'urutan'      => $request->urutan ?? $faq->urutan,
            'is_active'   => $request->has('is_active') ? (bool)$request->is_active : false,
        ]);

        return redirect()->route('admin.chatbot.index', ['tab' => 'faq'])
            ->with('success', 'FAQ ' . $faq->kode . ' berhasil diperbarui!');
    }

    /**
     * Hapus data FAQ dari database.
     */
    public function destroy($id)
    {
        $faq = ChatbotFaq::findOrFail($id);
        $kode = $faq->kode;
        $faq->delete();

        return redirect()->route('admin.chatbot.index', ['tab' => 'faq'])
            ->with('success', 'FAQ ' . $kode . ' berhasil dihapus dari sistem!');
    }

    /**
     * Quick toggle status aktif FAQ.
     */
    public function toggleActive($id)
    {
        $faq = ChatbotFaq::findOrFail($id);
        $faq->is_active = !$faq->is_active;
        $faq->save();

        $statusText = $faq->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.chatbot.index', ['tab' => 'faq'])
            ->with('success', "Status FAQ {$faq->kode} berhasil {$statusText}.");
    }

    /**
     * Bersihkan riwayat percakapan / log chatbot.
     */
    public function clearLogs(Request $request)
    {
        ChatbotLog::truncate();

        return redirect()->route('admin.chatbot.index', ['tab' => 'logs'])
            ->with('success', 'Seluruh riwayat percakapan chatbot berhasil dibersihkan.');
    }
}
