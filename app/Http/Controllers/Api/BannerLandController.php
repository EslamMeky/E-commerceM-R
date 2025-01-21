<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutLandRequest;
use App\Http\Requests\BannerLandRequest;
use App\Http\Traits\GeneralTrait;
use App\Models\BannerLand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BannerLandController extends Controller
{
    use GeneralTrait;

    public function save(BannerLandRequest $request)
    {
        try
        {
            $pathImage=uploadImage('Banner',$request->image);
            BannerLand::create([
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
            $banner=BannerLand::selection()->latest()->paginate(10);
            return $this->ReturnData('banner',$banner,"");
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
            $banner=BannerLand::latest()->paginate(pag);
            return $this->ReturnData('banner',$banner,"");
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
            $banner=BannerLand::selection()
                ->where('status','on')
                ->latest()->get();
            return $this->ReturnData('banner',$banner,"");
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

            $banner = BannerLand::findOrFail($id);
            if (!$banner)
            {
                return $this->ReturnError('404',__('message.notFound'));
            }

            if ($request->hasFile('image')) {
                $photoPath = parse_url($banner->image, PHP_URL_PATH);
                $photoPath = ltrim($photoPath, '/');
                $oldImagePath = public_path($photoPath);

                if ($banner->image && file_exists($oldImagePath)) {

                    unlink($oldImagePath);
                }
                $pathFile = uploadImage('Banner', $request->image);

                $banner->where('id', $id)->update([
                    'image' => $pathFile,
                ]);
            }

            $banner->update([
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
            $banner= BannerLand::find($id);
            if (!$banner)
            {
                return $this->ReturnError(404,__('message.notFound'));
            }
            if ($banner->image != null){
                $image=Str::after($banner->image,'assets/');
                $image=base_path('public/assets/'.$image);
                unlink($image);
                $banner->delete();
            }
            else
                $banner->delete();
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
            $banner= BannerLand::find($id);
            if (!$banner){
                return $this->ReturnError('404',__('message.notFound'));
            }
            $status=$banner->status=='off'? 'on':'off';
            $banner->update(['status'=>$status]);
            return $this->ReturnSuccess('200',__('message.updated'));

        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getCode());

        }
    }

}
