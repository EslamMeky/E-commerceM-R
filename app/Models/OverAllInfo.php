<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverAllInfo extends Model
{
    use HasFactory;
    protected $table='over_all_infos';
    protected $fillable=[
        'id',
        'email',
        'phone',
        'whatsUp',
        'address',
        'desc_ar',
        'desc_en',
        'linkMap',
        'created_at',
        'updated_at',
    ];
    public $timestamps=true;
    public function scopeSelection($q)
    {
        $local = app()->getLocale();
        return $q->select([
            'id',
            'email',
            'phone',
            'whatsUp',
            'address',
            'desc_'.$local.' as desc',
            'linkMap',
            'created_at',
            'updated_at',
        ]);
    }
}
