@extends('layouts.checkin')
@section('content')
    <roomselect :locale="locale" :userid="{{ auth()->user()->USER_ID }}"></roomselect>
@endsection
