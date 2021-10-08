<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Type_users extends Model
{
    use HasFactory;

    protected $fillable = [
        'type'
    ];

    public function users_types()
    {
        return $this->belongsToMany(User::class);
    }
}
