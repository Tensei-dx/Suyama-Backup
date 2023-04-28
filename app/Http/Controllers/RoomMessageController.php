<?php

namespace App\Http\Controllers;

use App\Models\RoomMessage;
use Illuminate\Http\Request;

class RoomMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roomMessages = RoomMessage::all();
        return response($roomMessages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RoomMessage  $roomMessage
     * @return \Illuminate\Http\Response
     */
    public function show(RoomMessage $roomMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RoomMessage  $roomMessage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RoomMessage $roomMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RoomMessage  $roomMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoomMessage $roomMessage)
    {
        //
    }
}
