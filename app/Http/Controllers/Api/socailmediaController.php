<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocailMediaRequest;
use App\Http\Traits\GeneralTrait;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class socailmediaController extends Controller
{
    use GeneralTrait;
    public function save(SocailMediaRequest $request)
    {
        try {


            // حفظ البيانات في جدول السوشيال ميديا
            SocialMedia::create([
                'face' => $request->face,
                'insta' => $request->insta,
                'tiktok' => $request->tiktok,
                'twitter' => $request->twitter,
                'linkedIn' => $request->linkedIn,
            ]);

            return $this->ReturnSuccess(200, __('message.saved'));

        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update(SocailMediaRequest $request, $id)
    {
        try {

            $socialMedia = SocialMedia::find($id);
            if (!$socialMedia) {
                return $this->ReturnError(404, __('message.notFound'));
            }
            // تحديث البيانات
            $socialMedia->update([
                'face' => $request->face,
                'insta' => $request->insta,
                'tiktok' => $request->tiktok,
                'twitter' => $request->twitter,
                'linkedIn' => $request->linkedIn,
            ]);

            return $this->ReturnSuccess(200, __('message.updated'));

        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function show()
    {
        try {
            // العثور على السوشيال ميديا باستخدام ID
            $socialMedia = SocialMedia::Selection()->latest()->get();

            return $this->ReturnData('socialMedia', $socialMedia, '');

        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }



}
