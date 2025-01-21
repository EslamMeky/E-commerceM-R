<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServicesLandRequest;
use App\Http\Traits\GeneralTrait;
use App\Models\ServicesLand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ServicesLandController extends Controller
{
    use GeneralTrait;

    public function save(ServicesLandRequest $request)
    {
        try
        {
            $pathImage=uploadImage('Services',$request->image);
            ServicesLand::create([
                'tittle_ar'=>$request->tittle_ar,
                'tittle_en'=>$request->tittle_en,
                'desc_ar'=>$request->desc_ar,
                'desc_en'=>$request->desc_en,
                'image'=>$pathImage,
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
            $services=ServicesLand::selection()->latest()->paginate(10);
            return $this->ReturnData('services',$services,"");
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
            $services=ServicesLand::latest()->paginate(pag);
            return $this->ReturnData('services',$services,"");
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

            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
            {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $service = ServicesLand::findOrFail($id);
            if (!$service)
            {
                return $this->ReturnError('404',__('message.notFound'));
            }

            if ($request->hasFile('image')) {
                $photoPath = parse_url($service->image, PHP_URL_PATH);
                $photoPath = ltrim($photoPath, '/');
                $oldImagePath = public_path($photoPath);

                if ($service->image && file_exists($oldImagePath)) {

                    unlink($oldImagePath);
                }
                $pathFile = uploadImage('Services', $request->image);

                $service->where('id', $id)->update([
                    'image' => $pathFile,
                ]);
            }
            // تحديث السجل
            $service->update([
                'tittle_ar' => $request->tittle_ar,
                'tittle_en' => $request->tittle_en,
                'desc_ar' => $request->desc_ar,
                'desc_en' => $request->desc_en,

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
            $service= ServicesLand::find($id);
            if (!$service)
            {
                return $this->ReturnError(404,__('message.notFound'));
            }
            if ($service->image != null){
                $image=Str::after($service->image,'assets/');
                $image=base_path('public/assets/'.$image);
                unlink($image);
                $service->delete();
            }
            else
                $service->delete();
            return $this->ReturnSuccess(200,__('message.deleted'));

        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getCode());
        }
    }

}
