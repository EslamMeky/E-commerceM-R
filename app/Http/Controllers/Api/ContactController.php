<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    use GeneralTrait;

    public function index()
    {
        try
        {
           $contact= Contact::latest()->paginate(pag);
           return $this->ReturnData('contact',$contact,'');
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function save(Request $request)
    {
        try
        {
            $rules = [
                'name' => 'required',
                'message' => 'required',
                'email' => 'required|email',

            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
            {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            Contact::create([
                'name'=>$request->name,
                'message'=>$request->message,
                'email'=>$request->email,
            ]);
            return $this->ReturnSuccess(200,__('message.saved'));
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());

        }
    }
}
