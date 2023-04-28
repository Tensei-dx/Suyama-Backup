@extends('layouts.checkin')
@section('content')
    <guestinfo username="{{ $username }}"
                pin="{{ $pin }}"
                room_name="{{ $room_name }}"
                check_in="{{ $check_in }}"
                check_out="{{ $check_out }}">
    </guestinfo>
@endsection
