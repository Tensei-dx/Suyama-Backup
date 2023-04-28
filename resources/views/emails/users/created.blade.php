<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
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
            font-size: 22px;
            font-weight:bold;">
        本メールは送信専用です。<br>
        本メールに返信しないようお願いいたします。
    </div>

    <div style="
            width: 300px;
            margin: auto;
            margin-top: 50px;">

        <img src="{{ asset('img/mail/iBMSLogo_Black.png') }}" style="width: 100%;">

    </div>

    <div style="width: 500px;
                margin: auto;
                margin-top: 50px;
                border-top: 2px solid #000;
                border-bottom: 2px solid #000;
                padding-top: 30px;
                padding-bottom: 30px;
                text-align: center;
                font-size: 30px;">
        管理者アカウントが作成されました
    </div>

    <div style="width: 500px;
                margin: auto;
                margin-top: 50px;
                padding-left: 40px;
                text-align: left;
                font-size: 22px;">
        {{ $user['FIRST_NAME']}} 様 <br>
        管理者アカウントが作成されました。<br>
        アカウント情報をお送りいたします。
    </div>

    <div style="width: 500px;
                    margin: auto;
                    margin-top: 50px;
                    padding-left: 40px;
                    padding-bottom: 70px;
                    text-align: left;
                    border-bottom: 2px solid #000;
                    font-size: 22px;">
            <div style="font-weight: bold;
                        margin-top: 30px;">
                ユーザ名
            </div>
            <div>
                {{ $user['USERNAME'] }}
            </div>

            <div style="font-weight: bold;
                        margin-top: 30px;">
                PINコード
            </div>
            <div>
                {{ $data['attributes']['pin'] }}
            </div>
    </div>

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
                font-size: 22px;
                font-weight:bold;">
            This email was sent from a send-only address.<br>
            Please do not reply to this email.
    </div>

    <div style="
            width: 300px;
            margin: auto;
            margin-top: 50px;">

        <img src="{{ asset('img/mail/iBMSLogo_Black.png') }}" style="width: 100%;">

    </div>

    <div style="width: 500px;
                margin: auto;
                margin-top: 50px;
                border-top: 2px solid #000;
                border-bottom: 2px solid #000;
                padding-top: 30px;
                padding-bottom: 30px;
                text-align: center;
                font-size: 30px;">
        Admin account have been created
    </div>

    <div style="width: 500px;
                margin: auto;
                margin-top: 50px;
                padding-left: 40px;
                text-align: left;
                font-size: 22px;">
        Dear Mr./Ms. {{ $user['FIRST_NAME']}}<br>
        Your admin account have been created.<br>
        Please check your account information
    </div>

    <div style="width: 500px;
                    margin: auto;
                    margin-top: 50px;
                    padding-left: 40px;
                    padding-bottom: 70px;
                    text-align: left;
                    border-bottom: 2px solid #000;
                    font-size: 22px;">
            <div style="font-weight: bold;
                        margin-top: 30px;">
                Username
            </div>
            <div>
                {{ $user['USERNAME'] }}
            </div>

            <div style="font-weight: bold;
                        margin-top: 30px;">
                PIN Code
            </div>
            <div>
                {{ $data['attributes']['pin'] }}
            </div>
    </div>
</body>
</html>
