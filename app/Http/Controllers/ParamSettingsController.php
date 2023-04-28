<?php

namespace App\Http\Controllers;

use App\Models\ParamSettings;
use Illuminate\Http\Request;

/**
 * <Class Name> ParamSettingsController
 *
 * <Function Name> Parameters Settings<br>
 * Create : 2022.02.03 TP Russell<br>
 *
 * <Overview> This controller is responsible for managing param settings table.
 * @package Controller
 * @author TP Russell <r-russell@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 Tensei Data Net Inc.
 */

class ParamSettingsController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // updateParamSettings           (1.0) Create new parameter settings

    public function updateParamSettings(Request $request)
    {
        $paramSettings = ParamSettings::where('PARAM_ID', 1)->first();
        $paramSettings->AC_AUTO_START = $request->AC_AUTO_START;
        $paramSettings->AC_START_OFFSET = $request->AC_START_OFFSET;
        $paramSettings->AC_MODE = $request->AC_MODE;
        $paramSettings->RL_NUM_PIN = $request->RL_NUM_PIN;
        $paramSettings->MAIL_THANKYOU_EN_CONTENT = $request->MAIL_THANKYOU_EN_CONTENT;
        $paramSettings->MAIL_THANKYOU_JA_CONTENT = $request->MAIL_THANKYOU_JA_CONTENT;
        $paramSettings->MAIL_REMIND_OFFSET = $request->MAIL_REMIND_OFFSET;
        $paramSettings->WIFI_NAME = $request->WIFI_NAME;
        $paramSettings->WIFI_PASSWORD = $request->WIFI_PASSWORD;
        $paramSettings->save();
        return 'success';
    }

    public function getParamSettings()
    {
        $paramSettings = ParamSettings::where('PARAM_ID', 1)->first();
        return $paramSettings;
    }
}
