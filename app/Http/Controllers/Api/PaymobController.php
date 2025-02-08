<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Mail\OrderPaidMail;
use App\Models\Cart;
use App\Models\Orders;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
class PaymobController extends Controller
{
    use GeneralTrait;

    protected $base_url;
    protected $api_key;
    protected $header;
    protected $integrations_id;
    public function __construct()
    {
        $this->base_url = env("Paymob_Base_Url", "https://accept.paymob.com");
        $this->api_key = env("Paymob_API_Key","ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljSEp2Wm1sc1pWOXdheUk2T1RRM056ZzVMQ0p1WVcxbElqb2lNVGN6Tnpjek5EYzJOUzR6T1RBek55SjkuNGJUdDdPVmdNS3RmWlVlSEk1dm44b1E2WDlUQVA4ZjMwaEVXU0c2U2pZN0k5SWoyZHFCRjlnUEcyVkVaSHVkc3RWYW5Cc2VNUGM5em82NWhvYzZJLVE=");

        // إعداد الهيدر
        $this->header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $this->integrations_id = [4890871 , 4420612];

    }

    public function generateToken()
    {
        try {

            $response = Http::post($this->base_url . '/api/auth/tokens', [
                'api_key' => $this->api_key,
            ]);

            $result = $response->json();

            if (!$response->successful() || !isset($result['token'])) {
                return $this->ReturnError('E00','Unable to retrieve token.');
            }

            return $result['token'];

        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }

    }



//    public function sendPayment(Request $request): JsonResponse
//    {
//        try {
//            $this->header['Authorization'] = 'Bearer ' . $this->generateToken();
//
//            // Validate data before sending it
//            $data = $request->all();
//            $data['api_source'] = "INVOICE";
//            $data['integrations'] = $this->integrations_id;
//
//            $response = Http::withHeaders($this->header)
//                ->post($this->base_url . '/api/ecommerce/orders', $data);
//
//            $result = $response->json();
//
//            if (!$response->successful()) {
//                return $this->ReturnError(400, 'Payment failed');
//            }
//
//            return $this->ReturnData('url', $result, 'done url');
//        } catch (\Exception $ex) {
//            return $this->ReturnError($ex->getCode(), $ex->getMessage());
//        }
//    }
//
//
//    public function callBack(Request $request): \Illuminate\Http\RedirectResponse
//    {
//        // حفظ الاستجابة في ملف JSON
//        Storage::put('paymob_response.json', json_encode($request->all()));
//
//        // فحص إذا كانت العملية ناجحة
//        $response = $request->all();
//        $shippingData = isset($response['shipping_data']) ? $response['shipping_data'] : null;
//        $items = isset($response['items']) ? $response['items'] : null;
//        if (isset($response['success']) && $response['success'] === 'true') {
//            // الحصول على بيانات الطلب وحالة الدفع
//            $orderId = $response['shipping_data']['order_id'] ?? null;
//            $status = $response['status'] ?? 'pending'; // تعيين الحالة الافتراضية إذا لم تكن موجودة
//            $paymentMethod = $response['payment_method'] ?? 'unknown';
//
//            // حفظ بيانات العملية في قاعدة البيانات
//            $order = Orders::create([
//                'transaction_id' => $response['id'],
//                'order_id' => $orderId,
//                'type_user' => $response['type_user'] ?? 'guest',
//                'amount_cents' => $response['amount_cents'],
//                'currency' => $response['currency'],
//                'payment_method' => $paymentMethod,
//                'discount' => $response['discount'] ?? 0,
//                'before_discount' => $response['before_discount'] ?? 0,
//                'shipping_data' => $shippingData,
//                'items' => $items,
//                'status' => $status, // إضافة الحالة
//            ]);
//
//            return redirect()->route('payment.success');
//        }
//
////
////        // إعادة التوجيه إلى صفحة الفشل
//        return redirect()->route('payment.failed');
//    }


