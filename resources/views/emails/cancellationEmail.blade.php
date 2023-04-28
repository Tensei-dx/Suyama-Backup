<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>

  <body>
    <h3>{{ $guest_name }} 様 <small>※Followed by English translation</small> </h3>
    <p>
        ご利用ありがとうございます。<br>
        CURRY CUBEの予約キャンセルを承りました。<br>
        <br>
        キャンセル内容をお知らせします。<br>
        <br>
        <pre>
        予約番号　　　　　　　　　　　　　　：0001<br>
        お名前　　　　　　　　　　　　　　　：{{ $guest_name }}<br>
        人数　　　　　　　　　　　　　　　　：2人<br>
        部屋番号　　　　　　　　　　　　　　：{{ $room }}<br>
        チェックイン日時　　　　　　　　　　：{{ $checkin }}<br>
        チェックアウト日時　　　　　　　　　：{{ $checkout }}<br>
        </pre>
        <br>
        キャンセル料については、規約をご確認ください。<br>
        またのご予約をお待ちしております。<br>
        <br>
        CARRY CUBE<br>
        <br>
      </p>
      <hr>
    <h4>【English】</h4>
    <h3>Dear Mr/Mrs {{ $guest_name }}</h3>
    <p>
        Your reservation for CARRY CUBE has been cancelled.<br>
        <br>
        Your reservation information is as follows:<br>
        <br>
        <pre>
        Reservation Number              : 0001<br>
        Name                            : {{ $guest_name }}<br>
        Number of people                : 2 people<br>
        Room number                     : {{ $room }}<br>
        Check-in date and time          : {{ $checkin }}<br>
        Check-out date and time         : {{ $checkout }}<br>
        </pre>
        <br>
        We appreciate your cancellation of your reservation for CARRY CUBE.<br>
        Please be noted to our cancellation policy.<br>
        <br>
        We look forward to welcoming you in the near future.<br>
        <br>
        Sincerely,<br>
        CARRY CUBE
    </p>
  </body>
</html>
