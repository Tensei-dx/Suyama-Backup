@extends('layouts.emails.canceled')

@section('jp_beggining')
{{-- <h3>{{ $ibmsBooking['FIRST_NAME'] }} 様</h3>

    ご利用ありがとうございます。
    {{ config('app.client.name') }}の予約キャンセルを承りました。<br>
    <br>
    キャンセル内容をお知らせします。
    <br> --}}

    <div style="width: 250px !important;
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
            日本語案内
        </h1>
    </div>

    <div style="width: 500px;
                margin: auto;
                background-color: #add8e6;
                text-align: left;
                padding: 25px 20px;
                margin-top: 40px;
                font-size: 22px;">
        本メールは送信専用です。<br>
        本メールに返信しないようお願いいたします。
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
    @endforeach --}}
    <div style="width: 500px;
                margin: auto;
                margin-top: 25px;
                border-bottom: 2px solid #000;
                padding-bottom: 20px;
                text-align: center;
                font-size: 30px;">
        予約キャンセルのご確認
    </div>

    <div style="width: 500px;
                margin: auto;
                margin-top: 25px;
                padding-left: 40px;
                text-align: left;
                font-size: 22px;">
        {{$ibmsBooking['FIRST_NAME']}} 様 <br>
        ご利用ありがとうございます。<br>
        予約のキャンセルを承りました。<br><br>
        キャンセルされたご予約内容をお送りいたします。
    </div>

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
            {{ $pmsBooking['check_in_date_time'] }}
        </div>

        <div style="font-weight: bold;
                    margin-top: 30px;">
            チェックアウト日
        </div>
        <div>
            {{ $pmsBooking['check_out_date_time'] }}
        </div>
    </div>

    @foreach ($ibmsBooking->bookingRoomAndUser as $bookRoom)
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
    キャンセル料については、規約をご確認ください。<br>
    またのご予約をお待ちしております。<br>
    <br> --}}
    <div style="width: 500px;
                margin: auto;
                margin-top: 25px;
                margin-bottom: 60px;
                padding-left: 40px;
                padding-top: 20px;
                border-top: 2px solid #000;
                font-size: 24px;">
        キャンセル料につきましては、規約をご確認ください。<br>
        またのご利用をお待ちしております。
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
{{-- <h3>Dear Mr/Mrs {{ $ibmsBooking['FIRST_NAME'] }}</h3>

    Thank you for visiting.
    We have received a reservation cancellation for {{ config('app.client.name') }}<br>
    <br>
    We will inform you of the cancellation details.
    <br> --}}

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
        This email was sent from a send-only address<br>
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

    {{-- Reservation Number       : {{ $ibmsBooking['BOOK_ID'] }}<br>
    Name                     : {{ $ibmsBooking['FIRST_NAME'] }}<br>
    Number of person Adult   : {{ $pmsBooking['adult_number'] }} person<br>
                     Child   : {{ $pmsBooking['child_number'] }} person<br>
    <br>
    @foreach ($ibmsBooking->bookingRoomAndUser as $bookRoom)
    Room name                : {{ $bookRoom['room']['ROOM_NAME'] }}<br>
    Check-in date and time   : {{ $bookRoom['CHECK_IN_TIME'] }}<br>
    Check-out date and time  : {{ $bookRoom['CHECK_OUT_TIME'] }}<br>
    @endforeach --}}
    <div style="width: 500px;
                margin: auto;
                margin-top: 25px;
                border-bottom: 2px solid #000;
                padding-bottom: 20px;
                text-align: center;
                font-size: 30px;">
        Confirmation of reservation cancellation
    </div>

    <div style="width: 500px;
                margin: auto;
                margin-top: 25px;
                padding-left: 40px;
                text-align: left;
                font-size: 22px;">
        Dear Mr./Ms. {{$ibmsBooking['FIRST_NAME']}}<br>
        Thank you for visiting<br>
        Your reservation has been cancelled.<br><br>
        We will send you the details of the cancelled reservation.
    </div>

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
            {{ $pmsBooking['check_in_date_time'] }}
        </div>

        <div style="font-weight: bold;
                    margin-top: 30px;">
            Check-out date
        </div>
        <div>
            {{ $pmsBooking['check_out_date_time'] }}
        </div>
    </div>

    @foreach ($ibmsBooking->bookingRoomAndUser as $bookRoom)
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
    Please check the terms and conditions for cancellation fees.
    We look forward to seeing you again soon.
    <br> --}}
    <div style="width: 500px;
                margin: auto;
                margin-top: 25px;
                margin-bottom: 60px;
                padding-left: 40px;
                padding-top: 20px;
                border-top: 2px solid #000;
                font-size: 24px;">
        Please check the terms and conditions for cancellation fees.<br>
        We look forward to seeing you again soon.
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
