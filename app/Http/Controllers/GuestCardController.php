<?php

namespace App\Http\Controllers;

use App\Models\Book_Room;
use App\Models\GuestCard;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GuestCardController extends Controller
{

    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // showReservationDetails           (1.0) Get the details of reservation
    // addGuestDetails                  (2.0) Registering data in the DB
    // imageStore                       (3.0) Store images in a folder
    // urlStore                         (4.0) Stores ImageURL of identification cards.
    // csvStore                         (5.0) Stores csv of identification cards.
    // putCsv                           (6.0) Write csv of identification cards.

    /**
     * <Processing name> Show GUESTCARD screen <br>
     * <Function> return the view of GUESCARD screen
     *
     * @param Illuminate\Http\Request $request
     * @return view
     */
    public function showGuestcard(Request $request)
    {
        if (auth()->user()->USER_TYPE == 2) {
            // Get book data for showing guest card registration screen
            $bookid = BOOK_ROOM::where('USER_ID', auth()->user()->USER_ID)->value('BOOK_ID');
            $response = app('App\Http\Controllers\GuestCardController')
                ->showReservationDetails($bookid);

            return view("guest.guestcard")->with('guestData', $response)
                ->with('modules', app('App\Http\Controllers\DashboardController')->getModules());
        } else {
            abort(404);
        }
    }


    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Reservation Details API <br>
     * <Function> When Guest is Logout it will update on Staysee by staysee API <br>
     *            URL: https://api.staysee.jp/v1/reservations/00<br>
     *            METHOD: GET
     *
     * @param int $bookid
     * @return void
     */
    public function showReservationDetails(int $bookid)
    {
        $json = Http::withToken(env('STAYSEE_TOKEN'))
            ->get('https://api.staysee.jp/v1/reservations/' . $bookid . '?children=allocate_rooms,reservation_members')
            ->json();

        $guestData = json_encode($json);

        return $guestData;
    }


    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Add GuestDetails <br>
     * <Function>   Add the information from the guest card to the DB <br>
     *
     * @param Illuminate\Http\Request $request
     * @return $guestcard_data
     */
    // public function addGuestDetails(Request $request)
    // {
    //     $guestcard_data = GuestCard::updateOrCreate(
    //         ['MEMBERS_ID' => $request->get('MEMBERS_ID')],
    //         [
    //             'BOOK_ID'            => $request->get('BOOK_ID'),
    //             'BOOK_NO'            => $request->get('BOOK_NO'),
    //             'MEMBERS_ID'         => $request->get('MEMBERS_ID'),
    //             'MEMBER_TYPE'        => $request->get('MEMBER_TYPE'),
    //             'NAME'               => $request->get('NAME'),
    //             'SEX'                => $request->get('SEX'),
    //             'AGE'                => $request->get('AGE'),
    //             'OCCUPATION'         => $request->get('OCCUPATION'),
    //             'TEL'                => $request->get('TEL'),
    //             'EMAIL'              => $request->get('EMAIL'),
    //             'ADDRESS'            => $request->get('ADDRESS'),
    //             'PASSPORT_URL'       => $request->get('PASSPORT_URL'),
    //             'NATIONALITY'        => $request->get('NATIONALITY'),
    //             'PASSPORT_NO'        => $request->get('PASSPORT_NO'),
    //             'PREVIOUS_PLACE'     => $request->get('PREVIOUS_PLACE'),
    //             'NEXT_DESTINATION'   => $request->get('NEXT_DESTINATION'),
    //         ]
    //     );
    //     $guestcard_data->save();
    //     return $guestcard_data;
    // }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Image Store <br>
     * <Function> Stores images of identification cards. <br>
     *
     * @param Illuminate\Http\Request $request
     * @return $file_path
     */
    // public function imageStore(Request $request)
    // {
    //     $user_id = auth()->user()->USER_ID;
    //     $room_id = Book_ROOM::where('USER_ID', $user_id)->first(['ROOM_ID']);
    //     $room_name = Room::where('ROOM_ID', $room_id->ROOM_ID)->first(['ROOM_NAME']);

    //     $time = Book_Room::where('USER_ID', $user_id)->first(['CHECK_IN_TIME']);
    //     $CItime = new Carbon($time->CHECK_IN_TIME);

    //     // accompany_id is incremented according to the number of people.
    //     $accompany_id = '001';
    //     $img_name = $CItime->format('Ymd') . '__' .  $room_id->ROOM_ID . '__' . $room_name->ROOM_NAME . '__' . $accompany_id . '__passport.jpeg';

    //     request()->file('file')->storeAs('/storeImage', $img_name);

    //     return true;
    // }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> url Store <br>
     * <Function> Stores ImageURL of identification cards. <br>
     *
     * @param Illuminate\Http\Request $request
     * @return true
     */
    // public function urlStore(Request $request)
    // {
    //     $user_id = auth()->user()->USER_ID;
    //     $room_id = Book_ROOM::where('USER_ID', $user_id)->first(['ROOM_ID']);
    //     $room_name = Room::where('ROOM_ID', $room_id->ROOM_ID)->first(['ROOM_NAME']);

    //     $time = Book_Room::where('USER_ID', $user_id)->first(['CHECK_IN_TIME']);
    //     $CItime = new Carbon($time->CHECK_IN_TIME);

    //     // accompany_id is incremented according to the number of people.
    //     $accompany_id = '001';
    //     $img_name = $CItime->format('Ymd') . '__' .  $room_id->ROOM_ID . '__' . $room_name->ROOM_NAME . '__' . $accompany_id . '__passport.jpeg';
    //     $path = app_path() . '\storeImage\\' . $img_name;

    //     GuestCard::where('MEMBERS_ID', $request->get('MEMBERS_ID'))->update(['PASSPORT_URL' => $path]);

    //     return $path;
    // }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> csv Store <br>
     * <Function> Stores csv of identification cards. <br>
     *
     * @param Illuminate\Http\Request $request
     * @return true
     */
    // public function csvStore(Request $request)
    // {
    //     $user_id = auth()->user()->USER_ID;
    //     $room_id = Book_ROOM::where('USER_ID', $user_id)->first(['ROOM_ID']);
    //     $room_name = Room::where('ROOM_ID', $room_id->ROOM_ID)->first(['ROOM_NAME']);

    //     $time = Book_Room::where('USER_ID', $user_id)->first(['CHECK_IN_TIME']);
    //     $CItime = new Carbon($time->CHECK_IN_TIME);

    //     $data = GuestCard::select('NAME', 'SEX', 'AGE', 'OCCUPATION', 'TEL', 'EMAIL', 'ADDRESS', 'PASSPORT_URL', 'NATIONALITY', 'PASSPORT_NO', 'PREVIOUS_PLACE', 'NEXT_DESTINATION')->where('MEMBERS_ID', $request->get('MEMBERS_ID'))->get()->toArray();

    //     // accompany_id is incremented according to the number of people.
    //     $accompany_id = '001';
    //     $csv_name = $CItime->format('Ymd') . '__' .  $room_id->ROOM_ID . '__' . $room_name->ROOM_NAME . '__' . $accompany_id . '__card.csv';
    //     $path = app_path() . '\storeImage\\' . $csv_name;

    //     $this->putCsv($data, $csv_name, $path);

    //     return true;
    // }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> csv Put <br>
     * <Function> write csv of identification cards. <br>
     *
     * @param $data, $name
     * @return
     */
    // function putCsv($data, $name)
    // {
    //     // TODO:Need to modify the path to relative on GCP
    //     $res = fopen("C:\\xampp\\htdocs\\iBMSfH-withStaysee\\storage\\app\\storeImage\\" . $name, 'w+');

    //     $header = ["名前", "性別", "年齢", "職業", "電話番号", "メールアドレス", "住所", "身分証明書", "国籍", "旅券番号", "前宿泊地", "次宿泊地"];
    //     mb_convert_variables('SJIS', 'UTF-8', $header);
    //     fputcsv($res, $header);

    //     foreach ($data as $dataInfo) {
    //         mb_convert_variables('SJIS', 'UTF-8', $dataInfo);
    //         fputcsv($res, $dataInfo);
    //     }
    //     fclose($res);
    // }
}
