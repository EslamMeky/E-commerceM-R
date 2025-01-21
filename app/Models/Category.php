<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table='categories';
    protected $fillable=[
        'id',
      'name_ar',
      'name_en',
      'image',
      'created_at',
      'updated_at'
    ];
    protected $timestamp=true;



    public function getImageAttribute($val)
    {
        return ($val!=null)? asset('assets/'.$val):"";
    }
}
