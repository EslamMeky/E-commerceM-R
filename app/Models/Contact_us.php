<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact_us extends Model
{
    use HasFactory;
    protected $table='contact_uses';
    protected $fillable=[
        'id',
        'tittle_ar',
        'tittle_en',
        'desc_ar',
        'desc_en',
        'name_btn_ar',
        'name_btn_en',
        'link_btn',
        'created_at',
        'updated_at',
    ];
    public $timestamps=true;

    public function scopeSelection($q){
        $local = app()->getLocale();
        return $q->select([
            'id',
            'tittle_'.$local .' as tittle',
            'desc_'.$local .' as desc',
            'name_btn_'.$local .' as name_btn',
            'link_btn',
            'created_at',
            'updated_at',
        ]);
    }
}
