<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bargaining extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $fillable = [
        'type', 'starts_at', 'ends_at', 'requisites', 'manager_phone', 'info', 'contact_person', 'step',
        'additional_info', 'status', 'created_at', 'updated_at'
     ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