    public function sendPayment(Request $request): JsonResponse
    {
        try {
            $this->header['Authorization'] = 'Bearer ' . $this->generateToken();

            // Validate data before sending it
            $data = $request->all();
            $data['api_source'] = "INVOICE";
            $data['integrations'] = $this->integrations_id;

            // إرسال البيانات إلى Paymob
            $response = Http::withHeaders($this->header)
                ->post($this->base_url . '/api/ecommerce/orders', $data);

            $result = $response->json();

            if (!$response->successful()) {
                return $this->ReturnError(400, 'Payment failed');
            }
            $userId = $data['userId'] ?? null;
            $codeUser = $data['codeUser'] ?? null;
            // حفظ البيانات في قاعدة البيانات بعد الحصول على الـ response
            Orders::create([
                'order_id' => $result['id'],  // order من response هو order_id
                'type_user' => $data['type_user'] ?? 'guest',
                'user_id' => $userId ,
                'code_user'=>$codeUser,
                'amount_cents' => $data['amount_cents'] / 100,
                'currency' => $data['currency'],
                'discount' => ($data['discount'] ?? 0) / 100, // قسمة الخصم على 100
                'before_discount' => ($data['before_discount'] ?? 0) / 100,
                'shipping_data' => json_encode($data['shipping_data']) ,
                'items' => json_encode($data['items']) ,
                'status' => 'pending',  // تعيين الحالة الافتراضية
            ]);
           $user= User::where('id',$data['userId'])
               ->where('code',null)->first();
            if ($user)
            {
                $uniqueCode = generateUniqueRandomCode('users', 'code', 12);
                $user->update([
                   'code'=>$uniqueCode,
                ]);
            }

            return $this->ReturnData('url', $result, 'done url');
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function callBack(Request $request): \Illuminate\Http\RedirectResponse
    {
        // حفظ الاستجابة في ملف JSON
        Storage::put('paymob_response.json', json_encode($request->all()));

        // فحص إذا كانت العملية ناجحة
        $response = $request->all();

        if (isset($response['success']) && $response['success'] === 'true')
        {
            // الحصول على بيانات الطلب وحالة الدفع
            $transactionId = $response['id']; // تم تعديلها هنا
            $orderId = $response['order']; // تم تعديلها هنا

            $order = Orders::where('order_id',$orderId)->first();

            if ($order) {
                // تحديث بيانات الطلب في قاعدة البيانات بعد الدفع
                $order->update([
                    'transaction_id' => $transactionId,
                    'payment_method' => $response['source_data_type'],
                    'status' => "paid",
                ]);


                if (!empty($order->user_id)) {
                    Cart::where('user_id', $order->user_id)->delete();
                }
                $shippingData = $order->shipping_data; // لا تحتاج إلى json_decode

                if (is_array($shippingData) && !empty($shippingData['email'])) {
                    Mail::to($shippingData['email'])->send(new OrderPaidMail($order));
                }
            }

            return redirect()->route('payment.success');
        }

        return redirect()->route('payment.failed');
    }

    public function showAll(){
        try
        {
         $orders= Orders::latest()->paginate(pag);
          return $this->ReturnData('orders',$orders,"");
        }catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function showByCode($code){
        try
        {
            $orders= Orders::where('code_user',$code)->paginate(pag);
            return $this->ReturnData('orders',$orders,"");
        }catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function cashOnDelivery(Request $request): JsonResponse
    {
        try {
            // Validate data before saving it
            $data = $request->all();
            $data['payment_method'] = 'cash_on_delivery';
            $data['status'] = 'pending'; // الحالة الافتراضية عند الطلب

            // حفظ الطلب في قاعدة البيانات
            $order = Orders::create([
                'transaction_id' => null, // لا يوجد Transaction ID للدفع عند الاستلام
                'order_id' => uniqid(), // يمكنك استخدام معرف فريد للطلب
                'user_id' => $data['userId'],
                'code_user' => $data['codeUser'] ?? null,
                'type_user' => $data['type_user'] ?? 'guest',
                'amount_cents' => $data['amount_cents'] / 100,
                'currency' => $data['currency'],
                'discount' => ($data['discount'] ?? 0) / 100,
                'before_discount' => ($data['before_discount'] ?? 0) / 100,
                'shipping_data' => json_encode($data['shipping_data']),
                'items' => json_encode($data['items']),
                'payment_method' => $data['payment_method'],
                'status' => $data['status'],
            ]);

            // إذا أردت إرسال إشعار أو بريد إلكتروني، يمكنك إضافته هنا
            if (!empty($data['userId'])) {
            Cart::where('user_id', $data['userId'])->delete();
            }
            $shippingData = is_string($order->shipping_data)
                ? json_decode($order->shipping_data, true)
                : $order->shipping_data; // إذا كانت مصفوفة، استخدمها مباشرة

            if (is_array($shippingData) && !empty($shippingData['email'])) {
                Mail::to($shippingData['email'])->send(new OrderPaidMail($order));
            }
            return $this->ReturnSuccess(200,__('message.saved'));
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function searchOrders(Request $request)
    {
        try {
            $query = Orders::query();

            // البحث عن الـ order_id
            if ($request->has('order_id') && $request->order_id != '') {
                $query->where('order_id', 'like', '%' . $request->order_id . '%');
            }

            // البحث عن الكود الخاص بالمستخدم (code_user)
            if ($request->has('code_user') && $request->code_user != '') {
                $query->where('code_user', 'like', '%' . $request->code_user . '%');
            }

            if ($request->has('status') && $request->status != '') {
                $query->where('status', 'like', '%' . $request->status . '%');
            }

            if ($request->has('customer_name') && $request->customer_name != '') {
                $query->where('shipping_data', 'like', '%"first_name":"' . $request->customer_name . '%')
                    ->orWhere('shipping_data', 'like', '%"last_name":"' . $request->customer_name . '%');
            }
            // البحث بالفترة الزمنية (start_date, end_date)
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }

            // إرجاع الطلبات التي تطابق البحث
            $orders = $query->latest()->get();

           return $this->ReturnData('orders',$orders,'');
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }


    public function success()
    {

        return view('payment-success');
    }
    public function failed()
    {

        return view('payment-failed');
    }


}
