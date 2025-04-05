@php
    $shippingData = is_string($order->shipping_data)
        ? json_decode($order->shipping_data, true)
        : $order->shipping_data;

    $isArabic = app()->getLocale() === 'ar';

    $diffInHours = now()->diffInHours($order->created_at);
    $maxHours = 168; // 7 أيام
    $percentage = min(($diffInHours / $maxHours) * 100, 100); // cap at 100%

    if ($diffInHours < 24) {
        $diffLabel = $isArabic ? 'طلب جديد (أقل من 24 ساعة)' : 'New Order (less than 24h)';
        $bgColor = '#28a745'; // أخضر
        $icon = '✅';
    } elseif ($diffInHours < 48) {
        $diffLabel = $isArabic ? 'طلب منذ يوم تقريبًا' : 'Order placed ~1 day ago';
        $bgColor = '#fd7e14'; // أورانج
        $icon = '⏳';
    } elseif ($diffInHours < 72) {
        $diffLabel = $isArabic ? 'طلب منذ يومين تقريبًا' : 'Order placed ~2 days ago';
        $bgColor = '#ffc107'; // أصفر
        $icon = '⚠️';
    } elseif ($diffInHours < 168) {
        $diffLabel = $isArabic ? 'طلب قديم (3+ أيام)' : 'Old Order (3+ days)';
        $bgColor = '#dc3545'; // أحمر
        $icon = '❌';
    } else {
        $diffLabel = $isArabic ? 'طلب قديم جدًا (أكثر من أسبوع)' : 'Very Old Order (1+ week)';
        $bgColor = '#6c757d'; // رمادي
        $icon = '🕰️';
    }
@endphp


    <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isArabic ? 'طلب جديد من عميل' : 'New Order Received' }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px; text-align: center; direction: {{ $isArabic ? 'rtl' : 'ltr' }};">

<div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <h2 style="color: #343a40;">
        {{ $isArabic ? '📦 تم استلام طلب جديد!' : '📦 A New Order Has Been Received!' }}
    </h2>

    <p style="font-size: 18px; color: #555;">
        {{ $isArabic ? 'اسم العميل:' : 'Customer Name:' }}
        <strong style="color: #007bff;">
            {{ $shippingData['first_name'] ?? '' }} {{ $shippingData['last_name'] ?? '' }}
        </strong>
    </p>

    <p style="font-size: 18px; color: #555;">
        {{ $isArabic ? 'رقم الطلب:' : 'Order ID:' }}
        <strong>{{ $order->order_id }}</strong>
    </p>

    <p style="font-size: 18px; color: #28a745;">
        {{ $isArabic ? 'إجمالي الطلب:' : 'Order Total:' }}
        <strong>{{ $order->amount_cents }} {{ $order->currency }}</strong>
    </p>

    <p style="font-size: 16px; color: #666;">
        {{ $isArabic ? 'طريقة الدفع: الدفع عند الاستلام' : 'Payment Method: Cash on Delivery' }}
    </p>

    <p style="font-size: 16px; color: #666;">
        {{ $isArabic ? 'تاريخ الطلب:' : 'Order Date:' }}
        {{ $order->created_at->format('Y-m-d H:i') }}
    </p>

    <a href="{{ url('https://mr-elite.com/dashboard/admin/control/payment') }}"
       style="display: inline-block; background-color: #007bff; color: white; padding: 12px 25px; text-decoration: none; font-size: 18px; border-radius: 5px; margin-top: 15px; transition: background 0.3s;">
        {{ $isArabic ? 'عرض تفاصيل الطلب 👀' : 'View Order Details 👀' }}
    </a>
</div>

<div style="margin: 20px 0;">
    <div style="padding: 10px; background-color: {{ $bgColor }}; color: white; border-radius: 25px; font-size: 16px; display: flex; align-items: center; justify-content: center;">
        <span style="font-size: 20px; margin-{{ $isArabic ? 'left' : 'right' }}: 8px;">{{ $icon }}</span>
        <span>{{ $diffLabel }}</span>
    </div>

    <div style="background-color: #e9ecef; height: 10px; border-radius: 25px; margin-top: 10px; overflow: hidden;">
        <div style="width: {{ $percentage }}%; background-color: {{ $bgColor }}; height: 100%; transition: width 0.5s;"></div>
    </div>

    <p style="font-size: 12px; color: #666; margin-top: 5px;">
        {{ $isArabic ? 'تم مرور' : 'Elapsed' }} {{ round($percentage, 1) }}% من 7 أيام
    </p>
</div>

</body>
</html>
