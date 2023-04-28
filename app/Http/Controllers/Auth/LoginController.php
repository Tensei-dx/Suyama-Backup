<?php
/*
 * <System Name> iBMS
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Mail\ThankYouMail;
use App\Models\Book_Room;
use App\Models\BookPMS;
use App\Models\User;
use App\Traits\CommonFunctions;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * <Class Name> LoginController
 *
 * <Function Name> Login Processing<br>
 * Create : 2018.07.10 TP Bryan<br>
 * Update : 2018.12.14 TP Robert
 *          2019.06.24 TDN Asakura
 *          2019.07.15 TP Ivin      Add try Catch and Validation for disabled username and username validation
 *          2020.05.22 TP Uddin     Modify URL and method name according to the URl list<br>
 *          2021.06.06 TP Ivin      Add notification function for Check in and Check out of the Guest<br>
 *          2021.06.28 TP Chris     Add Check check-in date for Guest<br>
 *          2021.07.06 TP Shannie   Edit Check-in Function<br>
 *          2021.07.12 TP Chris     Fix the error message in Login Screen
 *          2021.08.18 TP Jermaine  SPRINT_03 Task023
 *          2021.08.16 TDN Okada    SPRINT_03 Task009
 *          2021.09.13 TDN Renji    SPRINT_07 Task141
 *
 * <Overview> This controller handles authenticating users for the application and
 *            redirecting them to your home screen. The controller uses a trait
 *            to conveniently provide its functionality to your applications.
 *
 * @package Controller
 * @author TP Russell <r-russell@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class LoginController extends Controller
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // __construct                  (1.0) Constructor
    // username                     (2.0) Get username method
    // login                        (3.0) Handle a login request to the application.
    // validateLogin                (4.0) Get the needed authorization credentials from the request and validate if the user is active or not.
    // logout                       (5.0) When Guest is Logout it will create a notification

    use AuthenticatesUsers;
    use CommonFunctions;

    /**
     * Where to redirect users after login.
     * (Extended from AuthenticatesUsers trait)
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Constructor<br>
     * <Function> Create constructor for Login<br>
     *            URL: <br>
     *            METHOD:
     *
     * @param
     * @return void
     */
    public function __construct(User $user)
    {
        $this->middleware('guest')->except('logout');
        $this->user = $user;
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Username<br>
     * <Function> Get the username of the user<br>
     *            URL: <br>
     *            METHOD:
     *
     * @param
     * @return string 'username'
     */
    public function username()
    {
        return 'username';
    }

    // + SPRINT_07 [TASK141]
    /**
     * <Layer number> (3.0)
     *
     * <Processing name> login<br>
     * <Function> Handle a login request to the application.<br>
     *            Override the login method of AuthenticatesUsers
     *            URL: /login<br>
     *            METHOD: POST
     *
     * @param  \App\Http\Requests\User\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {
        $request->session()->put('locale', $request->locale);

        // Validete user, user's reservation and status
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> Validate Login<br>
     * <Function> Get the needed authorization credentials from the request and validate if the user is active or not.<br>
     *            URL: http://localhost/validateLogin<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    protected function validateLogin(Request $request)
    {
        try {

            $client = app()->make('App\Http\Controllers\ClientController');
            $staysee = app()->make('App\Http\Controllers\Staysee\StayseeReservationController');

            /* check user */

            // Get user by username.
            $user = $this->user->getUserByUsername($request);

            // check credentials
            if (
                !is_null($user)
                && $this->user->checkPassword($request, $user)
            ) {

                // check if the user is valid(REG_FLG == 1)
                if (!$this->user->isValidUser($user)) {
                    throw new \Exception('230010000');
                }
            } else {
                throw new \Exception('230010000');
            }

            /* check the reservation and room status for the guest */

            // check if the user is guest or admin
            if ($this->user->isGuest($user)) {

                // Get one valid reservation.
                $reservation = $this->user->getReservation($user);

                if (is_null($reservation)) {
                    throw new \Exception('230010000');
                }

                // Varify the reservation
                if (!$client->isValidReservation($reservation)) {
                    throw new \Exception('230010001');
                }

                // Prohibit to login in the case that the room status is check-out
                if ($client->isCheckoutStatus($reservation)) {
                    $client->createRecheckinNotification($user);
                    Log::info('END @ ' . __FILE__ . '_validateLogin(Request $request)');
                    throw new \Exception('230010002');
                }

                // Allow to login in the case that the room status is reserved
                if ($client->isReservedStatus($user) || $client->isCheckinStatus($user)) {
                    // Update Staysee Room Status in order to synchronize with Staysee
                    $isSuccess = $staysee->patchStayseeCheckIn($user);

                    if (!($isSuccess)) {
                        throw new \Exception('230010003');
                    }
                    // Update room status and push notification to admin
                    $client->updateStatusToCheckin($user);
                    $client->createCheckinNotification($user);


                    $user_id = $user->USER_ID;
                    $book_room = Book_ROOM::with('room')->where('USER_ID', $user_id)->get();
                    $room_name = $book_room[0]['room']['ROOM_NAME'];

                    // $this->appLog($user_id, $room_name, 'Check-In');

                    $this->logsNotification('I008', $book_room[0]->ROOM_ID, 4);


                    // Guest login process successfully done
                    return;

                    // Prohibit to login in the case that the room status is in other status
                } else {
                    // Temporary disabled by Uddin
                    // throw new \Exception('Error 007');
                }

                // if the user is admin, don't need to check the room status and reservation
            } elseif ($this->user->isAdmin($user)) {
                // $this->appLog($user->USER_ID, 'Management Dashboard', 'Log-In');
                return;

                // Prohibit to login in the case that the user is undefined user
            } else {
                throw new \Exception('Error 008');
            }
        } catch (\Exception $e) {
            $jsonString = file_get_contents(base_path('resources/assets/js/lang/ja.json'));
            $jap = json_decode($jsonString, true);
            $jsonString = file_get_contents(base_path('resources/assets/js/lang/en.json'));
            $eng = json_decode($jsonString, true);
            $jsonString = file_get_contents(base_path('resources/assets/js/lang/en.json'));
            $ko = json_decode($jsonString, true);
            // $jsonString = file_get_contents(base_path('resources/assets/js/lang/en.json'));
            // $ch_s = json_decode($jsonString, true);
            $jsonString = file_get_contents(base_path('resources/assets/js/lang/en.json'));
            $ch_t = json_decode($jsonString, true);

            $user = $this->user->getUserByUsername($request);

            if ($e->getMessage() == '230010000') {
                // if (empty($user) == 1) {
                //     $this->appLog($request->username, 'Log-In', 'User does not exists');
                // } else {
                //     $this->appLog($user->USER_ID, 'Log-In', 'Wrong credentials');
                // }

                if (session()->get('locale') == 'ja') {
                    abort(401, $jap["error_message_code"]["230010000"]);
                } else if (session()->get('locale') == 'en') {
                    abort(401, $eng["error_message_code"]["230010000"]);
                } else if (session()->get('locale') == 'ko') {
                    abort(401, $ko["error_message_code"]["230010000"]);
                    // } else if (session()->get('locale') == 'ch_s') {
                    //     abort(401, $ch_s["error_message_code"]["230010000"]);
                } else if (session()->get('locale') == 'ch_t') {
                    abort(401, $ch_t["error_message_code"]["230010000"]);
                }
            }
            //check $e to abort unnecessary User
            elseif ($e->getMessage() == '230010001') {
                // insert to app logs.
                // $this->appLog($user->USER_ID, 'Log-In', 'User is disabled');

                if (session()->get('locale') == 'ja') {
                    abort(401, $jap["error_message_code"]["230010001"]);
                } else if (session()->get('locale') == 'en') {
                    abort(401, $eng["error_message_code"]["230010001"]);
                } else if (session()->get('locale') == 'ko') {
                    abort(401, $ko["error_message_code"]["230010001"]);
                    // } else if (session()->get('locale') == 'ch_s') {
                    //     abort(401, $ch_s["error_message_code"]["230010001"]);
                } else if (session()->get('locale') == 'ch_t') {
                    abort(401, $ch_t["error_message_code"]["230010001"]);
                }
            }
            //Error message for the Guest that has no booking for today
            elseif ($e->getMessage() == '230010002') {
                // insert to app logs.
                // $this->appLog($user->USER_ID, 'Check-In', 'No valid reservation');

                if (session()->get('locale') == 'ja') {
                    abort(401, $jap["error_message_code"]["230010002"]);
                } else if (session()->get('locale') == 'en') {
                    abort(401, $eng["error_message_code"]["230010002"]);
                } else if (session()->get('locale') == 'ko') {
                    abort(401, $ko["error_message_code"]["230010002"]);
                    // } else if (session()->get('locale') == 'ch_s') {
                    //     abort(401, $ch_s["error_message_code"]["230010002"]);
                } else if (session()->get('locale') == 'ch_t') {
                    abort(401, $ch_t["error_message_code"]["230010002"]);
                }
            }
            //Error message for the Guest that not allowed to recheck-in
            elseif ($e->getMessage() == '230010004') {;
                // insert to app logs.
                // $this->appLog($user->USER_ID, 'Check-In', 'The room status is invalid');

                if (session()->get('locale') == 'ja') {
                    abort(401, $jap["error_message_code"]["230010004"]);
                } else if (session()->get('locale') == 'en') {
                    abort(401, $eng["error_message_code"]["230010004"]);
                } else if (session()->get('locale') == 'ko') {
                    abort(401, $ko["error_message_code"]["230010004"]);
                    // } else if (session()->get('locale') == 'ch_s') {
                    //     abort(401, $ch_s["error_message_code"]["230010004"]);
                } else if (session()->get('locale') == 'ch_t') {
                    abort(401, $ch_t["error_message_code"]["230010004"]);
                }
            }
            //Error message for the Guest that has no booking
            elseif ($e->getMessage() == '230010003') {
                // insert to app logs.
                // $this->appLog($user->USER_ID, 'Check-In', 'Staysee check-in patch failed');

                if (session()->get('locale') == 'ja') {
                    abort(401, $jap["error_message_code"]["230010003"]);
                } else if (session()->get('locale') == 'en') {
                    abort(401, $eng["error_message_code"]["230010003"]);
                } else if (session()->get('locale') == 'ko') {
                    abort(401, $ko["error_message_code"]["230010003"]);
                    // } else if (session()->get('locale') == 'ch_s') {
                    //     abort(401, $ch_s["error_message_code"]["230010003"]);
                } else if (session()->get('locale') == 'ch_t') {
                    abort(401, $ch_t["error_message_code"]["230010003"]);
                }
            }
            // Error message for the invalid room status
            elseif ($e->getMessage() == 'Error 007') {
                // insert to app logs.
                // $this->appLog($user->USER_ID, 'Check-In', 'No valid status');

                if (session()->get('locale') == 'ja') {
                    abort(401, "TMP Error 007_JA");
                } else if (session()->get('locale') == 'en') {
                    abort(401, "TMP Error 007_EN");
                } else if (session()->get('locale') == 'ko') {
                    abort(401, "TMP Error 007_KO");
                    // } else if (session()->get('locale') == 'ch_s') {
                    //     abort(401, "TMP Error 007_CH_S");
                } else if (session()->get('locale') == 'ch_t') {
                    abort(401, "TMP Error 007_CH_T");
                }
            }
            // Error message for the invalid user type
            elseif ($e->getMessage() == 'Error 008') {
                // insert to app logs.
                // $this->appLog($user->USER_ID, 'Check-In', 'Invalid user type');

                if (session()->get('locale') == 'ja') {
                    abort(401, "TMP Error 008_JA");
                } else if (session()->get('locale') == 'en') {
                    abort(401, "TMP Error 008_EN");
                } else if (session()->get('locale') == 'ko') {
                    abort(401, "TMP Error 008_KO");
                    // } else if (session()->get('locale') == 'ch_s') {
                    //     abort(401, "TMP Error 008_CH_S");
                } else if (session()->get('locale') == 'ch_t') {
                    abort(401, "TMP Error 008_CH_T");
                }
            } else {
                //abort(401, "Unexpected Error");
            }
        }
    }
    // + SPRINT_07 [TASK141]

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> logout <br>
     * <Function> When Guest is Logout it will create a notification <br>
     *            URL: http://localhost/logout<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return redirect('/')
     */
    protected function logout(Request $request)
    {
        // + SPRINT_03 [TASK09]
        try {
            // + SPRINT_03 [TASK09]

            $user = auth()->user();

            // if user is guest, create check out notification
            if ($user->USER_TYPE == 2) {

                $user_id = $user->USER_ID;
                $book_room = Book_ROOM::with('room')->where('USER_ID', $user_id)->get();
                $room_id = $book_room[0]->ROOM_ID;

                $response = app('App\Http\Controllers\ClientController')->updateRoomStatusToCheckout($room_id, 202);

                if ($response == 'success') {
                    $this->logsNotification('I010', $room_id, 4);
                }

                // if user is guest, create check out notification
                // app('App\Http\Controllers\ClientController')->createCheckOutNotification($user);

                // + SPRINT_03 [TASK009]
                // get booking associated to the user
                $booking = Book_Room::firstWhere('USER_ID', $user->USER_ID);

                // send check out request to Staysee via Staysee API
                $response = app('App\Http\Controllers\Staysee\StayseeReservationController')
                    ->patchStayseeCheckOut($booking->BOOK_ID);

                // if check out response failed, create error notification for failed check out
                if ($response->clientError() || $response->serverError()) {
                    app('App\Http\Controllers\ClientController')->errorCheckOutNotification($user, $booking);
                } else {
                    // if the check out API is successful

                    // get the booking info
                    $book = BookPMS::find($booking->BOOK_ID);

                    // send a thank you mail on first checkout
                    if ($book->EMAIL && !$book->THANK_YOU_MAIL_SENT_FLAG) {
                        Mail::to($book->EMAIL)->send(new ThankYouMail($book));

                        $book->THANK_YOU_MAIL_SENT_FLAG = true;
                        $book->save();
                    }
                }

                // + SPRINT_03 [TASK009]
            }
            // + SPRINT_03 [TASK009]
        } catch (\Throwable $e) {
            $jsonString = file_get_contents(base_path('resources/assets/js/lang/ja.json'));
            $jap = json_decode($jsonString, true);
            $jsonString = file_get_contents(base_path('resources/assets/js/lang/en.json'));
            $eng = json_decode($jsonString, true);
            $jsonString = file_get_contents(base_path('resources/assets/js/lang/en.json'));
            $ko = json_decode($jsonString, true);
            // $jsonString = file_get_contents(base_path('resources/assets/js/lang/en.json'));
            // $ch_s = json_decode($jsonString, true);
            $jsonString = file_get_contents(base_path('resources/assets/js/lang/en.json'));
            $ch_t = json_decode($jsonString, true);
            // Error message for the Guest that has no booking on Staysee
            if ($e->getMessage() == '230010003') {
                if (session()->get('locale') == 'ja') {
                    abort(401, $jap["error_message_code"]["230010003"]);
                } else if (session()->get('locale') == 'en') {
                    abort(401, $eng["error_message_code"]["230010003"]);
                } else if (session()->get('locale') == 'ko') {
                    abort(401, $ko["error_message_code"]["230010003"]);
                    // } else if (session()->get('locale') == 'ch_s') {
                    //     abort(401, $ch_s["error_message_code"]["230010003"]);
                } else if (session()->get('locale') == 'ch_t') {
                    abort(401, $ch_t["error_message_code"]["230010003"]);
                }
            }
        }
        $user = auth()->user();
        $username = $user->USERNAME;
        $user_id = $user->USER_ID;
        // if ($user->USER_TYPE == 1) {
        //     $this->appLog($user_id, 'Management Dashboard', 'Log-Out');
        // }
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect('/thankyou');
    }
}
