<?php

namespace Elysiumrealms\Otp\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Otp extends Model
{
    use Notifiable;

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
        'validity',
        'via',
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

    /**
     * Get the notification routing information for the model.
     *
     * @return string
     */
    public function routeNotificationForTwilio()
    {
        return $this->identifier;
    }

    /**
     * Get the notification routing information for the model.
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->identifier;
    }

    /**
     * Get the notification routing information for the model.
     *
     * @return string
     */
    public function routeNotificationForTelegram()
    {
        return $this->identifier;
    }
}
