<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutLandRequest;
use App\Http\Traits\GeneralTrait;
use App\Models\AboutLand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AboutLandController extends Controller
{
    use GeneralTrait;

    public function save(AboutLandRequest $request)
    {
        try
        {
            $pathImage=uploadImage('About',$request->image);
            AboutLand::create([
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
            $about=AboutLand::selection()->latest()->paginate(10);
            return $this->ReturnData('about',$about,"");
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
            $about=AboutLand::latest()->paginate(pag);
            return $this->ReturnData('about',$about,"");
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

            $about = AboutLand::findOrFail($id);
            if (!$about)
            {
                return $this->ReturnError('404',__('message.notFound'));
            }

            if ($request->hasFile('image')) {
                $photoPath = parse_url($about->image, PHP_URL_PATH);
                $photoPath = ltrim($photoPath, '/');
                $oldImagePath = public_path($photoPath);

                if ($about->image && file_exists($oldImagePath)) {

                    unlink($oldImagePath);
                }
                $pathFile = uploadImage('About', $request->image);

                $about->where('id', $id)->update([
                    'image' => $pathFile,
                ]);
            }

            $about->update([
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
            $about= AboutLand::find($id);
            if (!$about)
            {
                return $this->ReturnError(404,__('message.notFound'));
            }
            if ($about->image != null){
                $image=Str::after($about->image,'assets/');
                $image=base_path('public/assets/'.$image);
                unlink($image);
                $about->delete();
            }
            else
                $about->delete();
            return $this->ReturnSuccess(200,__('message.deleted'));

        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getCode());
        }
    }

}
