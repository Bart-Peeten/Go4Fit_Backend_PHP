<?php

namespace App\Http\Services;

use App\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    public function add(Request $request)
    {
        $result = null;
        $reservations = $this->findByDateAndTime($request->date, $request->time);
        if ($reservations->count() == 0) {
            $result = Reservation::create($request->all());
        }

        return $result;
    }

    public function getReservationsByDateAndTime(Request $request)
    {
        $reservation = $this->findByDateAndTime($request->date, $request->time);
        $result = Reservation::find($reservation->id)->users;

        return $result;
    }

    private function findByDateAndTime(String $date, String $time)
    {
        $reservation = DB::table('reservations')
            ->where('date', $date)
            ->where('time', $time)
            ->get();

        return $reservation;
    }

}
