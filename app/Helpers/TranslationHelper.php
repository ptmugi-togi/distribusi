<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

if (!function_exists('autoTranslate')) {
    /**
     * Otomatis translate teks ke bahasa sasaran (default: Inggris)
     */
    function autoTranslate(?string $text, string $targetLang = 'en', string $sourceLang = 'id'): string
    {
        if (empty($text) || trim($text) === '') {
            return $text ?? '';
        }

        $cacheKey = 'auto_trans_' . md5(trim($text) . '_' . $sourceLang . '_' . $targetLang);

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($text, $sourceLang, $targetLang) {
            try {
                $response = Http::withoutVerifying()
                    ->timeout(5)
                    ->get('https://translate.googleapis.com/translate_a/single', [
                        'client' => 'gtx',
                        'sl'     => $sourceLang,
                        'tl'     => $targetLang,
                        'dt'     => 't',
                        'q'      => $text,
                    ]);

                if ($response->successful()) {
                    $result = $response->json();

                    if (isset($result[0]) && is_array($result[0])) {
                        $translatedText = '';
                        foreach ($result[0] as $sentence) {
                            $translatedText .= $sentence[0] ?? '';
                        }

                        return !empty(trim($translatedText)) ? $translatedText : $text;
                    }
                }

                return $text;
            } catch (\Exception $e) {
                Log::warning('Google Translate Error: ' . $e->getMessage());
                return $text;
            }
        });
    }
}