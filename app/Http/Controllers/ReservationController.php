<?php

namespace App\Http\Controllers;

use App\Http\Services\ReservationService;
use App\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        return Reservation::all();
    }

    /**
     * @param Request $request
     * @return JsonResponse of list with participants
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getReservationsByDateAndTime(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|string',
            'time' => 'required|string'
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 200);
        }

        $participants = $this->service->getReservationsByDateAndTime($request);

        return response()->json($participants, 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse of list with participants
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getUsersForGivenWeek(Request $request)
    {
        $participantsList = $this->service->findUsersForGivenWeek($request);

        return response()->json($participantsList, 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse of list with numbers of participants.
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getNumberOfReservationsForGivenWeek(Request $request)
    {
        $numberOfReservations = $this->service->getNumbersOfReservationsForGivenWeek($request);

        return response()->json($numberOfReservations, 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse of list with participants
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getCancellationsForGivenWeek(Request $request)
    {
        $cancellations = $this->service->findCanceledUsersForGivenWeek($request);

        return response()->json($cancellations, 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse of list with booleans if participants is reserved.
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getIsParticipantReservedForGivenWeek(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required',
            'name' => 'required',
        ]);

        $isParticipantReserved = $this->service->getIsParticipantReservedForGivenWeek($request);

        return response()->json($isParticipantReserved, 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addNewReservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|string',
            'time' => 'required|string',
            'email' => 'required|email'
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 200);
        }

        $reservation = $this->service->addNewReservation($request);

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
            'firstname' => 'required',
            'lastname' => 'required',
            'date' => 'required',
            'time' => 'required',
        ]);

        $reservation = $this->service->addNewReservationByFullName($request);

        return response()->json($reservation, 201);
    }

    /**
     * @param Request $request
     * //     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletereservation(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'time' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'isAllowed' => 'required'
        ]);

        $reservation = $this->service->deleteReservation($request);

        return response()->json($reservation, 201);
    }
}
