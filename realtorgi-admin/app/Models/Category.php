<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];


    public function lot(){
        return $this->hasMany('App\Models\Lot', 'category_id', 'id')->where('status', '<>', 'Архивные')->where('status', '<>', 'Черновик')->where('status', '<>', 'На модерации');
    }
}
