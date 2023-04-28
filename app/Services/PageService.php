<?php

namespace App\Services;

class PageService
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // getFirstPage                 (1.0) Redirect to dashboard page

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Display dashboard page<br>
     * <Function> Redirect to dashboard page<br>
     *            URL: http://localhost/dashboard<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\View\View dashboard
     */
    public function getFirstPage()
    {
        $view = "home";                 // Original dashboard

        //Check if the user who logged in is and admmin or a guest.
        if (auth()->user()->USER_TYPE == 1) {
            $view = "management.index";  // Staff dashboard

            // $view = "home";
        } else if (auth()->user()->USER_TYPE == 2) {

            $view = "guest.terms";    // Guest dashboard
            // $view = "guest.welcome";    // Guest dashboard
            // $view = "guest.guestcard";    // Guest dashboard

            // Get book data for showing guest card registration screen
            // $bookid = BOOK_ROOM::where('USER_ID', auth()->user()->USER_ID)->value('BOOK_ID');
            // $response = app('App\Http\Controllers\GuestCardController')
            //     ->showReservationDetails($bookid);

            // return view($view)->with('guestData', $response)
            //     ->with('modules', app('App\Http\Controllers\DashboardController')->getModules());
        } else if (auth()->user()->USER_TYPE == 3) {
            $view = "cleaning.index";   // Janitor dashboard
        }
        return view($view);
    }
}
