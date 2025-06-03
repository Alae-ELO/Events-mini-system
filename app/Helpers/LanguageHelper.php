<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class LanguageHelper
{
    public static function getLocale()
    {
        return Session::get('locale', 'en');
    }

    public static function setLocale($locale)
    {
        Session::put('locale', $locale);
        app()->setLocale($locale);
    }
} 