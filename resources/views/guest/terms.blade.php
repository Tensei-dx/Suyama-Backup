@extends('layouts.checkin')
@section('content')
    <terms :locale="locale" :userid="{{ auth()->user()->USER_ID }}"></terms>
@endsection