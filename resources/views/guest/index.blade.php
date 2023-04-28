@extends('layouts.checkin')
@section('content')
    <guest :locale="locale" :userid="{{ auth()->user()->USER_ID }}"></guest>
@endsection