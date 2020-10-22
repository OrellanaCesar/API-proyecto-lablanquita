<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    const CREATED_AT = 'product_create_date';
    const UPDATED_AT = 'product_change_date';

    protected $primaryKey = 'product_id';
    protected $fillable = [
        'product_name',
        'product_description',
        'product_image',
        'product_price',
        'product_stock',
        'product_offer_day',
        'product_offer_day_order',
        'product_best_seller',
        'product_best_seller_order',
        'product_discount_percentage',
        'brand_id',
        'category_id'    
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       'product_create_date','product_change_date'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    public function categories(){
        return $this->belongsTo('App\Models\Category');
    }

    public function Brands(){
        return $this->belongsTo('App\Models\Brand');
    }
}
