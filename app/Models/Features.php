<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Features extends Model
{
    use HasFactory;
    protected $table='features';
    protected $fillable=[
        'id',
        'tittle_ar',
        'tittle_en',
        'desc_ar',
        'desc_en',
        'image',
        'created_at',
        'updated_at',
    ];

    public function scopeSelection($q){
        $local = app()->getLocale();
        return $q->select([
            'id',
            'tittle_'.$local.' as tittle',
            'desc_'.$local.' as desc',
            'image',
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
