<?php

namespace App\Http\Services;

use App\Http\Repositories\ReservationRepository;
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
     * @var
     */
    private $reservationRepository;

    /**
     * ReservationService constructor.
     */
    public function __construct(UserService $userService,
                                ReservationRepository $reservationRepository)
    {
        $this->userService = $userService;
        $this->reservationRepository = $reservationRepository;
    }

    public function getReservationsByDateAndTime(Request $request): Reservation
    {
        $reservation = $this->reservationRepository->findByDateAndTime($request->date, $request->time);
        $result = Reservation::find($reservation->id)->users;

        return $result;
    }

    public function findUsersForGivenWeek(Request $request)
    {
        $index = 0;
        $timeSlotsInWeek = $this->fillTimes();
        $dates = array();
        array_push($dates, $request->tuesday);
        array_push($dates, $request->wednesday);
        array_push($dates, $request->thursday);
        array_push($dates, $request->sunday);

        foreach ($dates as $date) {
            for ($i = 0; $i < count($timeSlotsInWeek); $i++) {
                $result = $this->reservationRepository->findByDateAndTime($date, $timeSlotsInWeek[$index][$i]);
            }
        }
    }

    public function addNewReservation(Request $request)
    {
        // First check if the reservation exist.
        $result = null;
        $reservationList = $this->reservationRepository->findByDateAndTime($request->date, $request->time);
        // Then get the user by email, to get the user id.
        $user = $this->userService->findByEmail($request->email);

        if ($reservationList->count() == 0) {
            // The following code needs in a transaction as we will create or update data.
            $result = DB::transaction(function () use ($request, $reservationList, $user) {
                // If there are no reservations, create this reservation.
                $reservation = Reservation::create($request->all());
                return $reservation->users()->attach($user->id);
            });
        } else {
            $result = DB::transaction(function () use ($request, $reservationList, $user) {
                $reservation = Reservation::findOrFail($reservationList[0]->id);
                return $reservation->users()->attach($user->id);
            });
        }

        return $result;
    }

    public function addNewReservationByFullName(Request $request): Reservation
    {
        $result = null;
        // First check if the reservation exist.
        $reservation = $this->reservationRepository->findByDateAndTime($request->date, $request->time);
        // Query for the user by his first and lastname.
        $userId = $this->userService->findByFirstAndLastname($request->firstname, $request->lastname);

        // The following code needs in a transaction as we will create or update data.
        $result = DB::transaction(function () use ($request, $reservation, $userId) {
            // If there are no reservations, create this reservation.
            if ($reservation->count() == 0) {
                $reservation = Reservation::create($request->all());
            }
            // Add the user with his id to the array of the Reservation.
            $result = $reservation->users()->attach($userId->id);
        });

        return $result;
    }

    public function deleteReservation(Request $request): void
    {
        // First get the users id.
        $userId = $this->userService->findUserIdByEmail($request->email);
        // find the reservation by date and time.
        $reservation = $this->reservationRepository->findByDateAndTime($request->date, $request->time);

        // The following code needs in a transaction as we will delete data.
        DB::transaction(function () use ($reservation, $userId) {
            // Detach the user from the reservation.
            $reservation->users()->detach($userId);
        });
    }

    private function fillTimes()
    {
        $tuesday = ["19:00", "20:00"];
        $wednesday = ["09:00", "19:00", "20:00"];
        $thursday = ["19:00"];
        $sunday = ["08:00", "09:00", "10:00"];

        return [$tuesday, $wednesday, $thursday, $sunday];
    }
}
