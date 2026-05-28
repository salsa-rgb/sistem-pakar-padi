<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Symptom;
use App\Models\Disease;
use App\Models\DiagnosticSession;
use Illuminate\Support\Facades\Log; 

class TelegramController extends Controller
{
    protected $token = "8368383773:AAH67n7d4trdcA8I8QdS_9tMQscaGzGQZ58";

    public function handle(Request $request)
    {
        // ===================================================
        // Balas Telegram DULU sebelum proses apapun
        // Ini mencegah timeout karena Telegram tidak perlu
        // menunggu Laravel selesai memproses
        // ===================================================
        ob_start();
        echo json_encode(['ok' => true]);
        $size = ob_get_length();
        header("Content-Length: $size");
        header("Connection: close");
        ob_end_flush();
        ob_flush();
        flush();
        // ===================================================

        $updates = $request->all();
        
        Log::info("Data masuk dari Telegram: ", $updates);

        if (isset($updates['message'])) {
            $chatId = $updates['message']['chat']['id'];
            $text   = $updates['message']['text'] ?? '';

            if ($text == '/start') {
                return $this->sendMessage($chatId,
                    "👋 *Selamat Datang di DokterPadi!*\n\n" .
                    "Saya akan membantu mendiagnosa penyakit pada tanaman padi Anda.\n\n" .
                    "Ketik /diagnosa untuk mulai memilih gejala."
                );
            }

            if ($text == '/diagnosa') {
                DiagnosticSession::where('chat_id', $chatId)->delete();
                return $this->showSymptoms($chatId);
            }
        }

        if (isset($updates['callback_query'])) {
            $callbackData    = $updates['callback_query']['data'];
            $chatId          = $updates['callback_query']['message']['chat']['id'];
            $callbackQueryId = $updates['callback_query']['id'];

            // Ambil teks tombol yang diklik
            $buttonText = "Gejala";
            $rows = $updates['callback_query']['message']['reply_markup']['inline_keyboard'];
            foreach ($rows as $row) {
                foreach ($row as $button) {
                    if ($button['callback_data'] === $callbackData) {
                        $buttonText = $button['text'];
                    }
                }
            }

            // User memilih gejala
            if (str_contains($callbackData, 'gejala_')) {
                $symptomId = str_replace('gejala_', '', $callbackData);

                // Cek apakah gejala sudah dipilih sebelumnya
                $exists = DiagnosticSession::where('chat_id', $chatId)
                                           ->where('symptom_id', $symptomId)
                                           ->exists();

                if ($exists) {
                    // Jika sudah dipilih, hapus (toggle off)
                    DiagnosticSession::where('chat_id', $chatId)
                                     ->where('symptom_id', $symptomId)
                                     ->delete();
                    $this->answerCallback($callbackQueryId);
                    return $this->sendMessage($chatId,
                        "❎ Gejala *{$buttonText}* telah dibatalkan."
                    );
                } else {
                    // Jika belum dipilih, tambahkan
                    DiagnosticSession::create([
                        'chat_id'    => $chatId,
                        'symptom_id' => $symptomId,
                    ]);
                    $this->answerCallback($callbackQueryId);
                    return $this->sendMessage($chatId,
                        "✅ Gejala *{$buttonText}* telah dipilih."
                    );
                }
            }

            // User minta lihat hasil diagnosa
            if ($callbackData == 'proses_diagnosa') {
                $this->answerCallback($callbackQueryId);
                $this->sendMessage($chatId, "⏳ Sedang memproses diagnosa...");
                return $this->prosesForwardChaining($chatId);
            }

            // User minta reset/ulangi
            if ($callbackData == 'ulangi_diagnosa') {
                $this->answerCallback($callbackQueryId);
                DiagnosticSession::where('chat_id', $chatId)->delete();
                return $this->showSymptoms($chatId);
            }
        }
    }

    private function showSymptoms($chatId)
    {
        $symptoms = Symptom::all();

        if ($symptoms->isEmpty()) {
            return $this->sendMessage($chatId,
                "❌ Maaf, data gejala belum tersedia."
            );
        }

        // Hitung jumlah gejala yang sudah dipilih
        $selectedCount = DiagnosticSession::where('chat_id', $chatId)->count();

        $buttons = [];
        foreach ($symptoms as $s) {
            $buttons[] = [[
                'text'          => $s->description,
                'callback_data' => 'gejala_' . $s->id,
            ]];
        }

        // Tombol aksi di bagian bawah
        $buttons[] = [[
            'text'          => '🔍 Lihat Hasil Diagnosa',
            'callback_data' => 'proses_diagnosa',
        ]];

        $pesan = "🌿 *Sistem Pakar Penyakit Padi*\n\n" .
                 "Silakan pilih gejala yang dialami tanaman Anda.\n" .
                 "Minimal pilih *3 gejala* untuk mendapatkan hasil diagnosa.\n\n" .
                 "✅ Gejala dipilih: *{$selectedCount}*\n\n" .
                 "Setelah selesai memilih, klik tombol *Lihat Hasil Diagnosa*.";

        return $this->sendMessage($chatId, $pesan, $buttons);
    }

