<?php

// format currency
if (! function_exists('formatCurrencyDetail')) {
    function formatCurrencyDetail($value, $currency) {
        if ($value === null || $currency === null) return '';

        $locales = [
            'CHF' => 'fr_CH',
            'EUR' => 'de_DE',
            'GBP' => 'en_GB',
            'IDR' => 'id_ID',
            'MYR' => 'ms_MY',
            'SGD' => 'en_SG',
            'USD' => 'en_US',
            'JPY' => 'ja_JP',
        ];

        $locale = $locales[$currency] ?? 'en';

        $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        $formatted = $formatter->formatCurrency($value, $currency);

        // kalau gagal → fallback manual
        if ($formatted === false) {
            return number_format($value, 2, ',', '.') . ' ' . $currency;
        }

        return $formatted;
    }

    if (! function_exists('formatNumberOnly')) {
        function formatNumberOnly($value, $currency) {
            if ($value === null) return '';

            if ($currency === 'IDR') {
                return number_format($value, 0, ',', '.');
            } else {
                return number_format($value, 2, ',', '.');
            }
        }
    }

    if (!function_exists('formatRupiahNull')) {
        function formatRupiahNull($value)
        {
            if (is_null($value) || $value == 0) {
                return '';
            }

            return 'Rp. ' . number_format($value, 0, ',', '.');
        }
    }
}

// format terbilang
function terbilang($angka)
{
    $angka = abs((int)$angka);

    $bilang = [
        "", "Satu", "Dua", "Tiga", "Empat", "Lima",
        "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"
    ];

    if ($angka < 12) {
        $result = $bilang[$angka];
    } elseif ($angka < 20) {
        $result = terbilang($angka - 10) . " Belas";
    } elseif ($angka < 100) {
        $result = terbilang($angka / 10) . " Puluh " . terbilang($angka % 10);
    } elseif ($angka < 200) {
        $result = "Seratus " . terbilang($angka - 100);
    } elseif ($angka < 1000) {
        $result = terbilang($angka / 100) . " Ratus " . terbilang($angka % 100);
    } elseif ($angka < 2000) {
        $result = "Seribu " . terbilang($angka - 1000);
    } elseif ($angka < 1000000) {
        $result = terbilang($angka / 1000) . " Ribu " . terbilang($angka % 1000);
    } elseif ($angka < 1000000000) {
        $result = terbilang($angka / 1000000) . " Juta " . terbilang($angka % 1000000);
    } elseif ($angka < 1000000000000) {
        $result = terbilang($angka / 1000000000) . " Miliar " . terbilang($angka % 1000000000);
    } elseif ($angka < 1000000000000000) {
        $result = terbilang($angka / 1000000000000) . " Triliun " . terbilang($angka % 1000000000000);
    } else {
        $result = "Jumlah terlalu besar";
    }

    return trim(preg_replace('/\s+/', ' ', $result));
}
