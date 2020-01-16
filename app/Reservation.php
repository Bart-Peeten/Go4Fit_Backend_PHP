<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\ManyToMany
     */
    public function reservations()
    {
        return $this->belongsToMany(User::class);
    }
}