    private function prosesForwardChaining($chatId)
    {
        $selectedSymptoms = DiagnosticSession::where('chat_id', $chatId)
                                              ->pluck('symptom_id')
                                              ->toArray();

        // Cek apakah user sudah memilih gejala
        if (empty($selectedSymptoms)) {
            return $this->sendMessage($chatId,
                "⚠️ Anda belum memilih gejala apapun!\n\n" .
                "Silakan pilih minimal *3 gejala* terlebih dahulu.\n\n" .
                "Ketik /diagnosa untuk memulai."
            );
        }

        // Cek minimum gejala yang dipilih
        $minimumMatch = 3;
        if (count($selectedSymptoms) < $minimumMatch) {
            return $this->sendMessage($chatId,
                "⚠️ Gejala yang dipilih terlalu sedikit!\n\n" .
                "Anda memilih *" . count($selectedSymptoms) . " gejala*.\n" .
                "Minimal pilih *{$minimumMatch} gejala* untuk mendapatkan hasil diagnosa.\n\n" .
                "Ketik /diagnosa untuk memulai ulang."
            );
        }

        $diseases     = Disease::with('symptoms')->get();
        $bestMatch    = null;
        $highestScore = 0;

        foreach ($diseases as $disease) {
            $ruleSymptoms = $disease->symptoms->pluck('id')->toArray();

            if (empty($ruleSymptoms)) continue;

            // Hitung gejala yang cocok
            $matched    = count(array_intersect($ruleSymptoms, $selectedSymptoms));
            $total      = count($ruleSymptoms);
            $confidence = ($matched / $total) * 100;

            Log::info("Penyakit: {$disease->name}", [
                'matched'    => $matched,
                'total'      => $total,
                'confidence' => $confidence,
            ]);

            // Minimal harus cocok $minimumMatch gejala
            if ($matched >= $minimumMatch && $confidence > $highestScore) {
                $highestScore = $confidence;
                $bestMatch    = [
                    'disease'    => $disease,
                    'confidence' => round($confidence, 2),
                    'matched'    => $matched,
                    'total'      => $total,
                ];
            }
        }

        if ($bestMatch) {
            $disease = $bestMatch['disease'];
            $pesan   = "🔬 *HASIL DIAGNOSA*\n\n" .
                       "🦠 *Penyakit :* {$disease->name}\n" .
                       "📊 *Kecocokan:* {$bestMatch['confidence']}%\n" .
                       "✅ *Gejala Cocok:* {$bestMatch['matched']} dari {$bestMatch['total']}\n\n" .
                       "💊 *Solusi:*\n{$disease->solution}\n\n" .
                       "Ketik /diagnosa untuk diagnosa ulang.";
        } else {
            $pesan = "❌ *Penyakit tidak teridentifikasi.*\n\n" .
                     "Gejala yang dipilih tidak cukup cocok dengan data yang ada.\n" .
                     "Minimal *{$minimumMatch} gejala* harus cocok untuk mendapatkan hasil.\n\n" .
                     "Silakan konsultasikan ke ahli pertanian terdekat.\n\n" .
                     "Ketik /diagnosa untuk mencoba lagi.";
        }

        // Hapus sesi setelah diagnosa selesai
        DiagnosticSession::where('chat_id', $chatId)->delete();

        return $this->sendMessage($chatId, $pesan);
    }

    protected function sendMessage($chatId, $text, $buttons = null)
    {
        $params = [
            'chat_id'    => $chatId,
            'text'       => $text,
            'parse_mode' => 'Markdown',
        ];

        if ($buttons) {
            $params['reply_markup'] = json_encode(['inline_keyboard' => $buttons]);
        }

        return Http::withOptions(['verify' => false])
            ->post("https://api.telegram.org/bot{$this->token}/sendMessage", $params);
    }

    protected function answerCallback($id)
    {
        return Http::withOptions(['verify' => false])
            ->post("https://api.telegram.org/bot{$this->token}/answerCallbackQuery", [
                'callback_query_id' => $id,
            ]);
    }
}