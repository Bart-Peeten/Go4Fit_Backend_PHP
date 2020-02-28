<?php


namespace App\Http\Services;


use App\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function findByEmail(String $email): User {
        $user = User::where('email', $email)
            ->first();

        return $user;
    }

    public function findByFirstAndLastname($firstname, $lastname): User {
        $user = DB::table('users')
            ->where('name', $lastname)
            ->where('firstname', $firstname)
            ->value('id');

        return $user;
    }

    public function findUserIdByEmail($email) {
        return User::where('email', $email)
            -> value('id');
    }

}
