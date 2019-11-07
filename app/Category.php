<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
   // Menambahkan fitur softdeletes
   use SoftDeletes;

   // Mendefinisikan relation many to many ke model book
   public function books(){
      return $this->belongsToMany('App\Book');
   }
}
