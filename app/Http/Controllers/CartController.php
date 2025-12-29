<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::with(['photos', 'category'])->find($productId);
            if ($product) {
                $itemTotal = $product->price * $item['quantity'];
                $total += $itemTotal;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemTotal,
                ];
            }
        }

        return view('cart', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    /**
     * Add product to cart
     */
    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        if ($product->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available'
            ], 400);
        }

        $cart = Session::get('cart', []);
        $quantity = $request->input('quantity', 1);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'quantity' => $quantity,
            ];
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cartCount' => $this->getCartCount(),
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $productId)
    {
        $cart = Session::get('cart', []);
        $quantity = (int) $request->input('quantity', 1);

        if ($quantity <= 0) {
            return $this->remove($productId);
        }

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            Session::put('cart', $cart);

            $product = Product::find($productId);
            $subtotal = $product ? $product->price * $quantity : 0;

            $cartTotal = $this->getCartTotalWithCurrency();
            
            return response()->json([
                'success' => true,
                'message' => 'Cart updated',
                'subtotal' => $product->currency . number_format($subtotal, 2),
                'cartCount' => $this->getCartCount(),
                'total' => $cartTotal['currency'] . $cartTotal['total'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart'
        ], 404);
    }

    /**
     * Remove product from cart
     */
    public function remove($productId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);

            $cartTotal = $this->getCartTotalWithCurrency();
            
            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart',
                'cartCount' => $this->getCartCount(),
                'total' => $cartTotal['currency'] . $cartTotal['total'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart'
        ], 404);
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        Session::forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
            'cartCount' => 0,
        ]);
    }

    /**
     * Get cart count (for AJAX requests)
     */
    public function count()
    {
        $cartTotal = $this->getCartTotalWithCurrency();
        
        return response()->json([
            'count' => $this->getCartCount(),
            'total' => $cartTotal['currency'] . $cartTotal['total'],
        ]);
    }

    /**
     * Get total number of items in cart
     */
    private function getCartCount()
    {
        $cart = Session::get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }

    /**
     * Get cart total
     */
    private function getCartTotal()
    {
        $cart = Session::get('cart', []);
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $total += $product->price * $item['quantity'];
            }
        }

        return number_format($total, 2);
    }

    /**
     * Get cart total with currency
     */
    private function getCartTotalWithCurrency()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        $currency = '$';

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $total += $product->price * $item['quantity'];
                if (!$currency || $currency === '$') {
                    $currency = $product->currency;
                }
            }
        }

        return [
            'total' => number_format($total, 2),
            'currency' => $currency
        ];
    }
}

