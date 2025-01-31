<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Http\Traits\GeneralTrait;
use App\Models\Admin;
use App\Models\Orders;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    use GeneralTrait;
    public function save(AdminRequest $request)
    {
        try {
            $uniqueCode = generateUniqueRandomCode('admins', 'code', 12);
            Admin::create([
            'name'=>$request->name,
            'gender'=>$request->gender,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'type'=>$request->type,
            'code'=>$uniqueCode,
            'role'=>json_encode($request->role) ,
            ]);
            return $this->ReturnSuccess(200,__('message.saved'));
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // التحقق من صحة البيانات باستخدام Auth
        if (!$token = Auth::guard('admin')->attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password',
            ], 401);
        }

        // جلب بيانات المستخدم
        $admin = Auth::guard('admin')->user();

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'admin' => $admin,
                'token' => $token,
                'token_type' => 'bearer',
            ],
        ]);
    }

    public function logout()
    {
        auth('admin')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function updateProfile(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'gender' => 'required',
                'type' => 'required',
                'role' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $user=auth('admin')->user();
            if (!$user) {
                return $this->ReturnError('404','not Found ');
            }
            $user->update([
                'name'=>$request->name,
                'gender'=>$request->gender,
                'phone'=>$request->phone,
                'email'=>$request->email,
                'type'=>$request->type,
                'role'=>json_encode($request->role) ,
            ]);


            return $this->ReturnSuccess(200,'updated Successfully');
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            // التحقق من وجود الـ Admin باستخدام التوكن
            $admin = auth('admin')->user(); // الحصول على الـ Admin بناءً على التوكن
            if (!$admin) {
                return $this->ReturnError(404, 'not Found');
            }

            // التحقق من الحقول المدخلة
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:8|confirmed', // كلمة المرور الجديدة
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // تحديث كلمة المرور الجديدة
            $admin->password = bcrypt($request->password);
            $admin->save(); // حفظ التغييرات في قاعدة البيانات

            // إرجاع استجابة بنجاح
            return $this->ReturnSuccess(200, 'password Updated');
        }
        catch (\Exception $ex)
        {
            // في حالة حدوث خطأ
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function singleUser($user_id)
    {
        try
        {
            $user=Admin::where('id',$user_id)->first();
            if (!$user)
            {
                return $this->ReturnError(404,'not Found');
            }
            return $this->ReturnData('admin',$user,'');
        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(), $ex->getMessage());

        }
    }

    public function showAll()
    {
        try
        {
                $users = Admin::paginate(pag);
            return $this->ReturnData('admin', $users, '');
        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(), $ex->getMessage());

        }
    }

    public function showByType($type)
    {
        try
        {
            $users = Admin::where('type',$type)
            ->paginate(pag);
            return $this->ReturnData('admin', $users, '');
        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(), $ex->getMessage());

        }
    }

    public function delete($id)
    {
        try
        {
            $admin= Admin::find($id);
            if (!$admin)
            {
                return $this->ReturnError(404,__('message.notFound'));
            }

            $admin->delete();
            return $this->ReturnSuccess(200,__('message.deleted'));

        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getCode());
        }
    }

    public function getDashboardStats()
    {
        try {
            $totalUsers = User::count();
            $totalProducts = Products::count();
            $totalOrders = Orders::count();
            $totalSales = Admin::where('type', 'sales')->count(); // لو عندك دور للبائعين

            $data = [
                'total_users' => $totalUsers,
                'total_products' => $totalProducts,
                'total_orders' => $totalOrders,
                'total_sellers' => $totalSales,
            ];

            return $this->ReturnData('dashboard_stats', $data, 'Dashboard statistics retrieved successfully');
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function searchAdmins(Request $request)
    {
        try {
            $query = Admin::query();

            // البحث عن الاسم (name)
            if ($request->has('name') && $request->name != '') {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            // البحث عن البريد الإلكتروني (email)
            if ($request->has('email') && $request->email != '') {
                $query->where('email', 'like', '%' . $request->email . '%');
            }

            // البحث عن رقم الهاتف (phone)
            if ($request->has('phone') && $request->phone != '') {
                $query->where('phone', 'like', '%' . $request->phone . '%');
            }

            // البحث عن النوع (type)
            if ($request->has('type') && $request->type != '') {
                $query->where('type', 'like', '%' . $request->type . '%');
            }

            // إرجاع الإداريين الذين تطابقوا مع البحث
            $admins = $query->get();

           return $this->ReturnData('admin',$admins,'');
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }


}
