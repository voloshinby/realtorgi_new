<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable// implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login', 'type', 'email', 'type_user', 'first_name', 'last_name',
        'full_name', 'passport_number', 'passport_issuer', 'passportDate',
        'passport_personal', 'registration_address', 'residence_address',
        'phone', 'inn', 'short_name', 'egr_date', 'manager_data', 'place_of_residence',
        'bank_details', 'legal_entity', 'active', 'password', 'remember_token',
        'email_verified_at', 'registration_code'
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
        'email_verified_at' => 'datetime',
    ];

     /**
     * Get the profile photo URL attribute.
     *
     * @return string
     */
    public function getPhotoAttribute()
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower($this->email)) . '.jpg?s=200&d=mm';
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function types()
    {
        return $this->belongsToMany(Type_users::class);
    }

    /**
     * Assigning User role
     *
     * @param \App\Models\Role $role
     */
    public function assignRole(Role $role)
    {
        return $this->roles()->save($role);
    }

    public function isAdmin()
    {
        return $this->roles()->where('name', 'Admin')->exists();
    }

    public function isUser()
    {
        return $this->roles()->where('name', 'User')->exists();
    }

    public function files()
    {
        return $this->hasMany('App\Models\UserFiles', 'user_id', 'id');
    }

    public function userFiles()
    {
        return $this->hasMany('App\Models\UserFiles', 'user_id', 'id');
    }

    public function gallery()
    {
        return $this->hasMany('App\Models\UserImages', 'user_id', 'id');
    }

}
