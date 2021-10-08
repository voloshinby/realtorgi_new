<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionConfirm extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'lot_id', 'confirmed_admin' , 'confirmed_user' ,  'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }

    public function gallery()
    {
        return $this->hasMany('App\Models\Gallery', 'lot_id', 'lot_id');
    }

    public function auction()
    {
        return $this->hasOneThrough(
            Auction::class,
            Lot::class,
            'id',
            'id',
            'lot_id',
            'auction_id',
        );
    }

}
