<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  
  const CREATED_AT = 'brand_create_date';
  const UPDATED_AT = 'brand_change_date'; 
  
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
     return $this->hasMany('App\Models\Product','brand_id');
   }
    //use HasFactory;
 }
