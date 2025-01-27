<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WhyRequest;
use App\Http\Traits\GeneralTrait;
use App\Models\Why;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WhyController extends Controller
{
    use GeneralTrait;
    public function index()
    {
        try
        {
           $why=Why::Selection()->latest()->paginate(pag);
           return $this->ReturnData('why',$why,"");
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function save(WhyRequest $request)
    {
        try
        {
            $pathImage=uploadImage('Why',$request->image);
            Why::create([
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

            $why = Why::findOrFail($id);
            if (!$why)
            {
                return $this->ReturnError('404',__('message.notFound'));
            }

            if ($request->hasFile('image')) {
                $photoPath = parse_url($why->image, PHP_URL_PATH);
                $photoPath = ltrim($photoPath, '/');
                $oldImagePath = public_path($photoPath);

                if ($why->image && file_exists($oldImagePath)) {

                    unlink($oldImagePath);
                }
                $pathFile = uploadImage('Why', $request->image);

                $why->where('id', $id)->update([
                    'image' => $pathFile,
                ]);
            }
            // تحديث السجل
            $why->update([
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
            $why= Why::find($id);
            if (!$why)
            {
                return $this->ReturnError(404,__('message.notFound'));
            }
            if ($why->image != null){
                $image=Str::after($why->image,'assets/');
                $image=base_path('public/assets/'.$image);
                unlink($image);
                $why->delete();
            }
            else
                $why->delete();
            return $this->ReturnSuccess(200,__('message.deleted'));

        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getCode());
        }
    }




}
