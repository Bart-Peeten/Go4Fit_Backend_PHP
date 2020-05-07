<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
|--------------------------------------------------------------------------
*/

Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');

Route::get('users', 'UsersController@index');
Route::get('users/{id}', 'UsersController@findById');
Route::get('userbyemail', 'UsersController@findByEmail');

Route::get('reservations', 'ReservationController@index');
Route::get('reservationNames', 'ReservationController@getReservationsByDateAndTime');
Route::get('weekusers', 'ReservationController@getUsersForGivenWeek');
Route::get('cancellations', 'ReservationController@getCancellationsForGivenWeek');
Route::get('weekreservaties', 'ReservationController@getNumberOfReservationsForGivenWeek');
Route::get('isParticipantReserved', 'ReservationController@getIsParticipantReservedForGivenWeek');
Route::post('reservation', 'ReservationController@addNewReservation');
Route::post('reservationwithonlyfullname', 'ReservationController@addReservationWithOnlyFullName');
Route::delete('deletereservation', 'ReservationController@deletereservation');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', 'AuthController@logout');
    Route::get('reservations', 'ReservationController@index');
});


