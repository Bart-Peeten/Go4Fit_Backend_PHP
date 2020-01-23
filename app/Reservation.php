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
     * @return BelongsToMany
     */
    public function reservations()
    {
        return $this->belongsToMany(User::class, 'reservations_user');
    }
}
