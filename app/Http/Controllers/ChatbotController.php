<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatbotFaq;
use App\Models\ChatbotLog;

class ChatbotController extends Controller
{
    /**
     * Ambil daftar tombol FAQ berdasarkan role pengguna saat ini.
     * - Guest: melihat FAQ dengan role_access 'all' dan 'guest'
     * - Student: melihat seluruh FAQ Guest ditambah 'student'
     */
    public function getQuestions(Request $request)
    {
        $role = 'guest';
        $userName = null;

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'Peserta' || $user->id_mhs !== null) {
                $role = 'student';
                $userName = $user->nama_lengkap;
            } elseif ($user->role === 'Admin' || $user->role === 'Super Admin') {
                $role = 'student'; // Admin/Superadmin juga dapat menguji seluruh pertanyaan termasuk student
                $userName = $user->nama_lengkap;
            }
        }

        $questions = ChatbotFaq::active()
            ->forRole($role)
            ->orderBy('urutan', 'asc')
            ->orderBy('id_faq', 'asc')
            ->get([
                'id_faq',
                'kode',
                'pertanyaan',
                'kategori',
                'role_access',
            ]);

        // Kategori unik untuk tab filter jika diperlukan di frontend
        $categories = $questions->pluck('kategori')->unique()->values();

        return response()->json([
            'success'    => true,
            'role'       => $role,
            'user_name'  => $userName,
            'categories' => $categories,
            'questions'  => $questions,
        ]);
    }

    /**
     * Ambil jawaban dari database berdasarkan faq_id yang diklik user,
     * lalu simpan log percakapan / interaksi.
     */
    public function getAnswer(Request $request)
    {
        $request->validate([
            'faq_id' => 'required|exists:chatbot_faqs,id_faq',
        ]);

        $faq = ChatbotFaq::active()->findOrFail($request->faq_id);

        // Tentukan role pengguna saat ini
        $role = 'guest';
        $userId = null;
        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id_users;
            $role = ($user->role === 'Peserta' || $user->id_mhs !== null) ? 'student' : strtolower($user->role);
        }

        // 1. Tambah counter klik FAQ
        $faq->increment('click_count');

        // 2. Rekam log percakapan / klik
        try {
            ChatbotLog::create([
                'faq_id'     => $faq->id_faq,
                'user_id'    => $userId,
                'user_type'  => $role,
                'pertanyaan' => $faq->pertanyaan,
                'session_id' => session()->getId(),
                'ip_address' => $request->ip(),
                'user_agent' => substr((string)$request->userAgent(), 0, 500),
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Jangan gagalkan respons jika logging mengalami kendala
        }

        return response()->json([
            'success' => true,
            'faq'     => [
                'id_faq'     => $faq->id_faq,
                'kode'       => $faq->kode,
                'pertanyaan' => $faq->pertanyaan,
                'jawaban'    => $faq->jawaban,
                'kategori'   => $faq->kategori,
            ],
            'role'    => $role,
        ]);
    }
}
