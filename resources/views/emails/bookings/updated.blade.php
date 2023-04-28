@extends('layouts.emails.updated')

@section('jp_beggining')
{{-- <h3>{{ $ibmsBooking['FIRST_NAME'] }} 様</h3>
    <p>
    お客様のご予約情報が更新されましたので通知いたします。<br>
    <br>
    お客様のご予約情報は以下のとおりです。<br>
    <br> --}}
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

    {{-- 予約番号                ： {{ $ibmsBooking['BOOK_ID'] }}<br>
    お名前                  ： {{ $ibmsBooking['FIRST_NAME'] }}<br>
    人数    大人            ： {{ $pmsBooking['adult_number'] }} 人<br>
            子ども          ： {{ $pmsBooking['child_number'] }} 人<br>
    <br>
    @foreach ($ibmsBooking->bookingRoomAndUser as $bookRoom)
    部屋名                  ： {{ $bookRoom['room']['ROOM_NAME'] }}<br>
    チェックイン日時        ： {{ $bookRoom['CHECK_IN_TIME'] }}<br>
    チェックアウト日時      ： {{ $bookRoom['CHECK_OUT_TIME'] }}<br>
    PINコード               ： {{ $bookRoom['PIN'] }}<br>
    @endforeach --}}
    <div style="width: 500px;
                margin: auto;
                margin-top: 25px;
                border-bottom: 2px solid #000;
                padding-bottom: 20px;
                text-align: center;
                font-size: 30px;">
        予約内容変更のご確認
    </div>

    <div style="width: 500px;
                margin: auto;
                margin-top: 25px;
                padding-left: 40px;
                text-align: left;
                font-size: 22px;">
        {{$ibmsBooking['FIRST_NAME']}} 様 <br>
        ご予約された内容の変更が完了いたしました。<br>
        変更後のご予約内容をお送りいたします。
    </div>

    @foreach ($ibmsBooking->bookingRoomAndUser as $bookRoom)
        <div style="width: 500px;
                margin: auto;
                margin-top: 25px;
                padding-left: 40px;
                text-align: left;
                font-size: 22px;">
            <div style="font-weight: bold;
                        margin-top: 30px;">
                予約番号
            </div>
            <div>
                {{ $ibmsBooking['BOOK_ID'] }}
            </div>

            <div style="font-weight: bold;
                        margin-top: 30px;">
                人数
            </div>
            <div>
                {{ $pmsBooking['adult_number'] + $pmsBooking['child_number'] }}
            </div>

            <div style="font-weight: bold;
                        margin-top: 30px;">
                チェックイン日
            </div>
            <div>
                {{ $bookRoom['CHECK_IN_TIME'] }}
            </div>

            <div style="font-weight: bold;
                        margin-top: 30px;">
                チェックアウト日
            </div>
            <div>
                {{ $bookRoom['CHECK_OUT_TIME'] }}
            </div>
        </div>

        <div style="width: 500px;
                    margin: auto;
                    margin-top: 25px;
                    padding-left: 40px;
                    border-radius: 20px;
                    border-color: #FFC300;
                    background-color: #FFC300;
                    padding-bottom: 30px;
                    font-size: 26px;">
            <div style="font-weight: bold;
                        padding-top: 40px;">
                部屋名
            </div>
            <div style="padding-top: 20px;">
                {{ $bookRoom['room']['ROOM_NAME'] }}
            </div>
            <div style="font-weight: bold;
                        padding-top: 40px;">
                シリアルID
            </div>
            <div style="padding-top: 20px;">
                {{ $bookRoom['user']['USERNAME'] }}
            </div>
            <div style="font-weight: bold;
                        padding-top: 40px;">
                PINコード
            </div>
            <div style="padding-top: 20px;">
                {{ $bookRoom['PIN'] }}
            </div>
        </div>
    @endforeach

@endsection

@section('jp_note')
    {{-- <br>
    当日、ご到着されましたら上記PINコードをキーパネルにご入力ください。<br>
    PINコードを入力後鍵を解錠いただけます。<br>
    PINコードはお客様の滞在時のみ有効になります。<br>
    ※稀に正しいPINコードを入力した場合に、解除できない場合がございます。<br>
    10秒程度間隔を空け再度ご入力ください。<br>
    <br>
    チェックイン時間前、およびチェックアウト時間後はお使いいただけませんので、<br>
    ご了承宜しくお願い申し上げます。<br>
    <br> --}}
    <div style="width: 500px;
                margin: auto;
                margin-top: 25px;
                margin-bottom: 60px;
                padding-left: 40px;
                padding-top: 20px;
                border-top: 2px solid #000;
                font-size: 24px;">
        <div style="margin-bottom: 20px;">
            【開錠方法に関して】<br><br>

            当日、ご到着されましたら、上記PINコードをキーパネル(下記の画像参照)にご入力ください。<br>
            PINコードを入力後、✓ボタンを押すと、開錠いただけます。<br>
            PINコードは、お客様の滞在期間のみ有効です。<br>
            施錠の際は、✓ボタンを押してください。
        </div>
        <div>
            <img src="{{asset('img/mail/remotelock8j.png')}}" style="width: 420px;
                                                                            margin: auto;
                                                                            margin-bottom: 20px;">
        </div>
        <div style="margin-bottom: 20px;">
            正しいPINコードを入力した場合でも、稀に開錠できない場合がございます。<br>
            開錠できなかった場合は、10秒程間隔を開けてから再度ご入力ください。<br>
            キーパネルに数字が表示されない場合は、キーパネルに一度触れてください。
        </div>
    </div>
@endsection

