<?php


namespace App\Http\Repositories;


use App\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationRepository
{
    public function findByDateAndTime(Carbon $date, Carbon $time)
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

    public function findUsersForReservation(Reservation $reservation)
    {
        return $reservation->users()->get();
    }

    public function findDeletedUsersForReservation(Reservation $reservation)
    {
        return $reservation->deletedUsers()->get();
    }
}
