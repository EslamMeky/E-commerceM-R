<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use HasFactory;
    protected $table='social_media';
    protected $fillable=[
        'id',
        'face',
        'insta',
        'tiktok',
        'twitter',
        'linkedIn',
        'created_at',
        'updated_at',
    ];
    public $timestamps=true;

    public function scopeSelection($q){
        return $q->select([
            'id',
            'face',
            'insta',
            'tiktok',
            'twitter',
            'linkedIn',
            'created_at',
            'updated_at',
        ]);
    }

}
