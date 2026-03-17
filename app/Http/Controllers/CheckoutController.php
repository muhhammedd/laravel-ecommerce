<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Helpers\Cart;
use App\Mail\NewOrderEmail;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $user = $request->user();

        // Validate basic shipping fields
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'address1' => 'required',
            'city' => 'required',
        ]);

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        if (empty($products)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $orderItems = [];
        $totalPrice = 0;

        DB::beginTransaction();

        // Check availability
        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            if ($product->quantity !== null && $product->quantity < $quantity) {
                $message = match ($product->quantity) {
                    0 => 'The product "'.$product->title.'" is out of stock',
                    1 => 'There is only one item left for product "'.$product->title,
                    default => 'There are only ' . $product->quantity . ' items left for product "'.$product->title,
                };
                return redirect()->back()->with('error', $message);
            }
        }

        // Prepare order items
        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * $quantity;
            
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price
            ];

            if ($product->quantity !== null) {
                $product->quantity -= $quantity;
                $product->save();
            }
        }

        try {
            // Create Order
            $orderData = [
                'total_price' => $totalPrice,
                'status' => OrderStatus::Pending->value, // Cash on Delivery default
                'created_by' => $user ? $user->id : null,
                'updated_by' => $user ? $user->id : null,
            ];
            $order = Order::create($orderData);

            // Create Order Items
            foreach ($orderItems as $orderItem) {
                $orderItem['order_id'] = $order->id;
                OrderItem::create($orderItem);
            }

            // Create Payment (COD)
            $paymentData = [
                'order_id' => $order->id,
                'amount' => $totalPrice,
                'status' => \App\Enums\PaymentStatus::Pending->value,
                'type' => 'cod', // Cash on Delivery
                'created_by' => $user ? $user->id : null,
                'updated_by' => $user ? $user->id : null,
            ];
            \App\Models\Payment::create($paymentData);

            // Save order detail for guest info / address
            \App\Models\OrderDetail::create([
                'order_id' => $order->id,
                'first_name' => $request->post('first_name'),
                'last_name' => $request->post('last_name'),
                'phone' => $request->post('phone'),
                'email' => $request->post('email'),
                'address1' => $request->post('address1'),
                'address2' => $request->post('address2') ?: '',
                'city' => $request->post('city'),
                'state' => $request->post('state') ?: '',
                'zipcode' => $request->post('zipcode') ?: '',
                'country_code' => $request->post('country_code') ?: '',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::critical(__METHOD__ . ' method does not work. '. $e->getMessage());
            throw $e;
        }

        DB::commit();

        // Clear Cart
        if ($user) {
            CartItem::where(['user_id' => $user->id])->delete();
        } else {
            \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('cart_items'));
        }

        // Send Email using the email provided in checkout
        try {
            $adminUsers = User::where('is_admin', 1)->get();
            $customerEmail = $request->post('email');
            
            foreach ($adminUsers as $admin) {
                Mail::to($admin)->send(new NewOrderEmail($order, true));
            }
            if ($customerEmail) {
                Mail::to($customerEmail)->send(new NewOrderEmail($order, false));
            }
        } catch (\Exception $e) {
            Log::critical('Email sending does not work. '. $e->getMessage());
        }

        return redirect()->route('checkout.success')->with('success', 'Your order has been placed successfully. Payment is Cash on Delivery.');
    }

    public function success(Request $request)
    {
        return view('checkout.success');
    }

    public function failure(Request $request)
    {
        return view('checkout.failure', ['message' => "Order failed."]);
    }
}
