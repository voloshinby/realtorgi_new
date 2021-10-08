<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type_user', 'full_name', 'passport_number', 'passport_issuer', 'passportDate',
        'passport_personal', 'registration_address', 'residence_address', 'phone', 'inn', 'short_name', 'egr_date',
        'manager_data', 'place_of_residence', 'bank_details', 'legal_entity', 'active', 'confirmed', 'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gallery()
    {
        return $this->hasMany('App\Models\UserImages', 'user_id', 'user_id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\UserFiles', 'user_id', 'user_id');
    }

    public function userFiles()
    {
        return $this->hasMany('App\Models\UserFiles', 'user_id', 'id');
    }
}
