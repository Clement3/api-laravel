<?php

namespace App;

use League\OAuth2\Server\Exception\OAuthServerException;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
                } else {
                    throw new OAuthServerException('User account is not active', 6, 'user_inactive', 401);
                }
            } else {
                throw new OAuthServerException('User is not confirmed', 6, 'user_unconfirmed', 401);
            }
        }
    }
}
