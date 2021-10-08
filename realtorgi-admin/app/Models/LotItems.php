<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'lot_id', 'name', 'info', 'images'
    ];

    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }

    public function gallery()
    {
        return $this->hasMany('App\Models\LotItemsGallery', 'item_id', 'id');
    }
}
