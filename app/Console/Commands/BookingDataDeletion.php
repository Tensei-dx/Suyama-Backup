<?php

namespace App\Console\Commands;

use App\Models\Book_Room;
use App\Models\BookPMS;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BookingDataDeletion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:booking-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (BookPMS::all() as $book) {
            $book_rooms = Book_Room::where('BOOK_ID', $book->BOOK_ID)->where('CHECK_OUT_TIME', '<=', Carbon::now()->subDays((7)))->get();
            if (count($book_rooms) > 0) {
                foreach ($book_rooms as $book_room) {
                    BookPMS::where('BOOK_ID', $book_rooms[0]->BOOK_ID)->delete();
                    User::where('USER_ID', $book_room->USER_ID)->delete();
                    Book_Room::where('BOOK_ROOM_ID', $book_room->BOOK_ROOM_ID)->delete();
                }
            }
        }
    }
}
