<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'title',
        'user_id',
        'text',
        'created_at',
        'updated_at',
        'status',
        'is_view_by_admin',
        'is_shown',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_admin()
    {
        return $this->belongsTo(User::class, 'user_id');

    }
}
