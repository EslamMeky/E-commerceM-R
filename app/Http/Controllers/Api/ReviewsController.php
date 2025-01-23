<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Traits\GeneralTrait;
use App\Models\Products;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    use GeneralTrait;
    public function store(ReviewRequest $request, $productId)
    {
        try
        {
            $product = Products::findOrFail($productId);

            $review = Review::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'rating' =>$request->rating,
                'comment' => $request->comment,
            ]);

           return $this->ReturnSuccess(201,__('message.saved'));
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }

    }

    public function show($productId)
    {
        try
        {
            $product = Products::findOrFail($productId);

            // جلب التقييمات مع المستخدمين
            $reviews = Review::where('product_id', $product->id)
                ->with(['user'])
                ->paginate(pag);

            // حساب عدد المستخدمين الذين قاموا بالتقييم
            $uniqueUsersCount = Review::where('product_id', $product->id)->distinct('user_id')->count('user_id');

            // حساب المتوسط للتقييمات
            $averageRating = round(Review::where('product_id', $product->id)->avg('rating') ?? 0);


            $data=[
             'review'=>$reviews,
             'users_counts'=>$uniqueUsersCount,
               'averageRating'=>$averageRating,
           ];
            return $this->ReturnData('data', $data, '');
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }


    public function showAll()
    {
        try
        {
            $reviews = Review::with([
                'user',
                'product' => function($query) {
                    $query->select('id', 'name_ar', 'name_en', 'desc_ar', 'desc_en');
                }
            ])
                ->latest()
                ->paginate(pag);
            $userCount = Review::distinct('user_id')->whereNotNull('user_id')->count('user_id');

            // حساب المتوسط
            $averageRating = round(Review::avg('rating') ?? 0);

            $data=[
                'review'=>$reviews,
                'users_counts'=>$userCount,
                'averageRating'=>$averageRating,
            ];
            return $this->ReturnData('data', $data, '');
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());

        }

    }

}
