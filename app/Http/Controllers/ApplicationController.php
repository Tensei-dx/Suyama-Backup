<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
    public function getAppVersion()
    {
        Log::info('START @ ' . __FILE__ . '_getAppVersion()');
        Log::info("\t" . 'INPUT @ ');
        Log::info('noContent');

        Log::info("\t" . 'RETURN @ ');
        Log::info('noContent');
        Log::info('END @ ' . __FILE__ . '_getAppVersion()');
        return config('app.version');
    }
}
