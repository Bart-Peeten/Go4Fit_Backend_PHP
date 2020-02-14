<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

class Reservation extends Model
{
    /**
     * @var string
     */
    protected $table = 'reservations';

    /**
     * @var array
     */
    protected $guarded = [];

    protected $fillable = ['date', 'time'];

    /**
     * The user that belongs to the reservation
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'reservations_user');
    }
}
