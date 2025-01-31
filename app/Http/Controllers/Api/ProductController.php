<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\productRequest;
use App\Http\Traits\GeneralTrait;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use GeneralTrait;

    public function save(productRequest $request)
    {
        try {
            $pathImage = uploadImage('Products', $request->image);

            // رفع الصور الإضافية (otherImage)
            $otherImagesPaths = [];
            if ($request->hasFile('OtherImage')) {
                foreach ($request->OtherImage as $image) {
                    $otherImagesPaths[] = uploadImage('OtherImagesProducts', $image);
                }
            }

            Products::create([
                'category_id' => $request->category_id,
                'name_ar' => $request->name_ar,
                'name_en' => $request->name_en,
                'image' => $pathImage,
                'OtherImage' => json_encode($otherImagesPaths),
                'desc_ar' => $request->desc_ar,
                'desc_en' => $request->desc_en,
                'main_price' => $request->main_price,
                'price_discount' => $request->price_discount,
                'colors' => json_encode($request->colors), // تحويل المصفوفة إلى JSON
                'sizes' => json_encode($request->sizes), // تحويل المصفوفة إلى JSON
                'stock' => $request->stock,
                'barcode' => $request->barcode,
                'out_of_stock' => $request->stock <= 0 ? 1 : 0,
            ]);

            return $this->ReturnSuccess(200, __('message.saved'));
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

//    public function singleProduct($name_product)
//    {
//        try {
//            $locale = app()->getLocale();
//            $product = Products::with(['category','reviews'])->select([
//                'id',
//                'category_id',
//                'name_' . $locale . ' as name',
//                'image',
//                'OtherImage',
//                'desc_' . $locale . ' as desc',
//                'main_price',
//                'price_discount',
//                'colors',
//                'sizes',
//                'stock',
//                'out_of_stock',
//                'created_at',
//                'updated_at'
//            ])->where('name_' . $locale, $name_product)->first();
//
//            // إذا لم يتم العثور على المنتج
//            if (!$product) {
//                return $this->ReturnError(404, __('message.notFound'));
//            }
//            // إذا كانت الصور الإضافية موجودة، تحويلها من JSON إلى Array
//            if ($product->OtherImage) {
//                $product->OtherImage = $product->OtherImage; // إذا كانت مخزنة كـ Array
//            }
//
//            // إرجاع المنتج مع الألوان والمقاسات كـ Arrays
//            $product->colors = $product->colors; // الألوان كـ Array
//            $product->sizes = $product->sizes; // المقاسات كـ Array
//
//            $reviewCount = $product->reviews()->count();
//            $averageRating = $product->reviews->isNotEmpty()
//                ? round($product->reviews()->avg('rating'), 2)
//                : 0;
//
//            $data = [
//                'product' => $product,
//                'average_rating' => $averageRating,
//                'review_count' => $reviewCount, // عدد المراجعات
//            ];
//
//            return $this->ReturnData('data', $data, '');
//        } catch (\Exception $ex) {
//            return $this->ReturnError($ex->getCode(), $ex->getMessage());
//
//        }
//
//    }
    public function singleProductWithRelated($name_product)
    {
        try {
            $locale = app()->getLocale();

            // جلب المنتج الرئيسي
            $product = Products::with(['category', 'reviews'])->select([
                'id',
                'category_id',
                'name_' . $locale . ' as name',
                'image',
                'OtherImage',
                'desc_' . $locale . ' as desc',
                'main_price',
                'price_discount',
                'colors',
                'sizes',
                'stock',
                'out_of_stock',
                'created_at',
                'updated_at'
            ])->where('name_' . $locale, $name_product)->first();

            // إذا لم يتم العثور على المنتج
            if (!$product) {
                return $this->ReturnError(404, __('message.notFound'));
            }

            // معالجة الصور الإضافية
            if ($product->OtherImage) {
                $product->OtherImage = $product->OtherImage; // إذا كانت مخزنة كـ Array
            }

            // معالجة الألوان والمقاسات
            $product->colors = $product->colors;
            $product->sizes = $product->sizes;

            // حساب متوسط التقييم وعدد المراجعات
            $reviewCount = $product->reviews()->count();
            $averageRating = $product->reviews->isNotEmpty()
                ? round($product->reviews()->avg('rating'), 2)
                : 0;

            // جلب المنتجات ذات الصلة
            $relatedProducts = Products::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id) // استثناء المنتج الأساسي
                ->select([
                    'id',
                    'category_id',
                    'name_' . $locale . ' as name',
                    'image',
                    'OtherImage',
                    'main_price',
                    'price_discount',
                    'colors',
                    'sizes',
                    'stock',
                    'out_of_stock',
                    'created_at',
                    'updated_at'
                ])
                ->latest()
                ->take(5)
                ->get();

            // معالجة المنتجات ذات الصلة
            foreach ($relatedProducts as $relatedProduct) {
                if ($relatedProduct->OtherImage) {
                    $relatedProduct->OtherImage = $relatedProduct->OtherImage;
                }

                if ($relatedProduct->colors) {
                    $relatedProduct->colors = json_decode($relatedProduct->colors, true);
                }

                if ($relatedProduct->sizes) {
                    $relatedProduct->sizes = json_decode($relatedProduct->sizes, true);
                }
            }

            // إعداد البيانات للإرجاع
            $data = [
                'product' => $product,
                'average_rating' => $averageRating,
                'review_count' => $reviewCount,
                'related_products' => $relatedProducts
            ];

            return $this->ReturnData('data', $data, '');
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function allProducts(Request $request)
    {
        try {
            $locale = app()->getLocale();

            // الحصول على المدخلات من الـ Request
            $searchTerm = $request->input('search'); // اسم المنتج أو الفئة
            $category = $request->input('category'); // الفئة
            $color = $request->input('color'); // اللون
            $size = $request->input('size'); // المقاس
            $priceOrder = $request->input('price_order'); // ترتيب السعر (asc أو desc)

            // بناء الاستعلام
            $query = Products::with(['category' => function ($query) use ($locale) {
                $query->select(['id', 'name_' . $locale, 'image']); // إرجاع اسم الفئة بناءً على اللغة
            }])->Selection();

            // إضافة شرط البحث في اسم المنتج
            if ($searchTerm) {
                $query->where('name_' . $locale, 'like', '%' . $searchTerm . '%');
            }

            // إضافة شرط البحث في الفئة
            if ($category) {
                $query->whereHas('category', function ($query) use ($category, $locale) {
                    $query->where('name_' . $locale, 'like', '%' . $category . '%');
                });
            }

            // إضافة شرط البحث في اللون
            if ($color) {
                $query->where('colors', 'like', '%' . $color . '%');
            }

            // إضافة شرط البحث في المقاس
            if ($size) {
                $query->where('sizes', 'like', '%' . $size . '%');
            }

            // إضافة ترتيب السعر إذا تم تحديده
            if ($priceOrder) {
                if ($priceOrder == 'asc') {
                    $query->orderBy('price_discount', 'asc');
                } elseif ($priceOrder == 'desc') {
                    $query->orderBy('price_discount', 'desc');
                }
            }

            // جلب المنتجات بناءً على الاستعلام
            $products = $query->latest()->paginate(pag);

            foreach ($products as $product) {
                // حساب التقييمات
                $reviewCount = $product->reviews()->count();
                $product->average_rating = $reviewCount > 0 ? round($product->reviews()->avg('rating'), 2) : 0;
                $product->review_count = $reviewCount;

                // إضافة المنتجات ذات الصلة
                $relatedProducts = Products::where('category_id', $product->category_id)
                    ->where('id', '!=', $product->id) // استثناء المنتج نفسه
                    ->Selection()
                    ->latest()
                    ->take(5)
                    ->get();

                // معالجة الصور الإضافية والألوان والمقاسات
                foreach ($relatedProducts as $relatedProduct) {
                    if ($relatedProduct->OtherImage) {
                        $relatedProduct->OtherImage = $relatedProduct->OtherImage; // إذا كانت مخزنة كـ Array
                    }

                    // تحويل الألوان والمقاسات إلى Arrays
                    $relatedProduct->colors = $relatedProduct->colors; // الألوان كـ Array
                    $relatedProduct->sizes = $relatedProduct->sizes; // المقاسات كـ Array
                }

                // إضافة المنتجات ذات الصلة لكل منتج
                $product->related_products = $relatedProducts;
            }

            // تحويل الصور الإضافية والألوان والمقاسات إلى Arrays لكل منتج
            foreach ($products as $product) {
                if ($product->OtherImage) {
                    $product->OtherImage = $product->OtherImage; // إذا كانت مخزنة كـ Array
                }

                // تحويل الألوان والمقاسات إلى Arrays
                $product->colors = $product->colors; // الألوان كـ Array
                $product->sizes = $product->sizes; // المقاسات كـ Array
            }

            return $this->ReturnData('products', $products, '');

        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function showAll()
    {
        try {


            $products = Products::with(['category' ])->latest()->paginate(pag);

            // تحويل الصور الإضافية والألوان والمقاسات إلى Arrays لكل منتج
            foreach ($products as $product) {
                // إذا كانت الصور الإضافية موجودة، تحويلها من JSON إلى Array
                if ($product->OtherImage) {
                    $product->OtherImage = $product->OtherImage; // إذا كانت مخزنة كـ Array
                }

                // تحويل الألوان والمقاسات إلى Arrays
                $product->colors = $product->colors; // الألوان كـ Array
                $product->sizes = $product->sizes; // المقاسات كـ Array
            }

            return $this->ReturnData('products', $products, '');
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update(Request $request, $product_id)
    {
        try {
            $rules = [
                'category_id' => 'required',
                'name_ar' => 'required',
                'name_en' => 'required',
                'desc_ar' => 'required',
                'desc_en' => 'required',
                'main_price' => 'required',
                'price_discount' => 'required',
                'colors' => 'required',
                'sizes' => 'required',
                'stock' => 'required',
                'barcode' => 'required',
//                'out_of_stock'=>'required'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            // جلب المنتج بناءً على المعرف
            $product = Products::find($product_id);


            if (!$product) {
                return $this->ReturnError(404, __('message.notFound'));
            }


            if ($request->hasFile('image')) {
                // حذف الصورة القديمة إذا كانت موجودة
                $photoPath = parse_url($product->image, PHP_URL_PATH);
                $photoPath = ltrim($photoPath, '/');
                $oldImagePath = public_path($photoPath);

                if ($product->image && file_exists($oldImagePath)) {
                    unlink($oldImagePath); // حذف الصورة القديمة
                }

                // رفع الصورة الجديدة
                $pathImage = uploadImage('Products', $request->image);
                $product->image = $pathImage; // تحديث الصورة الرئيسية
            }

            // تحديث الصور الإضافية (OtherImage) إذا تم رفع صور جديدة
            if ($request->hasFile('OtherImage')) {
                $otherImagesPaths = [];
                foreach ($request->OtherImage as $image) {
                    // رفع الصورة الجديدة
                    $otherImagesPaths[] = uploadImage('OtherImagesProducts', $image);
                }
                // تخزين الصور الإضافية في قاعدة البيانات
                $product->OtherImage = json_encode($otherImagesPaths); // تخزين الصور كـ JSON
            }

            // تحديث باقي الحقول فقط إذا كانت موجودة أو تم تغييرها
            $product->update([
                'category_id' => $request->category_id,
                'name_ar' => $request->name_ar,
                'name_en' => $request->name_en,
                'desc_ar' => $request->desc_ar,
                'desc_en' => $request->desc_en,
                'main_price' => $request->main_price,
                'price_discount' => $request->price_discount,
                'colors' => json_encode($request->colors), // تحويل المصفوفة إلى JSON
                'sizes' => json_encode($request->sizes), // تحويل المصفوفة إلى JSON
                'stock' => $request->stock,
                'barcode' => $request->barcode,
                'out_of_stock' => $request->stock <= 0 ? 1 : 0,
            ]);

            return $this->ReturnSuccess(200, __('message.updated'));
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }


    public function delete($product_id)
    {
        try {
            // جلب المنتج بناءً على المعرف
            $product = Products::find($product_id);

            // إذا لم يتم العثور على المنتج
            if (!$product) {
                return $this->ReturnError(404, __('message.notFound'));
            }

            // حذف الصورة الرئيسية إذا كانت موجودة
            if ($product->image != null) {
                $image = Str::after($product->image, 'assets/');
                $image = base_path('public/assets/' . $image);
                if (file_exists($image)) {
                    unlink($image); // حذف الصورة الرئيسية
                }
            }

            // حذف الصور الإضافية إذا كانت موجودة
            if ($product->OtherImage != null) {
                // تحقق إذا كانت القيمة بالفعل مصفوفة
                $otherImages = is_array($product->OtherImage) ? $product->OtherImage : json_decode($product->OtherImage, true);

                foreach ($otherImages as $image) {
                    $imagePath = base_path('public/assets/' . Str::after($image, 'assets/'));
                    if (file_exists($imagePath)) {
                        unlink($imagePath); // حذف الصور الإضافية
                    }
                }
            }

            // حذف المنتج من قاعدة البيانات
            $product->delete();

            return $this->ReturnSuccess(200, __('message.deleted'));
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function deleteAll()
    {
        try {
            // جلب جميع المنتجات
            $products = Products::all();

            // التحقق إذا كانت هناك منتجات
            if ($products->isEmpty()) {
                return $this->ReturnError(404, __('message.notFound'));
            }

            // حذف الصور المرتبطة بكل منتج
            foreach ($products as $product) {
                // حذف الصورة الرئيسية
                if ($product->image != null) {
                    $imagePath = base_path('public/assets/' . Str::after($product->image, 'assets/'));
                    if (file_exists($imagePath)) {
                        unlink($imagePath); // حذف الصورة الرئيسية
                    }
                }

                // حذف الصور الإضافية
                if ($product->OtherImage != null) {
                    $otherImages = is_array($product->OtherImage) ? $product->OtherImage : json_decode($product->OtherImage, true);
                    foreach ($otherImages as $image) {
                        $imagePath = base_path('public/assets/' . Str::after($image, 'assets/'));
                        if (file_exists($imagePath)) {
                            unlink($imagePath); // حذف الصور الإضافية
                        }
                    }
                }

                // حذف المنتج من قاعدة البيانات
                $product->delete();
            }

            return $this->ReturnSuccess(200, __('message.deleted'));
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }


    public function relatedProducts($product_id)
    {
        try {
            // جلب المنتج الأساسي بناءً على المعرف
            $product = Products::find($product_id);
            $locale = app()->getLocale();

            // إذا لم يتم العثور على المنتج
            if (!$product) {
                return $this->ReturnError(404, __('message.notFound'));
            }

            // جلب المنتجات ذات الصلة بناءً على الفئة (category_id)
            $relatedProducts = Products::where('category_id', $product->category_id)
                ->where('id', '!=', $product_id) // استثناء المنتج الأساسي
                ->Selection()
                ->latest()
                ->take(5)
                ->get();

            // معالجة الصور والألوان والمقاسات
            foreach ($relatedProducts as $relatedProduct) {
                // إذا كانت الصور الإضافية موجودة، تحويلها من JSON إلى Array
                if ($relatedProduct->OtherImage) {
                    $relatedProduct->OtherImage = $relatedProduct->OtherImage; // إذا كانت مخزنة كـ Array
                }

                if ($relatedProduct->colors) {
                    $relatedProduct->colors = json_decode($relatedProduct->colors, true);
                }
                if ($relatedProduct->sizes) {
                    $relatedProduct->sizes = json_decode($relatedProduct->sizes, true);
                }
            }

            // إذا لم تكن هناك منتجات ذات صلة
//            if ($relatedProducts->isEmpty()) {
//                return $this->ReturnError(404, __('message.noRelatedProducts'));
//            }

            return $this->ReturnData('relatedProducts', $relatedProducts, "");
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }


    public function searchProducts(Request $request)
    {
        try {
            $query = Products::query();

            // البحث عن الاسم بالعربية (name_ar)
            if ($request->has('name') && $request->name != '') {
                $searchTerm = $request->name;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name_ar', 'like', '%' . $searchTerm . '%')
                        ->orWhere('name_en', 'like', '%' . $searchTerm . '%');
                });
            }

            // البحث عن اسم الفئة في جدول categories (name_ar أو name_en)
            if ($request->has('category_name') && $request->category_name != '') {
                $query->join('categories', 'products.category_id', '=', 'categories.id')
                    ->where(function ($q) use ($request) {
                        $q->where('categories.name_ar', 'like', '%' . $request->category_name . '%')
                            ->orWhere('categories.name_en', 'like', '%' . $request->category_name . '%');
                    });
            }


            $products = $query->select('products.*')->get();

            return $this->ReturnData('product',$products,'');
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }


    public function showOutOfStock()
    {
        try {


            $products = Products::with(['category' ])
                ->where('stock', '<=', 0)
                ->latest()->paginate(pag);

            // تحويل الصور الإضافية والألوان والمقاسات إلى Arrays لكل منتج
            foreach ($products as $product) {
                // إذا كانت الصور الإضافية موجودة، تحويلها من JSON إلى Array
                if ($product->OtherImage) {
                    $product->OtherImage = $product->OtherImage; // إذا كانت مخزنة كـ Array
                }

                // تحويل الألوان والمقاسات إلى Arrays
                $product->colors = $product->colors; // الألوان كـ Array
                $product->sizes = $product->sizes; // المقاسات كـ Array
            }

            return $this->ReturnData('products', $products, '');
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function getAllColors()
    {
        try {
            // جلب كل القيم من عمود الألوان
            $products = Products::pluck('colors');

            $colorCounts = [];

            foreach ($products as $productColors) {
                if ($productColors) {
                    $colorsArray = json_decode($productColors, true); // تحويل JSON إلى مصفوفة
                    if (is_array($colorsArray)) {
                        foreach ($colorsArray as $color) {
                            if (!isset($colorCounts[$color])) {
                                $colorCounts[$color] = 1;
                            } else {
                                $colorCounts[$color]++;
                            }
                        }
                    }
                }
            }

            return $this->ReturnData('colors', $colorCounts, 'Colors retrieved successfully');
        } catch (\Exception $ex) {
            return $this->ReturnError($ex->getCode(), $ex->getMessage());
        }
    }


}
