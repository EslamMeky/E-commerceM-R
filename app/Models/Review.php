<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $table='reviews';
    protected $fillable=[
        'id',
        'product_id',
        'user_id',
        'rating',
        'comment',
        'created_at',
        'updated_at',
    ];

    public function scopeSelection($q){
        return $q->select([
            'id',
            'product_id',
            'user_id',
            'rating',
            'comment',
            'created_at',
            'updated_at',
        ]);
    }
    public $timestamps=true;

    public function product()
    {
        return $this->belongsTo(Products::class,'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
