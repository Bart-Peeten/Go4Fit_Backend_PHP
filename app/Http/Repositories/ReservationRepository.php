<?php


namespace App\Http\Repositories;


use App\Reservation;

class ReservationRepository
{
    public function findByDateAndTime(String $date, String $time): Reservation
    {
        $reservation = DB::table('reservations')
            ->where('date', $date)
            ->where('time', $time)
            ->get();

        return $reservation;
    }
}
