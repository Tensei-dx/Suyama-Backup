@extends('layouts.app')
@section('content')
	<deviceoperation :userid="{{ auth()->user()->USER_ID }}"></deviceoperation>
@endsection