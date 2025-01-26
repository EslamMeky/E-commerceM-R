<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Cashback;
use Illuminate\Http\Request;

class CachBackController extends Controller
{
    use GeneralTrait;
    public function index()
    {
        $cashbacks = Cashback::all();
        return $this->ReturnData('cashBack',$cashbacks,"");
    }

    // إنشاء سجل جديد
    public function store(Request $request)
    {
        try
        {
            $request->validate([
                'cashback' => 'required|string',
                'type' => 'required|string',
            ]);

            $cashback = Cashback::create($request->all());
            return $this->ReturnSuccess(200,'saved successfully');
        }catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    // عرض سجل واحد
    public function edit($id)
    {
        try {
            $cashback = Cashback::find($id);
            if (!$cashback) {
                return $this->ReturnError('E00',"Not Found");
            }
            return $this->ReturnData('cashBack',$cashback,"");

        }catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());

        }
    }

    // تحديث سجل
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'cashback' => 'sometimes|required|string',
                'type' => 'sometimes|required|string',
            ]);

            $cashback = Cashback::find($id);
            if (!$cashback) {
                return $this->ReturnError('E00',"Not Found");
            }

            $cashback->update($request->all());
            return $this->ReturnSuccess(200,'updated successfully');
        }catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }

    }

    // حذف سجل
    public function destroy($id)
    {
        try {
            $cashback = Cashback::find($id);
            if (!$cashback) {
                return $this->ReturnError('E00',"Not Found");
            }

            $cashback->delete();
            return $this->ReturnSuccess(200,'deleted successfully');

        }catch (\Exception $ex){
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

}
