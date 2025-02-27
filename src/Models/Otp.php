<?php

namespace Elysiumrealms\Otp\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    /**
     * The database table used by the model.
     *
     * @return string
     */
    public function getTable(): string
    {
        return config('otp.database.table');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'identifier',
        'token',
        'valid',
        'status',
        'validity'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'valid' => 'boolean',
        'status' => 'boolean',
    ];
}
