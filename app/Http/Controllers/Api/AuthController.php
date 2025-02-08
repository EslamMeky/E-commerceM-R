<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Traits\GeneralTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use GeneralTrait;

    public function register(AuthRequest $request)
    {
        try
        {
            $otp = rand(100000, 999999);
            $otpExpiry = now()->addMinutes(5);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'country' => $request->country,
                'city' => $request->city,
                'password' => bcrypt($request->password),
                'otp' => $otp,
                'otp_expiry' => $otpExpiry,
            ]);
            Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
                $message->to($user->email)->subject('Verify Your Email');
            });
            return $this->ReturnSuccess(201,__('message.registered'));
        }

        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function verifyEmail(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'otp' => 'required|integer|digits:6',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->ReturnError('404',__('message.notFound'));
            }

            if ($user->otp != $request->otp) {
                return response()->json(['error' => 'Invalid OTP'], 400);
            }

            if ($user->otp_expiry < now()) {
                return response()->json(['error' => 'OTP has expired'], 400);
            }

            $user->update([
                'email_verified_at' => now(),
            ]);

            return $this->ReturnSuccess(200,__('message.verified'));
        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password) ) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        if (!$user->email_verified_at) {
            return response()->json(['error' => 'Email not verified'], 403);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
        ]);
    }

    public function resentOtp(Request $request)
    {
        try
        {
            // التحقق من وجود البريد الإلكتروني
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->ReturnError('404', __('message.notFound'));
            }

                $otp = rand(100000, 999999);
                $otpExpiry = now()->addMinutes(5);

                $user->update([
                    'otp' => $otp,
                    'otp_expiry' => $otpExpiry,
                ]);

                Mail::raw("Your new OTP is: $otp", function ($message) use ($user) {
                    $message->to($user->email)->subject('Verify Your Email');
                });

                return $this->ReturnSuccess(200, __('message.otpSent'));

        }
        catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function forgetPassword(Request $request)
    {
        try {
            // التحقق من صحة البيانات المدخلة
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|exists:users,email',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = User::where('email', $request->email)->first();

            // إنشاء OTP جديد
            $otp = rand(100000, 999999);
            $otpExpiry = now()->addMinutes(5);

            // تحديث OTP في قاعدة البيانات
            $user->update([
                'otp' => $otp,
                'otp_expiry' => $otpExpiry,
            ]);

            // إرسال OTP عبر البريد الإلكتروني
            Mail::raw("Your password reset OTP is: $otp", function ($message) use ($user) {
                $message->to($user->email)->subject('Password Reset OTP');
            });

            return $this->ReturnSuccess(200, __('message.otpSent'));
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }


    public function changePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // جلب المستخدم المصادق عليه
            $user = auth()->user();

            // تحديث كلمة المرور مع التشفير
            $user->update([
                'password' => bcrypt($request->password),
            ]);

            return $this->ReturnSuccess(200, __('message.updated'));
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            // التحقق من صحة البيانات المدخلة
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|exists:users,email',
                'otp' => 'required|integer|digits:6',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = User::where('email', $request->email)->first();

            // التحقق من صلاحية الـ OTP
            if ($user->otp != $request->otp) {
                return response()->json(['error' => 'Invalid OTP'], 400);
            }

            if ($user->otp_expiry < now()) {
                return response()->json(['error' => 'OTP has expired'], 400);
            }

            // تحديث كلمة المرور
            $user->update([
                'password' => bcrypt($request->password),

            ]);

            return $this->ReturnSuccess(200, __('message.reset'));
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function singleUser()
    {
        try {
            // جلب المستخدم المصادق عليه
            $user = User::with('orders')->find(auth()->id());

            // التحقق من وجود المستخدم
            if (!$user) {
                return $this->ReturnError(404, __('message.notFound'));
            }

            return $this->ReturnData('user', $user, '');
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }


    public function showAll()
    {
        try
        {
            $user=User::paginate(pag);
            return $this->ReturnData('user',$user,'');
        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(), $ex->getMessage());

        }
    }
    public function updateProfile(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required|unique:users,phone,' . auth()->id(),
                'country' => 'required',
                'city' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user=auth()->user();
            if (!$user) {
               return $this->ReturnError('404',__('message.notFound'));
            }
            if ($request->hasFile('image')){
                $photoPath = parse_url($user->image, PHP_URL_PATH);
                $photoPath = ltrim($photoPath, '/');
                $oldImagePath = public_path($photoPath);

                if ($user->image && file_exists($oldImagePath))
                {

                    unlink($oldImagePath);
                }
                $pathImage= uploadImage('customers',$request->image);
                $user->update([
                    'image'=>$pathImage,
                    'name'=>$request->name,
                    'phone'=>$request->phone,
                    'country'=>$request->country,
                    'city'=>$request->city,
                ]);
            }
            $user->update([
                'name'=>$request->name,
                'phone'=>$request->phone,
                'country'=>$request->country,
                'city'=>$request->city,
            ]);


            return $this->ReturnSuccess(200,__('message.updated'));
        }
        catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(), $ex->getMessage());

        }
    }

    public function searchUsers(Request $request)
    {
        try {
            $query = User::query();

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

            // البحث عن الجنس (gender)
            if ($request->has('gender') && $request->gender != '') {
                $query->where('gender', 'like', '%' . $request->gender . '%');
            }

            // البحث عن البلد (country)
            if ($request->has('country') && $request->country != '') {
                $query->where('country', 'like', '%' . $request->country . '%');
            }

            // البحث عن المدينة (city)
            if ($request->has('city') && $request->city != '') {
                $query->where('city', 'like', '%' . $request->city . '%');
            }

            // تطبيق الفلاتر، البحث حسب الشروط المذكورة
            $users = $query->get();



            return $this->ReturnData('users', $users, 'Results found');
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }



}
