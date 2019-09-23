<?php

namespace Modules\User\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Jobs\VerifyEmailJob;
use Modules\User\Jobs\SendResetPasswordEmailJob;
use Laravel\Passport\HasApiTokens;
use Storage, Arr;

class UserEntity extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRoles, SoftDeletes, HasApiTokens;

    protected $table = 'user';

    protected $primaryKey = 'user_id';

    protected $guarded = ['user_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // protected $appends = ['avatar', 'last_verify_selfie_photo', 'status_text', 'identity', 'latest_document'];

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    public function sendEmailVerificationNotification()
    {
        VerifyEmailJob::dispatch($this);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        SendResetPasswordEmailJob::dispatch($this, $token);
    }

    /**
     * Check the user role is administrator
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->hasRole('administrator'); // administrator role, rever to roles table
    }
}
