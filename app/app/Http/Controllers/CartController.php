<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Http\Requests\CartProductRequest;
use App\Contracts\CartServiceInterface;

/**
 * @group Cart Management
 *
 * APIs for managing carts
 */
class CartController extends Controller
{

    protected $cartService;

    /**
     * Constructs a new CartController object
     */
    public function __construct(CartServiceInterface $cartService) {
        $this->cartService = $cartService;
    }


    /** 
     * GET api/cart/products
     * 
     * Return a json with the content of the cart, count of items and total price
     * 
     * @response scenario=success {
     * "products": [
     *   {
     *       "id": 1,
     *       "reference": "SKU00001",
     *       "name": "MUNDAKA",
     *       "description": "Gafas de sol MUNDAKA",
     *       "price": 35,
     *       "pivot": {
     *           "cart_id": 52,
     *           "product_id": 1,
     *           "quantity": 1
     *       }
     *   }
     * ],
     * "count": 1,
     * "total": 35
     * }
     * @response status=401 scenario="unauthenticated" {"message": "Unauthenticated"}
     * 
     * @return Illuminate\Http\JsonResponse json with the content of the cart, count of items and total price
     */
    public function list() : JsonResponse
    {
        return response()->json([
            'products' => $this->cartService->content(),
            'count' => $this->cartService->count(),
            'total' => $this->cartService->total(),
        ]);
    }

    /**
     * GET api/cart/products/count
     * 
     * Return the number of products in the cart 
     * 
     * @response scenario=success { "count": 1 }
     * @response status=401 scenario="unauthenticated" {"message": "Unauthenticated"}
     * 
     * @return Illuminate\Http\JsonResponse number of products
     */
    public function count() : JsonResponse
    {
        return response()->json([
            'count' => $this->cartService->count(),
        ]);
    }

    /**
     * GET api/cart/products/total
     * 
     * Return the total price of products in the cart 
     * 
     * @response scenario=success { "total": 35 }
     * @response status=401 scenario="unauthenticated" {"message": "Unauthenticated"}
     * 
     * @return Illuminate\Http\JsonResponse total price of products in the cart
     */
    public function total() : JsonResponse
    {
        return response()->json([
            'total' => $this->cartService->total(),
        ]);
    }

    /**
     * POST api/cart/product
     * 
     * Add a new product to the cart
     * 
     * @response scenario=success { "message": Success }
     * @response status=401 scenario="unauthenticated" {"message": "Unauthenticated"}
     * @response status=422 scenario="invalid parameters" {"message": "The selected id is invalid"}
     * 
     * @return Illuminate\Http\JsonResponse status 200 on success. status 400-500 on error
     */
    public function add(CartProductRequest $request) : JsonResponse 
    {
        $this->cartService->add($request->id, $request->quantity);
        return response()->json(['message' => 'Success'], 200);
    }

    /** 
     * PUT api/cart/product/{id}
     * 
     * Update the quantity of a product in the cart
     * 
     * @bodyParam id int required The ID of the product. Example: 9
     * @response scenario=success { "message": Success }
     * @response status=401 scenario="unauthenticated" {"message": "Unauthenticated"}
     * @response status=422 scenario="invalid parameters" {"message": "The selected id is invalid"}
     * 
     * @param int $id the id of the product
     * @return Illuminate\Http\JsonResponse status 200 on success. status 400-500 on error
     */
    public function update($id, CartProductRequest $request) : JsonResponse 
    {
        $this->cartService->update($request->id, $request->quantity);
        return response()->json(['message' => 'Success'], 200);
    }

    /** 
     * DELETE api/cart/product/{id}
     * 
     * Delete a product from the cart
     * 
     * @bodyParam id int required The ID of the product. Example: 9
     * @response scenario=success { "message": Success }
     * @response status=401 scenario="unauthenticated" {"message": "Unauthenticated"}
     * @response status=422 scenario="invalid parameters" {"message": "The selected id is invalid"}
     * 
     * @param int $id the id of the product
     * @return Illuminate\Http\JsonResponse status 200 on success. status 400-500 on error
     */
    public function remove($id) : JsonResponse 
    {
        return $this->cartService->remove($id) ? response()->json(['message' => 'Success'], 200) : response()->json(['message' => 'Error'], 404);
    }

    /**
     * DELETE api/cart/products
     * 
     * Delete all products from the cart
     * 
     * @response scenario=success { "message": Success }
     * @response status=401 scenario="unauthenticated" {"message": "Unauthenticated"}
     * 
     * @return Illuminate\Http\JsonResponse status 200 on success. status 400-500 on error
     */
    public function empty() : JsonResponse
    {
        $this->cartService->empty();
        return response()->json(['message' => 'Success'], 200);
    }

    /**
     * POST api/cart/confirm
     * 
     * Confirm the cart. Creates a new Order with the contents of the cart and empties the cart.
     * Only registered and logged in users can confirm the cart
     * 
     * @response scenario=success { "message": Success }
     * @response status=401 scenario="unauthenticated" {"message": "Unauthenticated"}
     * @response status=403 scenario="unauthorized" {"message": "Unauthorized"}
     * 
     * @return Illuminate\Http\JsonResponse status 200 on success. status 400-500 on error
     */
    public function confirm(): JsonResponse 
    {
        return $this->cartService->confirm() ? 
            response()->json(['message' => 'Success'], 200) : 
            response()->json(['message' => 'Unautorized'], 403);
    }
    

}