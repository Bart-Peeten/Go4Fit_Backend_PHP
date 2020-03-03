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

    public function getReservationsByDateAndTime(Request $request)
    {
        $fullNames = array();
        $reservationList = $this->reservationRepository->findByDateAndTime($request->date, $request->time);
        $reservation = Reservation::findOrFail($reservationList[0]->id);
        $result = Reservation::find($reservation->id)->users;
        for ($i = 0; $i < count($result); $i++) {
            $fullName = $result[$i]->firstname." ".$result[$i]->name;
            array_push($fullNames, $fullName);
        }

        return $fullNames;
    }

    public function findUsersForGivenWeek(Request $request)
    {
        $timeSlotsInWeek = $this->fillTimes();
        $dates = array();
        $names = null;
        array_push($dates, $request->tuesday);
        array_push($dates, $request->wednesday);
        array_push($dates, $request->thursday);
        array_push($dates, $request->sunday);
        $resultArray = array();
        for ($i = 0; $i < count($dates); $i++) {
            for ($j = 0; $j < count($timeSlotsInWeek[$i]); $j++) {
                $result = $this->reservationRepository->findByDateAndTime($dates[$j], $timeSlotsInWeek[$i][$j]);
                $names = $this->findNamesForReservation($result);
                array_push($resultArray, $names);
            }
        }

        return $resultArray;
    }

    public function addNewReservation(Request $request)
    {
        // First check if the reservation exist.
        $result = null;
        $reservationList = $this->reservationRepository->findByDateAndTime($request->date, $request->time);
        // Then get the user by email, to get the user id.
        $user = $this->userService->findByEmail($request->email);

        return $this->addUserToReservation($request, $reservationList, $user);
    }

    public function addNewReservationByFullName(Request $request)
    {
        $result = null;
        // First check if the reservation exist.
        $reservationList = $this->reservationRepository->findByDateAndTime($request->date, $request->time);
        // Query for the user by his first and lastname.
        $user = $this->userService->findByFirstAndLastname($request->firstname, $request->lastname);

        $reservation = Reservation::findOrFail($reservationList[0]->id);
        $usersForReservation = $this->reservationRepository->findUsersForReservation($reservation);

        foreach ($usersForReservation as $item) {
            if ($item->email == $user->email) {
                return [];
            }
        }

        return $this->addUserToReservation($request, $reservationList, $user);

    }

    public function deleteReservation(Request $request)
    {
        // First get the users id.
        $user = $this->userService->findByFirstAndLastname($request->firstname, $request->name);
        // find the reservation by date and time.
        $reservationList = $this->reservationRepository->findByDateAndTime($request->date, $request->time);
        $reservation = Reservation::findOrFail($reservationList[0]->id);

        if ($request->isAllowed == "true") {
            // The following code needs in a transaction as we will delete data.
            DB::transaction(function () use ($reservation, $user) {
                // Detach the user from the reservation.
                $reservation->users()->detach($user->id);
            });
        } else {
            // The following code needs in a transaction as we will delete data.
            DB::transaction(function () use ($reservation, $user) {
                // Detach the user from the reservation.
                $reservation->users()->detach($user->id);
                $reservation->deletedUsers()->attach($user->id);
            });
        }
//        return $user->id;
    }

    /**
     * @param Request $request
     * @param \Illuminate\Support\Collection $reservationList
     * @param \App\User $user
     * @return mixed
     */
    private function addUserToReservation(Request $request, \Illuminate\Support\Collection $reservationList, \App\User $user)
    {
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

    private function findNamesForReservation(\Illuminate\Support\Collection $reservationList)
    {
        $usersList = array();
        $users = null;
        if (count($reservationList) > 0) {
            $reservation = Reservation::findOrFail($reservationList[0]->id);
            $users = $this->reservationRepository->findUsersForReservation($reservation);
            if (count($users) > 0) {
                foreach ($users as $user) {
                    $fullName = $user->firstname." ".$user->name;
                    array_push($usersList, $fullName);
                }
            }
        }

        return $usersList;
    }

    private function fillTimes()
    {
        $tuesday = ["19:00", "20:00"];
        $wednesday = ["09:00", "19:00", "20:00"];
        $thursday = ["19:00"];
        $sunday = ["08:00", "09:00", "10:00"];

        return [$tuesday, $wednesday, $thursday, $sunday];
//        return [$tuesday, $wednesday];
    }
}
