<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $table='contacts';
    protected $fillable=[
        'id',
        'name',
        'message',
        'email',
        'created_at',
        'updated_at'
    ];
    protected $timestamp=true;


}
