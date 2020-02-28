<?php


namespace App\Http\Services;


use App\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * ToDo: This should be refactored to a UserRepository, so the code is more splitup and easier to make changes.
     */
    public function findByEmail(String $email): User {
        $user = User::where('email', $email)
            ->first();

        return $user;
    }

    public function findByFirstAndLastname($firstname, $lastname): User {
        $user = DB::table('users')
            ->where('name', $lastname)
            ->where('firstname', $firstname)
            ->first();

        return User::findOrFail($user->id);
    }

    public function findUserIdByEmail($email) {
        return User::where('email', $email)
            -> value('id');
    }

}
