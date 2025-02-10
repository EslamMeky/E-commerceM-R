<!DOCTYPE html>
{{--<html lang="ar">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <style>--}}
{{--        body { font-family: "Tahoma", "Arial", "Cairo", sans-serif; direction: ltr; text-align: right; }--}}
{{--        .invoice-container { padding: 20px; max-width: 800px; margin: auto; border: 1px solid #ccc; border-radius: 10px; }--}}
{{--        h1 { text-align: center; color: #4CAF50; }--}}
{{--        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }--}}
{{--        th, td { padding: 8px; border: 1px solid #ddd; }--}}
{{--        th { background-color: #f2f2f2; }--}}
{{--        .total { font-size: 18px; font-weight: bold; color: #333; }--}}
{{--    </style>--}}
{{--</head>--}}
{{--<body>--}}
{{--<div class="invoice-container">--}}
{{--    <h1>فاتورة</h1>--}}
{{--    <p><strong>معرف الطلب:</strong> {{ $order->order_id }}</p>--}}
{{--    <p><strong>التاريخ:</strong> {{ $order->updated_at }}</p>--}}

{{--    <h3>طريقة الدفع:</h3>--}}
{{--    <p>{{ $order->payment_method }}</p>--}}

{{--    <h3>الحالة:</h3>--}}
{{--    <p>{{ $order->status }}</p>--}}

{{--    <h3>العناصر:</h3>--}}
{{--    <table>--}}
{{--        <tr>--}}
{{--            <th>اسم المنتج</th>--}}
{{--            <th>السعر الفردي</th>--}}
{{--            <th>الكمية</th>--}}
{{--            <th>الإجمالي</th>--}}
{{--        </tr>--}}
{{--        @foreach ($order->items as $item)--}}
{{--            <tr>--}}
{{--                <td>{{ $item['name'] }}</td>--}}
{{--                <td>{{ number_format($item['amount_cents'], 2) }} EGP</td>--}}
{{--                <td>{{ $item['quantity'] }}</td>--}}
{{--                <td>{{ number_format($item['amount_cents'] * $item['quantity'], 2) }} EGP</td>--}}
{{--            </tr>--}}
{{--        @endforeach--}}
{{--    </table>--}}

{{--    <h3 class="total">الإجمالي: {{ number_format($order->before_discount, 2) }} EGP</h3>--}}
{{--    <h3 class="total">الخصم: {{ number_format($order->discount, 2) }} EGP</h3>--}}
{{--    <h3 class="total">الإجمالي بعد الخصم: {{ number_format($order->amount_cents, 2) }} EGP</h3>--}}
{{--</div>--}}
{{--</body>--}}
{{--</html>--}}


{{--<html>--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1">--}}
{{--    <style>--}}
{{--        body {--}}
{{--            font-family: "Tahoma", "Arial", "Cairo", sans-serif;--}}
{{--            direction: ${direction};--}}
{{--            text-align: ${textAlign};--}}
{{--            background-color: #f9f9f9;--}}
{{--            padding: 20px;--}}
{{--        }--}}
{{--        .invoice-container {--}}
{{--            max-width: 800px;--}}
{{--            margin: auto;--}}
{{--            background: white;--}}
{{--            padding: 20px;--}}
{{--            border-radius: 10px;--}}
{{--            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);--}}
{{--        }--}}
{{--        .header {--}}
{{--            display: flex;--}}
{{--            justify-content: space-between;--}}
{{--            align-items: center;--}}
{{--            border-bottom: 2px solid #eee;--}}
{{--            padding-bottom: 15px;--}}
{{--            margin-bottom: 20px;--}}
{{--        }--}}
{{--        .header img {--}}
{{--            max-width: 80px;--}}
{{--            height: auto;--}}
{{--            border-radius: 50%;--}}
{{--        }--}}
{{--        .invoice-title {--}}
{{--            font-size: 22px;--}}
{{--            color: #333;--}}
{{--            margin: 0;--}}
{{--        }--}}
{{--        table {--}}
{{--            width: 100%;--}}
{{--            border-collapse: collapse;--}}
{{--            margin-top: 20px;--}}
{{--        }--}}
{{--        th, td {--}}
{{--            padding: 12px;--}}
{{--            border-bottom: 1px solid #ddd;--}}
{{--        }--}}
{{--        th {--}}
{{--            background-color: #f5f5f5;--}}
{{--            color: #333;--}}
{{--        }--}}
{{--        .total {--}}
{{--            font-size: 18px;--}}
{{--            font-weight: bold;--}}
{{--            margin-top: 20px;--}}
{{--        }--}}
{{--        .btn-print {--}}
{{--            padding: 12px 20px;--}}
{{--            font-size: 16px;--}}
{{--            background-color: #007bff;--}}
{{--            color: white;--}}
{{--            border: none;--}}
{{--            border-radius: 5px;--}}
{{--            cursor: pointer;--}}
{{--            display: block;--}}
{{--            margin: 20px auto;--}}
{{--        }--}}
{{--    </style>--}}
{{--</head>--}}
{{--<body>--}}
{{--<div class="invoice-container">--}}
{{--    <div class="header">--}}
{{--        <img src="{{ asset('images/logo.png') }}" alt="Company Logo">--}}
{{--        <h1 class="invoice-title">${isArabic ? "فاتورة" : "Invoice"}</h1>--}}
{{--    </div>--}}

{{--    <p><strong>${isArabic ? "رقم الطلب:" : "Order ID:"}</strong> {{ $order->order_id }}</p>--}}
{{--    <p><strong>${isArabic ? "التاريخ:" : "Date:"}</strong> {{ $order->updated_at }}</p>--}}

{{--    <h3>${isArabic ? "عنوان الشحن:" : "Shipping Address:"}</h3>--}}
{{--    <p><strong>${isArabic ? "الاسم:" : "Name:"}</strong> ${shipping_data.first_name} ${shipping_data.last_name}</p>--}}
{{--    <p><strong>${isArabic ? "العنوان:" : "Address:"}</strong> ${shipping_data.street}, ${shipping_data.city}, ${shipping_data.state}, ${shipping_data.country}</p>--}}
{{--    <p><strong>${isArabic ? "الهاتف:" : "Phone:"}</strong> ${shipping_data.phone_number}</p>--}}
{{--    <p><strong>${isArabic ? "البريد الإلكتروني:" : "Email:"}</strong> ${shipping_data.email}</p>--}}

{{--    <p><strong>${isArabic ? "طريقة الدفع:" : "Payment Method:"}</strong> {{ $order->payment_method }}</p>--}}
{{--    <p><strong>${isArabic ? "الحالة:" : "Status:"}</strong> {{ $order->status }}</p>--}}

{{--    <h3>${isArabic ? "العناصر:" : "Items:"}</h3>--}}
{{--    <table>--}}
{{--        <tr>--}}
{{--            <th>${isArabic ? "اسم المنتج" : "Product Name"}</th>--}}
{{--            <th>${isArabic ? "السعر الفردي" : "Unit Price"}</th>--}}
{{--            <th>${isArabic ? "الكمية" : "Quantity"}</th>--}}
{{--            <th>${isArabic ? "الإجمالي" : "Total"}</th>--}}
{{--        </tr>--}}
{{--        @foreach ($order->items as $item)--}}
{{--            <tr>--}}
{{--                <td>{{ $item['name'] }}</td>--}}
{{--                <td>{{ number_format($item['amount_cents'], 2) }} EGP</td>--}}
{{--                <td>{{ $item['quantity'] }}</td>--}}
{{--                <td>{{ number_format($item['amount_cents'] * $item['quantity'], 2) }} EGP</td>--}}
{{--            </tr>--}}
{{--        @endforeach--}}
{{--    </table>--}}

{{--    <h3 class="total">${isArabic ? "الإجمالي (قبل الخصم):" : "Total (Before Discount):"} {{ number_format($order->before_discount, 2) }}</h3>--}}
{{--    <h3 class="total">${isArabic ? "الإجمالي ( الخصم):" : "Total ( Discount):"} {{ number_format($order->discount, 2) }}</h3>--}}
{{--    <h3 class="total">${isArabic ? "الإجمالي (بعد الخصم):" : "Total (After Discount):"} {{ number_format($order->amount_cents, 2) }}</h3>--}}


{{--</div>--}}
{{--</body>--}}
{{--</html>--}}


@php
    $isArabic = app()->getLocale() === 'ar';

    // التأكد من أن البيانات عبارة عن JSON قبل فك تشفيرها
    $shippingData = is_string($order->shipping_data) ? json_decode($order->shipping_data, true) : $order->shipping_data;
    $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
@endphp


    <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: "Tahoma", "Arial", "Cairo", sans-serif;
            direction: {{ $isArabic ? 'rtl' : 'ltr' }};
            text-align: {{ $isArabic ? 'right' : 'left' }};
            background-color: #f9f9f9;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 80px;
            height: auto;
            border-radius: 50%;
        }
        .invoice-title {
            font-size: 22px;
            color: #333;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
            color: #333;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <div class="header">
        {{--        <img src="http://127.0.0.1:8000/logo.jpg" alt="Company Logo">--}}
        <img src="{{ $message->embed($logoPath) }}" alt="Company Logo">
        <h1 class="invoice-title">
            @if($isArabic) فاتورة @else Invoice @endif
        </h1>
    </div>

    <p><strong>@if($isArabic) رقم الطلب: @else Order ID: @endif</strong> {{ $order->order_id }}</p>
    <p><strong>@if($isArabic) التاريخ: @else Date: @endif</strong> {{ $order->updated_at }}</p>

    <h3>@if($isArabic) عنوان الشحن: @else Shipping Address: @endif</h3>
    @if($shippingData)
        <p><strong>@if($isArabic) الاسم: @else Name: @endif</strong> {{ $shippingData['first_name'] ?? '' }} {{ $shippingData['last_name'] ?? '' }}</p>
        <p><strong>@if($isArabic) العنوان: @else Address: @endif</strong> {{ $shippingData['street'] ?? '' }}, {{ $shippingData['city'] ?? '' }}, {{ $shippingData['state'] ?? '' }}, {{ $shippingData['country'] ?? '' }}</p>
        <p><strong>@if($isArabic) الهاتف: @else Phone: @endif</strong> {{ $shippingData['phone_number'] ?? '' }}</p>
        <p><strong>@if($isArabic) البريد الإلكتروني: @else Email: @endif</strong> {{ $shippingData['email'] ?? '' }}</p>
    @endif

    <p><strong>@if($isArabic) طريقة الدفع: @else Payment Method: @endif</strong> {{ $order->payment_method }}</p>
    <p><strong>@if($isArabic) الحالة: @else Status: @endif</strong> {{ $order->status }}</p>

    <h3>@if($isArabic) العناصر: @else Items: @endif</h3>
    <table>
        <tr>
            <th>@if($isArabic) اسم المنتج @else Product Name @endif</th>
            <th>@if($isArabic) السعر الفردي @else Unit Price @endif</th>
            <th>@if($isArabic) الكمية @else Quantity @endif</th>
            <th>@if($isArabic) الإجمالي @else Total @endif</th>
        </tr>
        @foreach ($items as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ number_format($item['amount_cents'], 2) }} EGP</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ number_format(($item['amount_cents']) * $item['quantity'], 2) }} EGP</td>
            </tr>
        @endforeach
    </table>

    <h3 class="total">@if($isArabic) الإجمالي (قبل الخصم): @else Total (Before Discount): @endif {{ number_format($order->before_discount, 2) }}</h3>
    <h3 class="total">@if($isArabic) الإجمالي (الخصم): @else Total (Discount): @endif {{ number_format($order->discount, 2) }}</h3>
    <h3 class="total">@if($isArabic) الإجمالي (بعد الخصم): @else Total (After Discount): @endif {{ number_format($order->amount_cents, 2) }}</h3>
</div>

</body>
</html>
