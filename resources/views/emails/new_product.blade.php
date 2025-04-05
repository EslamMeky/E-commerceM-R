@php
    use Illuminate\Support\Str;
    $isArabic = app()->getLocale() === 'ar';
    $productSlug = $isArabic ? Str::slug($product->name_ar, '-') : Str::slug($product->name_en, '-');
@endphp
    <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@if(app()->getLocale() === 'ar') منتج جديد متاح الآن! @else New Product Available Now! @endif</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px; text-align: center; direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }};">

<div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <h2 style="color: #343a40;">
        @if(app()->getLocale() === 'ar') 🎉 تم إضافة منتج جديد إلى متجرنا! @else 🎉 A New Product Has Been Added to Our Store! @endif
    </h2>

    <p style="font-size: 18px; color: #555;">
        @if(app()->getLocale() === 'ar') اسم المنتج: <strong style="color: #007bff;">{{ $product->name_ar }}</strong> @else Product Name: <strong style="color: #007bff;">{{ $product->name_en }}</strong> @endif
    </p>

    <p style="font-size: 18px; color: #555;">
        @if(app()->getLocale() === 'ar') السعر: <strong style="color: #28a745;">{{ $product->price_discount }} جنيه</strong> @else Price: <strong style="color: #28a745;">{{ $product->price_discount }} EGP</strong> @endif
    </p>

    <p style="font-size: 16px; color: #666; line-height: 1.6;">
        @if(app()->getLocale() === 'ar') الوصف: {{ $product->desc_ar }} @else Description: {{ $product->desc_en }} @endif
    </p>

    <a href="{{ url('https://mr-elite.com/product/' . $productSlug) }}"
       style="display: inline-block; background-color: #28a745; color: white; padding: 12px 25px; text-decoration: none; font-size: 18px; border-radius: 5px; margin-top: 15px; transition: background 0.3s;">
        {{ $isArabic ? 'تصفح المنتج الآن 🚀' : 'Browse Product Now 🚀' }}
    </a>
</div>

</body>
</html>
