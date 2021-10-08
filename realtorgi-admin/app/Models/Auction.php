<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name', 'starts_at', 'ends_at', 'start_selling', 'end_selling',
        'auction_number', 'organizer_requisites', 'seller_id', 'seller_phone',
        'requisites', 'anticrisis_manager', 'anticrisis_manager_phone',
        'how_to_view_the_property', 'contact_person', 'compensation',
        'step', 'additional_info', 'deleted_at', 'created_at', 'updated_at',
        'type', 'city_id', 'seller_custom', 'city_name', 'images', 'files',
    ];

    public function user_seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function gallery()
    {
        return $this->hasMany('App\Models\AuctionGallery', 'auction_id', 'id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\AuctionFiles', 'auction_id', 'id');
    }


}
