<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPass
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // التحقق من كلمة المرور الخاصة بـ API في الهيدر
        $apiPassword = $request->header('API_PASSWORD'); // استرجاع كلمة المرور من الهيدر

        if ($apiPassword !== env('API_PASSWORD', 'KmW3y9TtYxPsWuUG5cVYRF')) {
            return response()->json(['Message' => 'Unauthenticated App.'], 401); // إرسال استجابة غير مصرح بها
        }

        return $next($request);
    }

}
