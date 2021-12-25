<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'user_id',
        'created_at',
        'updated_at',
        'status',
    ];

    protected $attributes = [
        'email' => 'test@mail.ru',
    ];
}
