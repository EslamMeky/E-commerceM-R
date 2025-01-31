<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImagesRequest;
use App\Http\Traits\GeneralTrait;
use App\Models\ImagesBanner;
use Illuminate\Http\Request;

class imagesbannerController extends Controller
{
    use GeneralTrait;
    public function save(ImagesRequest $request)
    {
        try {
            // تحقق من وجود الملفات
            $product = $request->hasFile('product') ? uploadImage('ImagesBanner', $request->product) : null;
            $about = $request->hasFile('about') ? uploadImage('ImagesBanner', $request->about) : null;
            $contact = $request->hasFile('contact') ? uploadImage('ImagesBanner', $request->contact) : null;
            $profile = $request->hasFile('profile') ? uploadImage('ImagesBanner', $request->profile) : null;

            // حفظ البيانات في قاعدة البيانات
            ImagesBanner::create([
                'product' => $product,
                'about' => $about,
                'contact' => $contact,
                'profile' => $profile,
            ]);

            return $this->ReturnSuccess(200, __('message.saved'));
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // البحث عن السجل المطلوب التحديث بناءً على الـ ID
            $imageBanner = ImagesBanner::find($id);

            // التحقق إذا كان السجل موجود
            if (!$imageBanner) {
                return $this->ReturnError(404, __('message.notFound'));
            }

            if ($request->hasFile('product')) {
                $photoPath = parse_url($imageBanner->product, PHP_URL_PATH);
                $photoPath = ltrim($photoPath, '/');
                $oldImagePath = public_path($photoPath);

                if ($imageBanner->product && file_exists($oldImagePath)) {

                    unlink($oldImagePath);
                }
                $product = uploadImage('ImagesBanner', $request->product);

                $imageBanner->where('id', $id)->update([
                    'product' => $product,
                ]);
            }

            if ($request->hasFile('about')) {
                $photoPath = parse_url($imageBanner->about, PHP_URL_PATH);
                $photoPath = ltrim($photoPath, '/');
                $oldImagePath = public_path($photoPath);

                if ($imageBanner->about && file_exists($oldImagePath)) {

                    unlink($oldImagePath);
                }
                $about = uploadImage('ImagesBanner', $request->about);

                $imageBanner->where('id', $id)->update([
                    'about' => $about,
                ]);
            }

            if ($request->hasFile('contact')) {
                $photoPath = parse_url($imageBanner->contact, PHP_URL_PATH);
                $photoPath = ltrim($photoPath, '/');
                $oldImagePath = public_path($photoPath);

                if ($imageBanner->contact && file_exists($oldImagePath)) {

                    unlink($oldImagePath);
                }
                $contact = uploadImage('ImagesBanner', $request->contact);

                $imageBanner->where('id', $id)->update([
                    'contact' => $contact,
                ]);
            }

            if ($request->hasFile('profile')) {
                $photoPath = parse_url($imageBanner->profile, PHP_URL_PATH);
                $photoPath = ltrim($photoPath, '/');
                $oldImagePath = public_path($photoPath);

                if ($imageBanner->profile && file_exists($oldImagePath)) {

                    unlink($oldImagePath);
                }
                $profile = uploadImage('ImagesBanner', $request->profile);

                $imageBanner->where('id', $id)->update([
                    'profile' => $profile,
                ]);
            }

            return $this->ReturnSuccess(200, __('message.updated'));
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function show()
    {
        try {

            $imageBanner = ImagesBanner::Selection()->latest()->get();

            return $this->ReturnData('imageBanner', $imageBanner, '');
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

}
