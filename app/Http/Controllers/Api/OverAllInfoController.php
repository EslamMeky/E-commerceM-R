<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OverAllInfoRequest;
use App\Http\Traits\GeneralTrait;
use App\Models\OverAllInfo;
use Illuminate\Http\Request;

class OverAllInfoController extends Controller
{
    use GeneralTrait;

    public function save(OverAllInfoRequest $request)
    {
        try {

              OverAllInfo::create([
                'email'=>$request->email,
                'phone'=>$request->phone,
                'whatsUp'=>$request->whatsUp,
                'address'=>$request->address,
                'desc_ar'=>$request->desc_ar,
                'desc_en'=>$request->desc_en,
                'linkMap'=>$request->linkMap,
            ]);

            return $this->ReturnSuccess(200, __('message.saved'));
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update(OverAllInfoRequest $request, $id)
    {
        try {

            $info = OverAllInfo::find($id);
            if (!$info) {
                return $this->ReturnError(404, __('message.notFound'));
            }

            $info->update([
                'email'=>$request->email,
                'phone'=>$request->phone,
                'whatsUp'=>$request->whatsUp,
                'address'=>$request->address,
                'desc_ar'=>$request->desc_ar,
                'desc_en'=>$request->desc_en,
                'linkMap'=>$request->linkMap,
            ]);

            return $this->ReturnSuccess(200, __('message.updated'));
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function show()
    {
        try {
            $info = OverAllInfo::Selection()->latest()->get();


            return $this->ReturnData('info', $info, '');
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function showAll()
    {
        try {
            $info = OverAllInfo::latest()->get();


            return $this->ReturnData('info', $info, '');
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }
}
