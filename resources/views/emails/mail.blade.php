<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
    <h3>{{ $name }} 様 <small>※Followed by English translation</small> </h3>
    <p>
        この度はCARRY CUBEへのご予約誠にありがとうございます。<br>
        <br>
        お客様のご予約情報は以下のとおりです。<br>
        <br>
        <pre>
        予約番号　　　 　　　   ：0001<br>
        お名前　　　　　　　    ：{{ $name }}<br>
        人数　　　　　　　　    ：2人<br>
        部屋番号　　　　　　    ：{{ $room }}<br>
        チェックイン日時　　    ：{{ $checkin }}<br>
        チェックアウト日時　    ：{{ $checkout }}<br>
        ＰＩＮコード　　　　    : {{ $pin }}<br>
        </pre>
        <br>
        当日、ご到着されましたら上記PINコードをキーパネルにご入力ください。<br>
        PINコードを入力後鍵を解錠いただけます。<br>
        PINコードはお客様の滞在時のみ有効になります。<br>
        チェックイン時間前、およびチェックアウト時間後はお使いいただけませんので、<br>
        ご了承宜しくお願い申し上げます。<br>
        <br>
        当日はごゆっくりおくつろぎください。<br>
        CARRY CUBE<br>
        <br>
        -------------------------------------------------------------------------------------
    </p>
    <h4>【English】</h4>
    <h3>Dear Mr/Mrs {{ $name }}</h3>
    <p>
        Thank you very much for making a reservation at CARRY CUBE.<br>
        <br>
        Your reservation information is as follows;<br>
        <br>
        <pre>
        Reservation Number       : 0001<br>
        Name                     : {{ $name }}<br>
        Number of people         : 2 people<br>
        Room number              : {{ $room }}<br>
        Check-in date and time   : {{ $checkin }}<br>
        Check-out date and time  : {{ $checkout }}<br>
        PIN code                 : {{ $pin }}<br>
        </pre>
        <br>
        When you arrive, please enter the above PIN code into the key panel.<br>
        After entering the PIN code, you will be able to unlock the door.<br>
        The PIN code will only be valid for the duration of your stay.<br>
        Please note that the PIN cannot be used before check-in time or after check-out time.<br>
        <br>
        We hope you can make yourself at home with our accommodation and hospitality.<br>
        <br>
        Sincerly,<br>
        CARRY CUBE
    </p>
  </body>
</html>
