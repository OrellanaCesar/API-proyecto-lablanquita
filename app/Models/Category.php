<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    const CREATED_AT = 'category_create_date';
    const UPDATED_AT = 'category_change_date';

    protected $primaryKey = 'category_id';
    protected $fillable = [
        'category_name'
    ];

    protected $hidden = [
        'create_create_date','change_change_date'
     ];

    public function products(){
        return $this->hasMany('App\Models\Product','category_id');
    }

}
