<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Admin;
use App\Models\Commissions;
use App\Models\Orders;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    use GeneralTrait;
    public function calculateCommission($adminId)
    {
        try
        {
            $admin = Admin::find($adminId);
            if (!$admin) {
                return $this->ReturnError('404', __('message.notFound'));
            }

            // جلب الطلبات التي لم تُحسب عليها عمولة
            $orders = Orders::where('code_user', $admin->code)
                ->where('commission_paid', 'false')
                ->get();

            // حساب إجمالي المبيعات للبائع
            $totalSales = $orders->sum('amount_cents');

            // حساب العمولة (20%)
            $commission = ($totalSales * 20) / 100;

            $data = [
                'orders' => $orders,
                'commission' => $commission,
                'totalSales' => $totalSales,
            ];

            return $this->ReturnData('data', $data, '');
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }


    public function requestWithdrawal($adminId)
    {
        try
        {
            $admin = Admin::find($adminId);
            if (!$admin) {
                return $this->ReturnError('404', __('message.notFound'));
            }

            // جلب العمولات المسحوبة في نفس الشهر
//            $lastWithdrawal = Commissions::where('admin_id', $adminId)
//                ->whereMonth('withdraw_date', now()->month) // نفس الشهر
//                ->exists();
//
//            if ($lastWithdrawal) {
//                return $this->ReturnError('400', __('message.withdrawalAlreadyDone'));
//            }

            // جلب الطلبات التي لم تُحسب عمولتها
            $orders = Orders::where('code_user', $admin->code)
                ->where('commission_paid', 'false')
                ->get();

            $totalSales = $orders->sum('amount_cents');
            $commission = ($totalSales * 20) / 100;

            if ($commission <= 0) {
                return $this->ReturnSuccess(200, __('message.noCommission'));
            }

            // تسجيل السحب في العمولات
            Commissions::create([
                'admin_id' => $adminId,
                'amount' => $commission,
                'withdraw_date' => now(),
                'status' => 'pending', // يحتاج إلى الموافقة من صاحب الموقع
            ]);

            // تحديث الطلبات إلى عمولة محسوبة
            Orders::where('code_user', $admin->code)->update(['commission_paid' => 'true']);

//            $orders->chunk(100, function($orders) {
//                foreach ($orders as $order) {
//                    $order->update(['commission_paid' => 'true']);
//                }
//            });


            return $this->ReturnSuccess(200, __('message.saved'));
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function updateCommissionStatus($commissionId)
    {
        try
        {
            // العثور على العمولة
            $commission = Commissions::find($commissionId);
            if (!$commission) {
                return $this->ReturnError('404', __('message.notFound'));
            }

            // تحديث حالة العمولة
            $commission->update(['status' => 'paid']);


            return $this->ReturnSuccess(200, __('message.updated'));
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function getCommissionsByAdmin($adminId)
    {
        try
        {
            $admin = Admin::find($adminId);
            if (!$admin) {
                return $this->ReturnError('404', __('message.notFound'));
            }

            // جلب العمولات الخاصة بالبائع
            $commissions = Commissions::with(['sales'])->where('admin_id', $adminId)->latest()->get();

            return $this->ReturnData('commissions', $commissions, '');
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function calculateMonthlyCommission($adminId, $month, $year)
    {
        try
        {
            $admin = Admin::find($adminId);
            if (!$admin) {
                return $this->ReturnError('404', __('message.notFound'));
            }

            // جلب العمولات المدفوعة في الشهر المحدد
            $commissions = Commissions::with(['sales'])->where('admin_id', $adminId)
                ->whereMonth('withdraw_date', $month)
                ->whereYear('withdraw_date', $year)
                ->where('status', 'paid')
                ->sum('amount');

            return $this->ReturnData('monthlyCommission', $commissions, '');
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function checkPendingCommissions($adminId, $fromDate, $toDate)
    {
        try
        {
            $admin = Admin::find($adminId);
            if (!$admin) {
                return $this->ReturnError('404', __('message.notFound'));
            }

            // جلب العمولات المستحقة في الفترة الزمنية المحددة
            $pendingCommissions = Commissions::with(['sales'])->where('admin_id', $adminId)
                ->whereBetween('withdraw_date', [$fromDate, $toDate])
                ->where('status', 'pending')
                ->get();

            return $this->ReturnData('pendingCommissions', $pendingCommissions, '');
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }


    public function searchCommissions(Request $request)
    {
        try
        {
            // بناء استعلام البحث بناءً على المعايير المدخلة
            $query = Commissions::query();

            // البحث حسب الـ status
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            // البحث حسب تاريخ السحب (from_date - to_date)
            if ($request->has('from_date') && $request->has('to_date')) {
                $query->whereBetween('withdraw_date', [$request->from_date, $request->to_date]);
            }

            // استرجاع العمولات بناءً على المعايير
            $commissions = $query->with(['sales'])->latest()->get();


            return $this->ReturnData('commissions', $commissions, '');
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function show()
    {
        try
        {
           $commission= Commissions::with(['sales'])->latest()->paginate(pag);
           return $this->ReturnData('commission',$commission,'');
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function showByCode($code){
        try
        {
            $orders= Orders::where('code_user',$code)
              ->where('commission_paid','false')->paginate(pag);
            return $this->ReturnData('orders',$orders,"");
        }catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }


}
