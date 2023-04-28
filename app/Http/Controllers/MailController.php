<?php
// <System Name> iBMS
// <Program Name> MailController.php
// <Create> 2020.03.17 TP Harvey
// <Update>

namespace App\Http\Controllers;

use App\Mail\ClientMail;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // sendClientMail                   Send email from client to Tensei Developer


    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name>  sendClientMail
     * <Function> Send email from client to Tensei Developer
     *            URL: http://localhost/Aapi/sendClientMail<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string
     * @throws Throwable When an exception occurs in this process
     */
    public function sendClientMail(Request $request)
    {

        try {
            //send email instruction
            $sEmailAddSender    = env('MAIL_FROM_ADDRESS');
            $sEmailAdd          = env('MAIL_TO_ADDRESS');
            $sEmailSubj         = "❗❗ iBMS Client Concern ❗❗";
            $body               = $request->body;

            $data = [
                'emailAdd' => $sEmailAddSender,
                'subject'   => $sEmailSubj,
                'body'      => $body
            ];

            Mail::to($sEmailAdd)->send(new ClientMail($data));
            return "success";
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }
}
