<?php

namespace App;

use App\Mail\ResetPassword;
use League\OAuth2\Server\Exception\OAuthServerException;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
        'email',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_confirmed' => 'boolean',
        'is_actived' => 'boolean'
    ];

    /**
     * Get the route key name for Laravel.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * Get the items for the user.
     */
    public function items()
    {
        return $this->hasMany('App\Item');
    }

    /**
     * Get the profile for the user.
     */
    public function profile()
    {
        return $this->hasOne('App\Profile');
    }    
    
    /**
     * Get the bookmarks for the user.
     */
    public function bookmarks()
    {
        return $this->hasMany('App\Bookmark');
    }

    /**
     * Mark the user's account as confirmed.
     */
    public function confirm()
    {
        $this->is_confirmed = true;
        $this->confirmation_token = null;
        $this->save();
    }

    /**
     * Laravel\Passport\Bridge\UserRepository
     * Add condition to this existing function
     * Check if the user is actived and confirmed
     */
    public function validateForPassportPasswordGrant($password)
    {
        if (Hash::check($password, $this->getAuthPassword())) {
            if ($this->is_confirmed) {
                if ($this->is_actived) {
                    return true;
                } 

                throw new OAuthServerException(__('auth.not_actived'), 6, 'user_inactive', 401);
            }

            throw new OAuthServerException(__('auth.not_confirmed'), 6, 'user_unconfirmed', 401);          
        }
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        Mail::to($this)->send(new ResetPassword($this, $token));
    }    
}
