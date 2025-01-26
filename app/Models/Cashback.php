<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashback extends Model
{
    use HasFactory;
    protected $table='cashbacks';
    protected $fillable=[
        'id',
        'cashback',
        'type',
        'created_at',
        'updated_at'
    ];

    public $timestamps=true;

    public function scopeSelection($q){
        return $q->select([
            'id',
            'cashback',
            'type',
            'created_at',
            'updated_at'
        ]);
    }
}
