<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Translation\Translator as BaseTranslator;

class CustomTranslator extends BaseTranslator
{
    /**
     * Get the translation for the given key, seamlessly supporting both:
     * 1. Legacy bilingual strings: __($bn, $en)
     * 2. Standard Laravel translations: __($key, array $replace = [], $locale = null)
     *
     * @param  string  $key
     * @param  mixed  $replace
     * @param  string|null  $locale
     * @param  bool  $fallback
     * @return string|array|null
     */
    public function get($key, $replace = [], $locale = null, $fallback = true)
    {
        if (is_string($replace)) {
            try {
                $lang = session('locale', session('lang', 'bn'));
            } catch (\Throwable) {
                $lang = 'bn';
            }
            return ($lang === 'en' && !empty($replace)) ? $replace : $key;
        }

        if (!is_array($replace)) {
            $replace = [];
        }

        return parent::get($key, $replace, $locale, $fallback);
    }
}
