<?php

namespace Turing\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Turing\Http\Requests\AddProductRequest;
use Turing\Models\Product;
use Turing\Services\ShoppingCartServiceInterface;

class ShoppingCartController extends Controller
{
    /**
     * Add new item in shopping cart.
     *
     *
     * @param AddProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProduct(AddProductRequest $request)
    {
        try {

            return response()->json([
                'success' => true,
                'cart' => resolve(ShoppingCartServiceInterface::class)
                    ->updateCart(
                        Auth::user(),
                        Product::find($request->get('product_id')),
                        $request->all()
                    )
            ]);

        } catch (\Throwable $e) {
            Log::error('Product add', ['e' => $e]);
            return response()->json(['success' => false], 500);

        }
    }
}
