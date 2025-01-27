<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Subscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscribeController extends Controller
{
    use GeneralTrait;

    public function index()
    {
        try
        {
            $subscribe= Subscribe::latest()->paginate(pag);
            return $this->ReturnData('subscribe',$subscribe,'');
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
                'email' => 'required|email',

            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
            {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            Subscribe::create([
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
