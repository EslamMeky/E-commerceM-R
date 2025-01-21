<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdvertiseLandRequest;
use App\Http\Traits\GeneralTrait;
use App\Models\AdvertiseLand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdvertiseLandController extends Controller
{
    use GeneralTrait;

    public function save(AdvertiseLandRequest $request)
    {
        try
        {
            $pathImage=uploadImage('Advertise',$request->image);
            AdvertiseLand::create([
                'tittle_ar'=>$request->tittle_ar,
                'tittle_en'=>$request->tittle_en,
                'desc_ar'=>$request->desc_ar,
                'desc_en'=>$request->desc_en,
                'image'=>$pathImage,
                'name_btn_ar'=>$request->name_btn_ar,
                'name_btn_en'=>$request->name_btn_en,
                'link_btn'=>$request->link_btn,
                'status'=>$request->status,
            ]);
            return $this->ReturnSuccess(200,__('message.saved'));
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function show()
    {
        try
        {
            $advertise=AdvertiseLand::selection()->latest()->paginate(10);
            return $this->ReturnData('advertise',$advertise,"");
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }
    public function showAll()
    {
        try
        {
            $advertise=AdvertiseLand::latest()->paginate(pag);
            return $this->ReturnData('advertise',$advertise,"");
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }
    public function showLand()
    {
        try
        {
            $advertise=AdvertiseLand::selection()
                ->where('status','on')
                ->latest()->get();
            return $this->ReturnData('advertise',$advertise,"");
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try
        {
            $rules = [
                'tittle_ar' => 'required',
                'tittle_en' => 'required',
                'desc_ar' => 'required',
                'desc_en' => 'required',
                'name_btn_ar'=>'required',
                'name_btn_en'=>'required',
                'link_btn'=>'required',
                'status'=>'required',

            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
            {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $advertise = AdvertiseLand::findOrFail($id);
            if (!$advertise)
            {
                return $this->ReturnError('404',__('message.notFound'));
            }

            if ($request->hasFile('image')) {
                $photoPath = parse_url($advertise->image, PHP_URL_PATH);
                $photoPath = ltrim($photoPath, '/');
                $oldImagePath = public_path($photoPath);

                if ($advertise->image && file_exists($oldImagePath)) {

                    unlink($oldImagePath);
                }
                $pathFile = uploadImage('Advertise', $request->image);

                $advertise->where('id', $id)->update([
                    'image' => $pathFile,
                ]);
            }

            $advertise->update([
                'tittle_ar' => $request->tittle_ar,
                'tittle_en' => $request->tittle_en,
                'desc_ar' => $request->desc_ar,
                'desc_en' => $request->desc_en,
                'name_btn_ar'=>$request->name_btn_ar,
                'name_btn_en'=>$request->name_btn_en,
                'link_btn'=>$request->link_btn,
                'status'=>$request->status,


            ]);

            return $this->ReturnSuccess(200, __('message.updated'));
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete($id)
    {
        try
        {
            $advertise= AdvertiseLand::find($id);
            if (!$advertise)
            {
                return $this->ReturnError(404,__('message.notFound'));
            }
            if ($advertise->image != null){
                $image=Str::after($advertise->image,'assets/');
                $image=base_path('public/assets/'.$image);
                unlink($image);
                $advertise->delete();
            }
            else
                $advertise->delete();
            return $this->ReturnSuccess(200,__('message.deleted'));

        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getCode());
        }
    }

    public function updateStatus($id)
    {
        try {
            $advertise = AdvertiseLand::find($id);

            if (!$advertise) {
                return $this->ReturnError('404', __('message.notFound'));
            }

            // تعيين status لجميع السجلات إلى 'off'
            AdvertiseLand::query()->update(['status' => 'off']);

            // تعيين status للسجل المحدد إلى 'on'
            $advertise->update(['status' => 'on']);

            return $this->ReturnSuccess('200', __('message.updated'));
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

}
