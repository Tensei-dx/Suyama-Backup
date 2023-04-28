@extends('layouts.checkin')
@section('content')
    <welcome :locale="locale" :userid="{{ auth()->user()->USER_ID }}"></welcome>
@endsection