{{--
    <System Name> iBMS
    <Program Name> index.blade.php
    <Create>        TDN Okada
    <Update>
--}}
@extends('layouts.management')
@section('content')
    <update-account v-bind:update-Data="{{ $updateData }}"></update-account>
@endsection
