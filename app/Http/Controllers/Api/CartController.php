<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Http\Traits\GeneralTrait;
use App\Models\Cart;
use App\Models\Products;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use GeneralTrait;

    public function addToCart(CartRequest $request, $productId)
    {
        try {
            $product = Products::findOrFail($productId);

            if ($request->quantity > $product->stock) {
                return $this->ReturnError(400, __('message.out_of_stock'));
            }

            $cart = Cart::where('user_id', auth()->id())
                ->where('product_id', $productId)
                ->where('color', $request->color)
                ->where('size', $request->size)
                ->first();

            if ($cart) {

                $cart->update([
                    'quantity' => $cart->quantity + $request->quantity,
                ]);
            } else {

                Cart::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'color' => $request->color,
                    'size' => $request->size,
                ]);
            }

           return $this->ReturnSuccess(201,__('message.saved'));

        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function viewCart()
    {
        try
        {
            $local = app()->getLocale(); // الحصول على اللغة الحالية

            $cartItems = Cart::with([
                'product' => function($query) use ($local) {
                    $query->select(
                        'id',
                        'name_' . $local . ' as name',
                        'desc_' . $local . ' as desc',
                        'image',
                        'price_discount',
                        'stock',
                    );
                }
            ])->where('user_id', auth()->id())
                ->latest()
                ->get();


            $total = $cartItems->sum(function ($item) {
                return $item->product->price_discount * $item->quantity;
            });

            $data=[
                'cartItems'=>$cartItems,
                'total'=>$total
            ];
            return $this->ReturnData('data',$data,'');
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }

    }

    public function updateQuantity(Request $request)
    {
        try
        {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'color' => 'required|string|max:255',
                'size' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
            ]);


            $cart = Cart::where('user_id', auth()->id())
                ->where('product_id', $request->product_id)
                ->where('color', $request->color)
                ->where('size', $request->size)
                ->first();

            if ($cart) {

                $cart->update([
                    'quantity' => $request->quantity,
                ]);
                return $this->ReturnSuccess(201,__('message.updated'));
            }
            return $this->ReturnError(404,__('message.notFound'));

        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());
        }

    }

    public function removeFromCart($id)
    {
        try {
//            $request->validate([
//                'product_id' => 'required|exists:products,id',
//                'color' => 'required|string|max:255',
//                'size' => 'required|string|max:255',
//            ]);
//
//
//            $cart = Cart::where('user_id', auth()->id())
//                ->where('product_id', $request->product_id)
//                ->where('color', $request->color)
//                ->where('size', $request->size)
//                ->first();
//
//            if ($cart) {
//                $cart->delete();
//               return $this->ReturnSuccess(200,__('message.deleted'));
//            }
//
//           return $this->ReturnError(404,__('message.notFound'));
            $cart=Cart::where('id',$id)->first();
            if (!$cart)
            {
                return $this->ReturnError(404,__('message.notFound'));
            }
            $cart->delete();
            return $this->ReturnSuccess(200,__('message.deleted'));
        }
        catch (\Exception $ex)
        {
            return $this->ReturnError($ex->getCode(),$ex->getMessage());

        }
    }


}
