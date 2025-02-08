<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: "Tahoma", "Arial", "Cairo", sans-serif; direction: rtl; text-align: right; }
        .invoice-container { padding: 20px; max-width: 800px; margin: auto; border: 1px solid #ccc; border-radius: 10px; }
        h1 { text-align: center; color: #4CAF50; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 8px; border: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .total { font-size: 18px; font-weight: bold; color: #333; }
    </style>
</head>
<body>
<div class="invoice-container">
    <h1>فاتورة</h1>
    <p><strong>معرف الطلب:</strong> {{ $order->order_id }}</p>
    <p><strong>التاريخ:</strong> {{ $order->updated_at }}</p>

    <h3>طريقة الدفع:</h3>
    <p>{{ $order->payment_method }}</p>

    <h3>الحالة:</h3>
    <p>{{ $order->status }}</p>

    <h3>العناصر:</h3>
    <table>
        <tr>
            <th>اسم المنتج</th>
            <th>السعر الفردي</th>
            <th>الكمية</th>
            <th>الإجمالي</th>
        </tr>
        @foreach ($order->items as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ number_format($item['amount_cents'], 2) }} EGP</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ number_format($item['amount_cents'] * $item['quantity'], 2) }} EGP</td>
            </tr>
        @endforeach
    </table>

    <h3 class="total">الإجمالي: {{ number_format($order->before_discount, 2) }} EGP</h3>
    <h3 class="total">الخصم: {{ number_format($order->discount, 2) }} EGP</h3>
    <h3 class="total">الإجمالي بعد الخصم: {{ number_format($order->amount_cents, 2) }} EGP</h3>
</div>
</body>
</html>
