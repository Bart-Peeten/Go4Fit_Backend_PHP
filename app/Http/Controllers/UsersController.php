<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
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
}
