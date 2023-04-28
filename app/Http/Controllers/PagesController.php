<?php
/*
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Models\Book_Room;
use App\Models\User;
use App\Services\PageService;
use Illuminate\Http\Request;

/**
 * <Class Name> PagesController
 *
 * <Function Name> Pages Processing and Management<br>
 * Create : 2018.07.02 TP Bryan<br>
 * Update : 2018.11.21 TP Raymond Add code for modules
 *          2019.07.09 TP Ivin Checking of Hierarchy and Adding of return comments
 *          2021.03.02 TP Uddin For testing purposes, set $view to home for dashboard
 *          2021.09.03 TDN Okada SPRINT_05 [Task131]
 *
 * <Overview> This controller is responsible for managing and redirection of pages.
 */
class PagesController extends Controller
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // toppage                        (1.0) Redirect to toppage page
    // monitoring                       (2.0) Redirect to monitoring page
    // users                            (3.0) Redirect to user management page
    // gateway                          (4.0) Redirect to gateway hardware management page
    // device                           (5.0) Redirect to device hardware management page
    // binding                          (6.0) Redirect to binding hardware management page
    // logs                             (8.0) Redirect to logs page
    // notifications                    (9.0) Redirect to notifications page
    // displayview                      (10.0) Redirect to displayview page
    // floor                            (11.0) Redirect to floor management page
    // about                            (12.0) Redirect to about page
    // help                             (13.0) Redirect to help page
    // showUpdateGuestAccount           (18.0) Update Guest Account page
    // showTerms                        (19.0) Show terms and condition page

    /**
     * The PageService instance.
     *
     * @var App\Services\PageService
     */
    protected $pageService;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Display toppage page<br>
     * <Function> Redirect to toppage page<br>
     *            URL: http://localhost/<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View toppage
     */
    public function dashboard(Request $request)
    {
        return $this->pageService->getFirstPage();
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Display "locations" page<br>
     * <Function> Redirect to monitoring page<br>
     *            URL: http://localhost/monitoring<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View dashboard
     */
    public function monitoring(Request $request)
    {
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Display users page<br>
     * <Function> Redirect to user management page<br>
     *            URL: http://localhost/users<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View users
     */
    public function users(Request $request)
    {
        try {
            return view('users.index')->with(
                'modules',
                app('App\Http\Controllers\DashboardController')->getModules()
            );
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> Display gateway management page<br>
     * <Function> Redirect to gateway hardware management page<br>
     *            URL: http://localhost/gateway-management
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View Harware Management(Gateway)
     */
    public function gateway(Request $request)
    {
        return view('hardware.gateway')->with(
            'modules',
            app('App\Http\Controllers\DashboardController')->getModules()
        );
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> Display device management page<br>
     * <Function> Redirect to device hardware management page<br>
     *            URL: http://localhost/device-management<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View Harware Management(Device)
     */
    public function device(Request $request)
    {
        return view('hardware.device')->with(
            'modules',
            app('App\Http\Controllers\DashboardController')->getModules()
        );
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> Display binding management page<br>
     * <Function> Redirect to binding hardware management page<br>
     *            URL: http://localhost/binding-management<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View Harware Management(Binding)
     */
    public function binding(Request $request)
    {
        return view('hardware.binding')->with(
            'modules',
            app('App\Http\Controllers\DashboardController')->getModules()
        );
    }



    /**
     * <Layer number> (8.0)
     *
     * <Processing name> Display logs page<br>
     * <Function> Redirect to logs page<br>
     *            URL: http://localhost/logs<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View Logs
     */
    public function logs(Request $request)
    {
        return view('logs.index')->with(
            'modules',
            app('App\Http\Controllers\DashboardController')->getModules()
        );
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name> Display notifications page<br>
     * <Function> Redirect to notifications page<br>
     *            URL: http://localhost/notifications<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View
     */
    public function notifications(Request $request)
    {
    }

    /**
     * <Layer number> (10.0)
     *
     * <Processing name> Display display view page<br>
     * <Function> Redirect to Display View page<br>
     *            URL: http://localhost/displayview<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View Device Operation
     */
    public function displayview(Request $request)
    {
        return view('displayview.index')->with(
            'modules',
            app('App\Http\Controllers\DashboardController')->getModules()
        );
    }

    /**
     * <Layer number> (11.0)
     *
     * <Processing name> Display floor View page<br>
     * <Function> Redirect to floor View page<br>
     *            URL: http://localhost/floor<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View Floor Management
     */
    public function floor(Request $request)
    {
        return view('floor.index')->with(
            'modules',
            app('App\Http\Controllers\DashboardController')->getModules()
        );
    }

    /**
     * <Layer number> (12.0)
     *
     * <Processing name> Display about page<br>
     * <Function> Redirect to about page<br>
     *            URL: http://localhost/about<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View ABout
     */
    public function about(Request $request)
    {
        return view('about.index')->with(
            'modules',
            app('App\Http\Controllers\DashboardController')->getModules()
        );
    }

    /**
     * <Layer number> (13.0)
     *
     * <Processing name> Display help page<br>
     * <Function> Redirect to help page<br>
     *            URL: http://localhost/help<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View Help
     */
    public function help(Request $request)
    {
        return view('help.index')->with(
            'modules',
            app('App\Http\Controllers\DashboardController')->getModules()
        );
    }

    /**
     *
     */
    public function management()
    {
        return view('management.index')->with(
            'modules',
            app('App\Http\Controllers\DashboardController')->getModules()
        );
    }

    /**
     *
     */
    public function guest()
    {
        return view('guest.index')->with(
            'modules',
            app('App\Http\Controllers\DashboardController')->getModules()
        );
    }

    /**
     *
     */
    public function welcome()
    {
        return  view('guest.welcome')->with(
            'modules',
            app('App\Http\Controllers\DashboardController')->getModules()
        );
    }

    /**
     *
     */
    public function roomselect()
    {
        return  view('guest.roomselect')->with(
            'modules',
            app('App\Http\Controllers\DashboardController')->getModules()
        );
    }

    /////////////////////////// /**
    ///////////////////////////  *
    ///////////////////////////  */
    /////////////////////////// public function guestcard()
    /////////////////////////// {
    ///////////////////////////     return  view('guestcard')->with('modules',
    ///////////////////////////         app('App\Http\Controllers\DashboardController')->getModules());
    /////////////////////////// }

    /**
     *
     */
    public function remotelock(Request $request)
    {
        return view('remotelock.index')->with(
            'modules',
            app('App\Http\Controllers\DashboardController')->getModules()
        );
    }

    /**
     *
     */
    public function remotelockGuest(Request $request)
    {
        return view('remotelock.guestAccount.index')->with(
            'modules',
            app('App\Http\Controllers\DashboardController')->getModules()
        );
    }


    /**
     * <Layer number> (18.0)
     *
     * <Processing name> <br>
     * <Function> Update Guest Account page<br>
     *            URL: http://localhost/remotelock/updateGuest/{id}<br>
     *            METHOD: GET
     *
     * @param $user_id
     * @return Illuminate\View\View updateAccount
     */
    public function showUpdateGuestAccount(Request $request, $user_id)
    {
        try {
            $updateInfo = Book_Room::with('book')->where('USER_ID', $user_id)->first();
            $user = User::find($user_id);

            if (!is_null($updateInfo) && !is_null($user)) {
                $merged_array = array_merge($updateInfo->attributesToArray(), $user->attributesToArray());
                $updateData = json_encode($merged_array);
            } else {
                $updateData = json_encode(null);
            }

            return view('remotelock.updateAccount.index')
                ->with([
                    'updateData' => $updateData,
                    'modules' => app('App\Http\Controllers\DashboardController')->getModules()
                ]);
        } catch (\Exception $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);

            return view('remotelock.updateAccount.index')
                ->with([
                    'updateData' => json_encode(null),
                    'modules' => app('App\Http\Controllers\DashboardController')->getModules()
                ]);
        }
    }

    /**
     * <Layer number> (19.0)
     *
     * <Processing name> showTerms<br>
     * <Function> Show Terms And Condition<br>
     *            URL: http://localhost/terms<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View
     */
    public function showTerms(Request $request)
    {
        return view('guest.terms')->with(
            'modules',
            app('App\Http\Controllers\DashboardController')->getModules()
        );
    }

    /**
     * <Layer number> (20.0)
     *
     * <Processing name> logout<br>
     * <Function> Logout Screen<br>
     *            URL: http://localhost/logout<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View
     */
    public function logout(Request $request)
    {
        return view('guest.logout');
    }

    public function guestinfo(Request $request, User $user)
    {
        if (auth()->user()->USER_TYPE !== 1) {
            return response('Unauthrized', 401);
        }

        $user->load('bookRoom.room');

        // return $user;
        return view('guest.guestinfo')
            ->with([
                'username' => $user->USERNAME,
                'pin' => $user->bookRoom->PIN,
                'room_name' => $user->bookRoom->room->ROOM_NAME,
                'check_in' => $user->bookRoom->CHECK_IN_TIME,
                'check_out' => $user->bookRoom->CHECK_OUT_TIME,
            ]);
    }
}
