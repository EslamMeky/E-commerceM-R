<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact_usRequest;
use App\Http\Traits\GeneralTrait;
use App\Models\Contact_us;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ContactUsController extends Controller
{
    use GeneralTrait;

    public function save(Contact_usRequest $request)
    {
        try
        {

            Contact_us::create([
                'tittle_ar'=>$request->tittle_ar,
                'tittle_en'=>$request->tittle_en,
                'desc_ar'=>$request->desc_ar,
                'desc_en'=>$request->desc_en,
                'name_btn_ar'=>$request->name_btn_ar,
                'name_btn_en'=>$request->name_btn_en,
                'link_btn'=>$request->link_btn,
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
            $contact=Contact_us::selection()->latest()->paginate(10);
            return $this->ReturnData('contactUs',$contact,"");
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
            $contact=Contact_us::latest()->paginate(pag);
            return $this->ReturnData('contactUs',$contact,"");
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
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
            {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $contact = Contact_us::findOrFail($id);
            if (!$contact)
            {
                return $this->ReturnError('404',__('message.notFound'));
            }


            $contact->update([
                'tittle_ar' => $request->tittle_ar,
                'tittle_en' => $request->tittle_en,
                'desc_ar' => $request->desc_ar,
                'desc_en' => $request->desc_en,
                'name_btn_ar'=>$request->name_btn_ar,
                'name_btn_en'=>$request->name_btn_en,
                'link_btn'=>$request->link_btn,

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
            $contact= Contact_us::find($id);
            if (!$contact)
            {
                return $this->ReturnError(404,__('message.notFound'));
            }
            $contact->delete();
            return $this->ReturnSuccess(200,__('message.deleted'));

        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getCode());
        }
    }

}
