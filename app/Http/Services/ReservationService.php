<?php

namespace App\Http\Services;

use App\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    /**
     * @var
     */
    private $userService;

    /**
     * ReservationService constructor.
     */
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function addNewReservation(Request $request): Reservation {
        // First check if the reservation exist.
        $result = null;
        $reservation = $this->findByDateAndTime($request->date, $request->time);
        // If there are no reservations, create this reservation.
        if ($reservation->count() == 0) {
            $reservation = Reservation::create($request->all());
        }
        // Now add the user to this reservation.
        // First get the user by email, to get the user id.
        $user = $this->userService->findByEmail($request->email);
        // Add the user with his id to the array of the Reservation.
        $result = $reservation->users()->attach($user->id);

        return $result;
    }

    public function addNewReservationByFullName(Request $request): Reservation {
        // First check if the reservation exist.
        $reservation = $this->findByDateAndTime($request->date, $request->time);
        // If there are no reservations, create this reservation.
        if ($reservation->count() == 0) {
            $reservation = Reservation::create($request->all());
        }
        // Query for the user by his first and lastname.
        $userId = $this->userService->findByFirstAndLastname($request->firstname, $request->lastname);
        // Add the user with his id to the array of the Reservation.
        $result = $reservation->users()->attach($userId->id);

        return $result;
    }

    public function getReservationsByDateAndTime(Request $request): Reservation {
        $reservation = $this->findByDateAndTime($request->date, $request->time);
        $result = Reservation::find($reservation->id)->users;

        return $result;
    }

    public function deleteReservation(Request $request): void {
        // First get the users id.
        $userId = $this->userService->findUserIdByEmail($request->email);
        // find the reservation by date and time.
        $reservation = $this->findByDateAndTime($request->date, $request->time);

        // Detach the user from the reservation.
        $reservation->users()->detach($userId);
    }

    public function findByDateAndTime(String $date, String $time): Reservation {
        $reservation = DB::table('reservations')
            ->where('date', $date)
            ->where('time', $time)
            ->get();

        return $reservation;
    }
}
