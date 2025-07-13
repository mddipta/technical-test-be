<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('getVendor')) {
    function getVendor()
    {
        return Auth::user()->vendor;
    }
}
