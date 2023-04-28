@extends('layouts.emails.thankyou')

@section('jp_beggining')
<p>
    <div style="
            width: 250px !important;
            margin: auto;
            background-color: #263033;
            border-radius: 15px;
            border-color: #263033;
            position: relative;
            margin-top: 40px;">
        <h1 style="
            text-align: center !important;
            width: 100% !important;
            color: #fff;
            padding: 20px 0;
            position: relative;">
            日本語案内
        </h1>
    </div>

    <div style="
        width: 500px;
        margin: auto;
        background-color: #add8e6;
        text-align: left;
        padding: 25px 20px;
        margin-top: 40px;
        font-size: 22px;">
        本メールは送信専用です。<br>
        本メールに返信しないようお願いいたします。
    </div>

    <div style="
        width: 300px;
        margin: auto;
        margin-top: 50px;">
        <img src="{{asset('img/mail/iBMSLogo_Black.png')}}" style="width: 100%;">
    </div>

    <div style="
        width: 500px;
        margin: auto;
        margin-top: 100px;
        border-bottom: 2px solid #000;
        padding-bottom: 20px;
        text-align: center;
        font-size: 32px;">
        {{ config('app.client.name') }}
    </div>
@endsection

@section('jp_reservation')

    <div style="width: 500px;
                margin: auto;
                margin-top: 50px;
                border-bottom: 2px solid #000;
                padding-bottom: 20px;
                text-align: center;
                font-size: 30px;">
        ご利用ありがとうございました
    </div>

    <div style="width: 500px;
                margin: auto;
                margin-top: 50px;
                padding-left: 40px;
                text-align: left;
                font-size: 22px;">
        {{$ibmsBooking['FIRST_NAME']}} 様 <br>
            {!! nl2br($param['MAIL_THANKYOU_JA_CONTENT']) !!}
        {{-- {{Str::replaceArray('\n', '<br>', $param['MAIL_THANKYOU_JA_CONTENT'])}} --}}
        {{-- チェックアウトが完了いたしました。<br>
        ご宿泊いただき誠にありがとうございました。<br>
        またのご利用を心よりお待ちしております。 --}}
    </div>

@endsection

@section('jp_note')
    <div style="width: 500px;
                margin: auto;
                margin-top: 50px;
                margin-bottom: 60px;
                padding-left: 40px;
                padding-top: 20px;
                border-top: 2px solid #000;
                font-size: 24px;
                border-color: #FFC300;
                background-color: #FFC300;">
        ・ 追加オーダーされた方は、フロントにてご精算を<br>
           お願いいたします。<br>
        ・ 退出後に忘れ物に気づかれた場合、<br>
    　     チェックアウト時刻までは、スマートロックを開錠し、<br>
    　     入室することができます。<br>
    　     チェックアウト時刻を過ぎている場合は、<br>
    　     フロントまでお越しいただくようお願いいたします。
    </div>
@endsection

{{-- @section('jp_signiture')
{{ config('app.client.name') }}<br>
<br>
-------------------------------------------------------------------------------------<br>
※このメールはシステムから自動送信されています。<br>
本メールに返信しないでください。<br>
</p>
@endsection --}}



@section('en_beggining')
<p>
    <div style="
            width: 350px !important;
            margin: auto;
            background-color: #263033;
            border-radius: 15px;
            border-color: #263033;
            position: relative;
            margin-top: 40px;">
        <h1 style="
            text-align: center !important;
            width: 100% !important;
            color: #fff;
            padding: 20px 0;
            position: relative;">
            English Information
        </h1>
    </div>

    <div style="
        width: 500px;
        margin: auto;
        background-color: #add8e6;
        text-align: left;
        padding: 25px 20px;
        margin-top: 40px;
        font-size: 22px;">
        This email was sent from a send-only address<br>
        Please do not reply to this email.
    </div>

    <div style="
        width: 300px;
        margin: auto;
        margin-top: 50px;">
        <img src="{{asset('img/mail/iBMSLogo_Black.png')}}" style="width: 100%;">
    </div>

    <div style="
        width: 500px;
        margin: auto;
        margin-top: 100px;
        border-bottom: 2px solid #000;
        padding-bottom: 20px;
        text-align: center;
        font-size: 32px;">
        {{ config('app.client.name') }}
    </div>
@endsection

@section('en_reservation')

        <div style="width: 500px;
                margin: auto;
                margin-top: 25px;
                border-bottom: 2px solid #000;
                padding-bottom: 20px;
                text-align: center;
                font-size: 30px;">
            Thank you for visiting.
        </div>

        <div style="width: 500px;
                    margin: auto;
                    margin-top: 25px;
                    padding-left: 40px;
                    text-align: left;
                    font-size: 22px;">
            Dear Mr./Ms. {{$ibmsBooking['FIRST_NAME']}}<br>
            {!! nl2br($param['MAIL_THANKYOU_EN_CONTENT']) !!}
            {{-- {{Str::replaceArray('\n', '<br>', $param['MAIL_THANKYOU_EN_CONTENT'])}} --}}
            {{-- Checkout has been completed.<br>
            Thank you very much for staying with us.<br>
            We look forward to welcoming you again soon. --}}
        </div>

@endsection

@section('en_note')
    <div style="width: 500px;
                margin: auto;
                margin-top: 25px;
                margin-bottom: 60px;
                padding-left: 40px;
                padding-top: 20px;
                border-top: 2px solid #000;
                font-size: 24px;
                border-color: #FFC300;
                background-color: #FFC300;">
        ・ Please pay for any additional orders<br>
           at the front desk<br>
        ・ If you notice that you have left something<br>
    　     after you have left the room, you can open the smart lock<br>
    　     and enter the room until check-out time.<br>
    　     If it is past the check-out time,<br>
    　     please come to the front desk.
    </div>
@endsection

{{-- @section('en_signiture')
    <br>
    Sincerly,<br>
    {{ config('app.client.name') }}<br>
    <br>
    -------------------------------------------------------------------------------------<br>
    *This email is sent automatically from the system.<br>
    Please do not reply to this email.<br>
</p>
@endsection --}}
