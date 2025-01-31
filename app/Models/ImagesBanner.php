<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagesBanner extends Model
{
    use HasFactory;
    protected $table='images_banners';
    protected $fillable=[
        'id',
        'product',
        'about',
        'contact',
        'profile',
        'created_at',
        'updated_at',
    ];

    public function scopeSelection($q){
        return $q->select([
            'id',
            'product',
            'about',
            'contact',
            'profile',
            'created_at',
            'updated_at',
        ]);
    }
    public $timestamps=true;

    public function getProductAttribute($val)
    {
        return ($val != null) ? asset('assets/' . $val) : "";
    }
    public function getProfileAttribute($val)
    {
        return ($val != null) ? asset('assets/' . $val) : "";
    }
    public function getContactAttribute($val)
    {
            return ($val != null) ? asset('assets/' . $val) : "";
    }
    public function getAboutAttribute($val)
    {
    return ($val != null) ? asset('assets/' . $val) : "";
    }

}
