<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Traits\GeneralTrait;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use GeneralTrait;

    public function save(CategoryRequest $request){
        try
        {
            $pathImage=uploadImage('Categories',$request->image);
            Category::create([
             'name_ar'=>$request->name_ar,
             'name_en'=>$request->name_en,
             'image'=>$pathImage,
             ]);
         return $this->ReturnSuccess(200,__('message.saved'));
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function singleCategory($category_id)
    {
        try
        {
           $category=Category::where('id',$category_id)->first();
           if (!$category)
           {
               return $this->ReturnError(404,__('message.notFound'));
           }
           return $this->ReturnData('category',$category,'');

        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function allCategories()
    {
        try
        {
            $locale = app()->getLocale();
            $category=Category::select([
                'id',
                'name_'.$locale,
                'image',
                'created_at',
                'updated_at'
            ])->latest()->get();
            if (!$category)
            {
                return $this->ReturnError(404,__('message.notFound'));
            }
            return $this->ReturnData('categories',$category,'');

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

            $category=Category::latest()->paginate(pag);
            if (!$category)
            {
                return $this->ReturnError(404,__('message.notFound'));
            }
            return $this->ReturnData('categories',$category,'');

        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }
    public function update(Request $request,$category_id)
    {
        try
        {
            $rules = [
                'name_ar' => 'required',
                'name_en' => 'required',

            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
            {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $category= Category::find($category_id);
            if (!$category)
            {
                return $this->ReturnError('404',__('message.notFound'));
            }
            $category->where('id',$category_id)->update([
                'name_ar' => $request->name_ar,
                'name_en' => $request->name_en,
            ]);

            if ($request->hasFile('image'))
            {
                $photoPath = parse_url($category->image, PHP_URL_PATH);
                $photoPath = ltrim($photoPath, '/');
                $oldImagePath = public_path($photoPath);

                if ($category->image && file_exists($oldImagePath))
                {

                    unlink($oldImagePath);
                }
                $pathFile=uploadImage('Categories',$request->image);

                $category->where('id',$category_id)->update([
                    'image' => $pathFile,
                ]);
            }
            return $this->ReturnSuccess(200,__('message.updated'));
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function delete($category_id)
    {
        try
        {
            $category= Category::find($category_id);
            if (!$category)
            {
                return $this->ReturnError(404,__('message.notFound'));
            }
            if ($category->image != null){
                $image=Str::after($category->image,'assets/');
                $image=base_path('public/assets/'.$image);
                unlink($image);
                $category->delete();
            }
            else
                $category->delete();
            return $this->ReturnSuccess(200,__('message.deleted'));

        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getCode());
        }
    }



}
