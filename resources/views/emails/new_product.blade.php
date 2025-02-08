<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>منتج جديد متاح الآن!</title>
</head>
<body>
<h2>تم إضافة منتج جديد إلى متجرنا!</h2>
<p>اسم المنتج: {{ $product->name_ar }}</p>
<p>السعر: {{ $product->price_discount }} جنيه</p>
<p>الوصف: {{ $product->desc_ar }}</p>
<a href="{{ url('/product/' . $product->id) }}" style="background-color: #28a745; color: white; padding: 10px 20px; text-decoration: none;">
    تصفح المنتج الآن
</a>
</body>
</html>
