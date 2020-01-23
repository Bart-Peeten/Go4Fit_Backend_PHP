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
}
