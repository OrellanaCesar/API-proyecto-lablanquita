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
  public $timestamps = false;
  protected $primaryKey = 'brand_id';
  protected $fillable = [
   'brand_name'
 ];

   /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */


   protected $hidden = [
     'brand_create_date','brand_change_date'
   ];

   public function products(){
     return $this->hasMany(Product::class);
   }
    //use HasFactory;
 }
