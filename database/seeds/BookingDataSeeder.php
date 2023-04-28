<?php

use App\Models\Book_Room;
use App\Models\BookPMS;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $booking = factory(BookPMS::class)->create();

        $user = factory(User::class)->create();

        $room = Room::all()->random();

        $bookingRooms = factory(Book_Room::class)->create([
            'BOOK_ID' => $booking->BOOK_ID,
            'USER_ID' => $user->USER_ID,
            'ROOM_ID' => $room->ROOM_ID
        ]);
    }
}
