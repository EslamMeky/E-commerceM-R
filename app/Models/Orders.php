<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $table='orders';
    protected $fillable=[
        'id',
        'transaction_id',
        'order_id',
        'code_user',
        'user_id',
        'type_user',
        'payment_method',
        'status',
        'amount_cents',
        'currency',
        'discount',
        'before_discount',
        'shipping_data',
        'items',
        'commission_paid',
        'created_at',
        'updated_at',
    ];

    public function scopeSelection($q){
        return $q->select([
            'id',
            'transaction_id',
            'order_id',
            'code_user',
            'user_id',
            'type_user',
            'payment_method',
            'status',
            'amount_cents',
            'currency',
            'discount',
            'before_discount',
            'shipping_data',
            'items',
            'commission_paid',
            'created_at',
            'updated_at',
        ]);
    }
    public $timestamps=true;

    public function getShippingDataAttribute($val)
    {
        if (is_array($val)) {
            return $val; // إذا كان الحقل بالفعل مصفوفة، قم بإعادته كما هو
        }

        return ($val != null) ? json_decode($val, true) : [];
    }
    public function getItemsAttribute($val)
    {
        if (is_array($val)) {
            return $val; // إذا كان الحقل بالفعل مصفوفة، قم بإعادته كما هو
        }

        return ($val != null) ? json_decode($val, true) : [];
    }
}
