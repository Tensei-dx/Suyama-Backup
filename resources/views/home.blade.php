@extends('layouts.app')
@section('content')
<dashboard :locale="locale" :userid="{{ auth()->user()->USER_ID }}"></dashboard>
@endsection
