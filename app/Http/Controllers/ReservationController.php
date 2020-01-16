<?php

namespace App\Http\Controllers;

use App\Reservation;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class ReservationController extends Controller
{
    /**
     * @var
     */
    protected $user;

    /**
     * ReservationController constructor
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
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
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'time' => 'required',
        ]);

        $reservation = new Reservation();
        $reservation->date = $request->date;
        $reservation->time = $request->time;

        if ($this->user->reservation()->save($task))
            return response()->json([
                'success' => true,
                'task' => $task
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task could not be added.'
            ], 500);
    }
}
