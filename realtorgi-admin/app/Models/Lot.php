<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lot extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'lot_number', 'auction_id', 'name', 'info', 'price_start',
        'price_min', 'step', 'deposit', 'images', 'files', 'additional_info',
        'status', 'deleted_at', 'created_at', 'updated_at', 'category_id', 'price_step'
    ];

    public function user_seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function auction_files()
    {
        return $this->hasManyThrough(
            AuctionFiles::class,
            Auction::class,
            'id',
            'auction_id',
            'auction_id',
            'id',
        );
    }

    public function auction_gallery(){
        return $this->hasManyThrough(
            AuctionGallery::class,
            Auction::class,
            'id',
            'auction_id',
            'auction_id',
            'id',
        );
    }

    public function gallery()
    {
        return $this->hasMany('App\Models\Gallery', 'lot_id', 'id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\File', 'lot_id', 'id');
    }

    public function users()
    {
        return $this->hasOne('App\Models\AuctionConfirm', 'lot_id', 'id')->where('confirmed_user', 1);
    }
}
