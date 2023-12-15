<?php

namespace App\Http\Controllers\Stripe;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Charge;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function checkout(Product $product)
    {
        return view('checkout', compact('product'));
    }

    public function charge(Request $request, Product $product)
    { 
        // No need to find the product again; it's automatically resolved via route model binding
        Stripe::setApiKey(config('services.stripe.secret'));
        $token = $request->input('stripeToken');
        $charge = Charge::create([
            'amount' => $product->price * 100,
            'currency' => $product->currency,
            'description' => $product->name,
            'source' => $token,
        ]);
        
        // Handle successful payment, update orders, etc.
        $product->update([
            'paid' => true,
        ]);
        
        return redirect()->route('product.index')->with('success', 'Payment successful!');
    }
}