<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{

    // Menggunakan SoftDeletes
    use SoftDeletes;

    // Mendefinisikan relation many to many ke model Category
    public function categories(){
        return $this->belongsToMany('App\Category');
    }

    // Mendefinisikan relation many to many ke model Orders
    public function orders(){
        return $this->belongsToMany('App\Order');
    }
}
