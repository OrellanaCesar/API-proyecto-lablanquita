<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    const CREATED_AT = 'profile_create_date';
    const UPDATED_AT = 'profile_change_date';

    protected $primaryKey = 'profile_id';
    protected $fillable = [
        'profile_name'
    ];

    protected $hidden = [
        'profile_create_date','profile_change_date'
     ];

    public function users(){
        return $this->hasMany('App\Models\User','profile_id');
    }
}
