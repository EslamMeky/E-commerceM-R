<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table='carts';
    protected $fillable=[
        'id',
        'user_id',
        'product_id',
        'quantity',
        'color',
        'size',
        'created_at',
        'updated_at',
    ];

    public function scopeSelection($q){
        return $q->select([
            'id',
            'user_id',
            'product_id',
            'quantity',
            'color',
            'size',
            'created_at',
            'updated_at',
        ]);
    }
    public $timestamps=true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Products::class,'product_id');
    }

}
