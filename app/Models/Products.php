<?php

namespace App\Models;

use App\Mail\NewProductMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Products extends Model
{
    use HasFactory;
    protected $table='products';
    protected $fillable=[
        'id',
        'category_id',
        'name_ar',
        'name_en',
        'image',
        'OtherImage',
        'desc_ar',
        'desc_en',
        'main_price',
        'price_discount',
        'colors',
        'sizes',
        'stock',
        'barcode',
        'out_of_stock',
        'created_at',
        'updated_at'
    ];
    protected $timestamp=true;


    public function scopeSelection($q){
        $local = app()->getLocale();
        return $q->select([
            'id',
            'category_id',
            'name_' . $local . ' as name', // إرجاع اسم المنتج بناءً على اللغة
            'image',
            'OtherImage',
            'desc_' . $local . ' as desc', // إرجاع الوصف بناءً على اللغة
            'main_price',
            'price_discount',
            'colors',
            'sizes',
            'stock',
            'barcode',
            'out_of_stock',
            'created_at',
            'updated_at'
        ]);
    }
    public function getImageAttribute($val)
    {
        return ($val != null) ? asset('assets/' . $val) : "";
    }

    public function getOtherImageAttribute($val)
    {
        if ($val != null) {
            $images = json_decode($val, true); // فك تشفير JSON إلى Array
            if (is_array($images)) {
                return array_map(function ($image) {
                    return asset('assets/' . $image); // تعديل المسار لإضافة المجلد الناقص
                }, $images);
            }
        }
        return [];
    }



    public function getColorsAttribute($val)
    {
        if (is_array($val)) {
            return $val; // إذا كان الحقل بالفعل مصفوفة، قم بإعادته كما هو
        }

        return ($val != null) ? json_decode($val, true) : [];
    }

    public function getSizesAttribute($val)
    {
        if (is_array($val)) {
            return $val; // إذا كان الحقل بالفعل مصفوفة، قم بإعادته كما هو
        }

        return ($val != null) ? json_decode($val, true) : [];
    }



    public function category()
    {
     return $this->belongsTo(Category::class,'category_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class,'product_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($product) {
            // جلب جميع المشتركين
            $subscribers = Subscribe::pluck('email');

            // إرسال البريد لكل مشترك
            foreach ($subscribers as $email) {
                Mail::to($email)->send(new NewProductMail($product));
            }
        });
    }
}
