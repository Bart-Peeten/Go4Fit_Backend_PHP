<?php


namespace App\Http\Repositories;


use App\Reservation;
use Illuminate\Support\Facades\DB;

class ReservationRepository
{
    public function findByDateAndTime(String $date, String $time)
    {
        $reservation = DB::table('reservations')
            ->where('date', $date)
            ->where('time', $time)
            ->get();

        return $reservation;
    }
}
