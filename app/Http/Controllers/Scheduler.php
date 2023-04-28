<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

/**
 * <Class Name> Scheduler
 * 
 * Create : 2021.11.24 TP Uddin <br>
 * 
 * <Overview> An invokable controller for calling the 'schedule:run' command through HTTP request <br>
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright
 */
class Scheduler extends Controller
{
    public function __invoke()
    {
        Artisan::call('schedule:run');
    }
}