{{-- @section('jp_signiture')
当日はごゆっくりおくつろぎください。<br>
{{ config('app.client.name') }}<br>
<br>
-------------------------------------------------------------------------------------<br>
※このメールはシステムから自動送信されています。<br>
本メールに返信しないでください。<br>
</p>
@endsection --}}




@section('en_beggining')
{{-- <h4>【English】</h4>
    <h3>Dear Mr/Mrs {{ $ibmsBooking['FIRST_NAME'] }}</h3>
    <p>
    We would like to notify you that your reservation information has been updated.<br>
    <br>
    Your reservation information is as follows;<br>
    <br> --}}
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

    {{-- Reservation Number       : {{ $ibmsBooking['BOOK_ID'] }}<br>
    Name                     : {{ $ibmsBooking['FIRST_NAME'] }}<br>
    Number of person Adult   : {{ $pmsBooking['adult_number'] }} person<br>
                     Child   : {{ $pmsBooking['child_number'] }} person<br>
    <br>
    <br>
    @foreach ($ibmsBooking->bookingRoomAndUser as $bookRoom)
    Room name                : {{ $bookRoom['room']['ROOM_NAME'] }}<br>
    Check-in date and time   : {{ $bookRoom['CHECK_IN_TIME'] }}<br>
    Check-out date and time  : {{ $bookRoom['CHECK_OUT_TIME'] }}<br>
    PIN code                 : {{ $bookRoom['PIN'] }}<br>
    @endforeach --}}
    <div style="width: 500px;
                margin: auto;
                margin-top: 25px;
                border-bottom: 2px solid #000;
                padding-bottom: 20px;
                text-align: center;
                font-size: 30px;">
        Confirmation of changes in reservation details
    </div>

    <div style="width: 500px;
                margin: auto;
                margin-top: 25px;
                padding-left: 40px;
                text-align: left;
                font-size: 22px;">
        Dear Mr./Ms. {{$ibmsBooking['FIRST_NAME']}}<br>
        We have completed the changes to your reservations.<br>
        We will send the changed reservation details.
    </div>

    @foreach ($ibmsBooking->bookingRoomAndUser as $bookRoom)
        <div style="width: 500px;
                    margin: auto;
                    margin-top: 25px;
                    padding-left: 40px;
                    text-align: left;
                    font-size: 22px;">
            <div style="font-weight: bold;
                        margin-top: 30px;">
                Reservation Number
            </div>
            <div>
                {{ $ibmsBooking['BOOK_ID'] }}
            </div>

            <div style="font-weight: bold;
                        margin-top: 30px;">
                Number of People
            </div>
            <div>
                {{ $pmsBooking['adult_number'] + $pmsBooking['child_number'] }}
            </div>

            <div style="font-weight: bold;
                        margin-top: 30px;">
                Check-in date
            </div>
            <div>
                {{ $bookRoom['CHECK_IN_TIME'] }}
            </div>

            <div style="font-weight: bold;
                        margin-top: 30px;">
                Check-out date
            </div>
            <div>
                {{ $bookRoom['CHECK_OUT_TIME'] }}
            </div>
        </div>

        <div style="width: 500px;
                    margin: auto;
                    margin-top: 25px;
                    padding-left: 40px;
                    border-radius: 20px;
                    border-color: #FFC300;
                    background-color: #FFC300;
                    padding-bottom: 30px;
                    font-size: 26px;">
            <div style="font-weight: bold;
                        padding-top: 40px;">
                Room name
            </div>
            <div style="padding-top: 20px;">
                {{ $bookRoom['room']['ROOM_NAME'] }}
            </div>
            <div style="font-weight: bold;
                        padding-top: 40px;">
                Serial ID
            </div>
            <div style="padding-top: 20px;">
                {{ $bookRoom['user']['USERNAME'] }}
            </div>
            <div style="font-weight: bold;
                        padding-top: 40px;">
                PIN code
            </div>
            <div style="padding-top: 20px;">
                {{ $bookRoom['PIN'] }}
            </div>
        </div>
    @endforeach

@endsection

@section('en_note')
    {{-- <br>
    When you arrive, please enter the above PIN code into the key panel.<br>
    After entering the PIN code, you will be able to unlock the door.<br>
    The PIN code will only be valid for the duration of your stay.<br>
    *In rare cases, if you enter the correct PIN code, you may not be able to unlock your account. <br>
    Please wait for about 10 seconds and enter the code again. <br>
    <br>
    Please note that the PIN cannot be used before check-in time or after check-out time. <br>
    We hope you can make yourself at home with our accommodation and hospitality.<br>
    <br> --}}
    <div style="width: 500px;
                margin: auto;
                margin-top: 25px;
                margin-bottom: 60px;
                padding-left: 40px;
                padding-top: 20px;
                border-top: 2px solid #000;
                font-size: 24px;">
        <div style="margin-bottom: 20px;">
            【How to unlock the key】<br><br>

            When you arrive, please enter the above PIN code into the key panel(see the following image).<br>
            After entering the PIN code, press the ✓ button to unlock.<br>
            The PIN code will only be valid for the duration of your stay.<br>
            When locking, please press the ✓ button.
        </div>
        <div>
            <img src="{{asset('img/mail/remotelock8j.png')}}" style="width: 420px;
                                                                            margin: auto;
                                                                            margin-bottom: 20px;">
        </div>
        <div style="margin-bottom: 20px;">
            It is rare that the lock cannot be opened even if you enter the correct PIN code.<br>
            In that case, please wait for about 10 seconds and enter the PIN code again.<br>
            If the numbers do not appear on the key panel, touch the key panel once.
        </div>
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
