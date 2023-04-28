<?php
/*
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Models\SessionData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * <Class Name> SessionController
 *
 * <Function Name> Session Processing<br>
 * Create : 2019.01.23 OJT Jethro<br>
 * Update : 2020.05.18 TP Uddin Modify URLs and Method Name<br>
 *
 * <Overview> This controller is responsible for the status of the user whether its
 *			  online or offline and it manages the language that the user choose.
 * @package Controller
 * @author TP Russell <r-russell@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class SessionController extends Controller
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // getSession                   (1.0) Get the current session data
    // getUserId                    (2.0) Get the current id of the user
    // checkSession                 (3.0) Check the session time interval
    // changeLocale                 (4.0) Change the locale language

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Acquire current session datad<br>
     * <Function> Get the current session data from the database and display on the screen<br>
     *            URL: http://localhost/getSession<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object $request->session()->all()
     */
    public function getSession(Request $request)
    {
        // Return request
        return $request->session()->all();
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Acquire current id of the user<br>
     * <Function> Get the current id of the user from the database and display on the screen<br>
     *            URL: http://localhost/getUserID<br>
     *            METHOD: GET
     *
     * @return string auth()->user()
     */
    public function getUserId()
    {
        if (auth()->user()) {
            return auth()->user();
        }
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Check session time interval<br>
     * <Function> Check the session life time of the user<br>
     *            URL: http://localhost/checkSession/:id<br>
     *            METHOD: POST
     *
     * @param int $id
     * @return int $idleTime
     * @throws Throwable When an exception occurs in this process
     */
    public function checkSession(Request $request, int $id)
    {
        $ip = request()->ip();
        $idleTime = "0";
        try {
            $session = SessionData::where('USER_ID', $id)
                ->where('ip_address', $ip)
                ->first();

            // return $session[0]->last_activity - time();
            $idleTime = time() - $session['last_activity'];
            //create cookie of logged out user and store for 5 minutes
            if ($idleTime > (env('SESSION_LIFETIME')) * 60) {
                return response('loggedOut')
                    ->cookie('test', 1, 1);
            } else {
                return $idleTime;
            }
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        // Return the idletime
        return $idleTime;
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> Change the locale language<br>
     * <Function> Change the current language of the user<br>
     *            URL: http://localhost/changeLocale/:locale<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param string $locale
     * @return object $request->session()->all()
     */
    public function changeLocale(Request $request, string $locale)
    {
        try {
            if (Auth::check()) {
                $request->session()->put('locale', $locale);
                return $request->session()->get('locale');
            } else {
                return response()->noContent();
            }
        } catch (\Throwable $th) {
            report($th);
        }
    }
}
