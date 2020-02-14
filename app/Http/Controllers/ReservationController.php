<?php

namespace App\Http\Controllers;

use App\Http\Services\ReservationService;
use App\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class ReservationController extends Controller
{
    /**
     * @var
     */
    protected $user;

    /**
     * @var
     */
    private $service;

    /**
     * ReservationController constructor
     * @param ReservationService $reservationService
     */
    public function __construct(ReservationService $reservationService)
    {
        $this->service = $reservationService;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $reservations = Reservation::all();

        return $reservations;
    }

    /**
     * @param Request $request
     * @return JsonResponse of list with participants
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getReservationsByDateAndTime(Request $request)
    {
        $participants = [];
        return response()->json($participants, 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse of list with participants
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getUsersForGivenWeek(Request $request)
    {
        $participants = array();
        return response()->json($participants, 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse of list with participants
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getCancellationsForGivenWeek(Request $request)
    {
        $cancellations = array(array());
        return response()->json($cancellations, 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse of list with participants
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getNumberOfReservationsForGivenWeek(Request $request)
    {
        $numberOfReservations = array();
        return response()->json($numberOfReservations, 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse of list with participants
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getIsParticipantReservedForGivenWeek(Request $request)
    {
        $isParticipantReserved = array();
        return response()->json($isParticipantReserved, 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addNewReservation(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'time' => 'required',
        ]);

        $reservation = $this->service->add($request);

        return response()->json($reservation, 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addReservationWithOnlyFullName(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'time' => 'required',
        ]);

        $reservation = $this->service->add($request);

        return response()->json($reservation, 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletereservation(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'time' => 'required',
        ]);

        $reservation = $this->service->add($request);

        return response()->json($reservation, 201);
    }
}
