<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Brand extends Model
{
  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
   protected $primaryKey = 'brandid';
   protected $fillable = [
     'brandname'
   ];

   /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */


   protected $hidden = [
       'brandcreatedate','brandchangedate'
   ];

   public function products(){
       return $this->hasMany(Product::class);
   }
    //use HasFactory;
}
