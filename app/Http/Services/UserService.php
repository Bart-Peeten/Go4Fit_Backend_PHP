<?php


namespace App\Http\Services;


use App\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function findByEmail(String $email)
    {
        $user = User::where('email', $email)
            ->first();

        return $user;
    }

}
