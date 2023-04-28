@extends('layouts.emails.remind')

@section('jp_beggining')
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
        予約内容のご確認
    </div>

    <div style="width: 500px;
                margin: auto;
                margin-top: 50px;
                padding-left: 40px;
                text-align: left;
                font-size: 22px;">
        ご予約の日付が近づきましたので、お知らせ申し上げます。<br>
        以下、ご予約時にお送りしたメールと同じ内容でございます。
    </div>

    <div style="width: 500px;
                margin: auto;
                margin-top: 50px;
                padding-left: 40px;
                text-align: left;
                font-size: 22px;">
        {{$ibmsBooking['FIRST_NAME']}} 様 <br>
        この度はご予約いただきまして、誠にありがとうございます。<br>
        ご予約された内容をお送りいたします。
    </div>

    @foreach ($ibmsBooking->bookingRoomAndUser as $bookRoom)
        <div style="width: 500px;
                    margin: auto;
                    margin-top: 50px;
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
                    margin-top: 50px;
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
    <div style="width: 500px;
                margin: auto;
                margin-top: 50px;
                margin-bottom: 60px;
                padding-left: 40px;
                padding-top: 20px;
                border-top: 2px solid #000;
                font-size: 24px;">
        <div style="margin-bottom: 20px;">
            【開錠方法に関して】<br><br>

            当日、ご到着されましたら、上記PINコードをキーパネル(下記の画像参照)にご入力ください。<br>
            PINコードを入力しますと、開錠いただけます。<br>
            PINコードは、お客様の滞在期間のみ有効です。
        </div>
        <div>
            <img src="{{asset('img/mail/remotelock8j.png')}}" style="width: 420px;
                                                                            margin: auto;
                                                                            margin-bottom: 20px;">
        </div>
        <div style="margin-bottom: 20px;">
            正しいPINコードを入力した場合でも、稀に開錠できない場合がございます。<br>
            開錠できなかった場合は、10秒程間隔を開けてから再度ご入力ください。
        </div>
    </div>
@endsection


@section('en_beggining')
    <div style="width: 350px !important;
                margin: auto;
                background-color: #263033;
                border-radius: 15px;
                border-color: #263033;
                position: relative;
                margin-top: 40px;">
        <h1 style="text-align: center !important;
                    width: 100% !important;
                    color: #fff;
                    padding: 20px 0;
                    position: relative;">
            English Information
        </h1>
    </div>

    <div style="width: 500px;
                margin: auto;
                background-color: #add8e6;
                text-align: left;
                padding: 25px 20px;
                margin-top: 40px;
                font-size: 22px;">
        This email was sent from a send-only address.<br>
        Please do not reply to this email.
    </div>

    <div style="width: 300px;
                margin: auto;
                margin-top: 50px;">
        <img src="{{asset('img/mail/iBMSLogo_Black.png')}}" style="width: 100%;">
    </div>

    <div style="width: 500px;
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
                margin-top: 50px;
                border-bottom: 2px solid #000;
                padding-bottom: 20px;
                text-align: center;
                font-size: 30px;">
        Accommodation information
    </div>

    <div style="width: 500px;
                margin: auto;
                margin-top: 50px;
                padding-left: 40px;
                text-align: left;
                font-size: 22px;">
        We would like to inform you that the date for your reservation is approaching.<br>
        The following is the same email that was sent to you when you made your reservation.
    </div>

    <div style="width: 500px;
                margin: auto;
                margin-top: 50px;
                padding-left: 40px;
                text-align: left;
                font-size: 22px;">
        Dear Mr./Ms. {{ $ibmsBooking['FIRST_NAME'] }}<br>
        Thank you very much for booking.<br>
        Please check the facilities and accommodation information.
    </div>

    @foreach ($ibmsBooking->bookingRoomAndUser as $bookRoom)
        <div style="width: 500px;
                    margin: auto;
                    margin-top: 50px;
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
                {{ $pmsBooking['adult_number'] + $pmsBooking['child_number']}}
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
                    margin-top: 50px;
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
    <div style="width: 500px;
                margin: auto;
                margin-top: 50px;
                margin-bottom: 60px;
                padding-left: 40px;
                padding-top: 20px;
                border-top: 2px solid #000;
                font-size: 24px;">
        <div style="margin-bottom: 20px;">
            【How to unlock the key】<br><br>

            When you arrive, please enter the above PIN code into the key panel(see the following image).<br>
            After entering the PIN code, you will be able to unlock the door.<br>
            The PIN code will only be valid for the duration of your stay.

        </div>
        <div>
            <img src="{{asset('img/mail/remotelock8j.png')}}" style="width: 420px;
                                                                            margin: auto;
                                                                            margin-bottom: 20px;">
        </div>
        <div style="margin-bottom: 20px;">
            It is rare that the lock cannot be opened even if you enter the correct PIN code.<br>
            In that case, please wait for about 10 seconds and enter the PIN code again.
        </div>
    </div>
@endsection

