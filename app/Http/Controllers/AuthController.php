<?php

namespace App\Http\Controllers;

use App\Http\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegistrationRequest;

class AuthController extends Controller
{
    /**
     * @var bool
     */
    public $loginAfterSignUp = true;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $token = null;

        if (!$token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }

        $user = JWTAuth::user();

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }

    /**
     * @param RegistrationFormRequest $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
//        $data = $request->validated();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'firstname' => 'required|string',
            'email' => 'required|email|unique:users',
            'telephone' => 'required',
            'password' => 'required|string|min:6|max:10',
        ]);

        if ($validator->fails()){
           return response()->json([
               'status' => 'error',
               'message' => $validator->messages()
           ], 200);
        }

//        $data['password'] = bcrypt($data['password']);
//
//        $user = User::create($data);
//
//        if ($this->loginAfterSignUp) {
//            return $this->login($request);
//        }

        $user = new User;
        $user->fill($request->all());
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'success'   =>  true,
            'data'      =>  $user
        ], 200);
    }
}
