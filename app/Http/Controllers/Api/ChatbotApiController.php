<?php

namespace App\Http\Controllers\Api;

use App\Models\ChatbotFaq;
use App\Models\ChatbotLog;
use Illuminate\Http\Request;

/**
 * Chatbot FAQ "Tanya Kami" untuk mobile. Sama dengan App\Http\Controllers\ChatbotController (web),
 * tetapi user dibaca dari token Sanctum (opsional) karena API tidak memakai session.
 */
class ChatbotApiController extends ApiController
{
    /** Role FAQ: 'student' untuk peserta & admin (seperti web), selain itu 'guest'. */
    protected function faqRole(Request $request): string
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return 'guest';
        }

        return ($user->role === 'Peserta' || $user->id_mhs !== null || in_array($user->role, ['Admin', 'Super Admin']))
            ? 'student'
            : 'guest';
    }

    /** GET /api/chatbot/questions */
    public function questions(Request $request)
    {
        $questions = ChatbotFaq::active()
            ->forRole($this->faqRole($request))
            ->orderBy('urutan')
            ->orderBy('id_faq')
            ->get(['id_faq', 'kode', 'pertanyaan', 'kategori']);

        return $this->ok(
            $questions->map(fn ($q) => [
                'id' => $q->id_faq,
                'kode' => $q->kode,
                'pertanyaan' => $q->pertanyaan,
                'kategori' => $q->kategori,
            ])->values(),
            null,
            ['categories' => $questions->pluck('kategori')->unique()->values()]
        );
    }

    /** POST /api/chatbot/answer {faq_id} — tambah counter klik & log, seperti web. */
    public function answer(Request $request)
    {
        $request->validate(['faq_id' => 'required|exists:chatbot_faqs,id_faq']);

        $faq = ChatbotFaq::active()->findOrFail($request->faq_id);
        $user = auth('sanctum')->user();

        $faq->increment('click_count');

        try {
            ChatbotLog::create([
                'faq_id' => $faq->id_faq,
                'user_id' => $user?->id_users,
                'user_type' => $user ? (($user->role === 'Peserta' || $user->id_mhs !== null) ? 'student' : strtolower($user->role)) : 'guest',
                'pertanyaan' => $faq->pertanyaan,
                'session_id' => 'mobile-api',
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Jangan gagalkan respons jika logging mengalami kendala (sama seperti web)
        }

        return $this->ok([
            'id' => $faq->id_faq,
            'kode' => $faq->kode,
            'pertanyaan' => $faq->pertanyaan,
            'jawaban' => $faq->jawaban,
            'kategori' => $faq->kategori,
        ]);
    }
}
