@extends('layouts.checkin')
@section('content')
{{-- = SPRINT_08 TASK149 --}}
    {{-- <management userid="{{ auth()->id() }}"></management> --}}
    <management v-bind:user-id="{{ auth()->id() }}"></management>
    {{-- = SPRINT_08 TASK149 --}}
@endsection
