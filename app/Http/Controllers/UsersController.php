<?php

namespace App\Http\Controllers;

use App\Http\Services\ReservationService;
use App\Http\Services\UserService;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    /**
     * @var
     */
    private $service;

    /**
     * ReservationController constructor
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $users = User::all();

        return $users;
    }

    /**
     * @return requested user
     */
    public function findById($id)
    {
        return User::find($id);
    }

    /**
     * @return requested user
     */
    public function findByEmail(Request $request)
    {
        return $this->service->findByEmail($request->email);
    }
}
