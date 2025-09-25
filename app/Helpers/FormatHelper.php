<?php

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
}
