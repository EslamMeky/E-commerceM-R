<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commissions extends Model
{
    use HasFactory;
    protected $table='commissions';
    protected $fillable=[
        'id',
        'admin_id',
        'amount',
        'withdraw_date',
        'status',
        'created_at',
        'updated_at',
    ];
    public $timestamps=true;

    public function sales()
    {
        return $this->belongsTo(Admin::class,'admin_id');
    }
}
