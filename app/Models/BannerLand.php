<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerLand extends Model
{
    use HasFactory;

    protected $table='banner_lands';
    protected $fillable=[
        'id',
        'tittle_ar',
        'tittle_en',
        'desc_ar',
        'desc_en',
        'image',
        'name_btn_ar',
        'name_btn_en',
        'link_btn',
        'status',
        'created_at',
        'updated_at',
    ];

    public function scopeSelection($q){
        $local = app()->getLocale();
        return $q->select([
            'id',
            'tittle_'.$local .' as tittle',
            'desc_'.$local .' as desc',
            'image',
            'name_btn_'.$local .' as name_btn',
            'link_btn',
            'status',
            'created_at',
            'updated_at',
        ]);
    }
    public $timestamps=true;

    public function getImageAttribute($val)
    {
        return ($val != null) ? asset('assets/' . $val) : "";
    }

}
