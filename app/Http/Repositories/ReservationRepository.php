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

    public function findUsersByDateAndTime(String $date, String $time)
    {
        $reservationList = findByDateAndTime($date, $time);
        $reservation = Reservation::findOrFail($reservationList[0]->id);

        return $reservation->users()->get();
    }

    public function findUsersForReservation($reservation)
    {
        return $reservation->users()->get();
    }
}
