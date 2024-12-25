<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\OrderConfirmation;
use App\Models\User;

class OrderController extends Controller
{
    // List all orders
    public function index()
    {
        $orders = Order::with('items.product')->get();
        return response()->json($orders);
    }

    // Get a specific order
    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return response()->json($order);
    }

    // Create a new order
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:credit_card,paypal,cash_on_delivery',
        ]);
    
        try {
            // Wrap the order creation logic in a transaction
            $order = DB::transaction(function () use ($validated) {
                $totalPrice = 0;
                $orderItems = [];
    
                foreach ($validated['items'] as $item) {
                    $product = Product::findOrFail($item['product_id']);
    
                    // Check stock availability
                    if ($product->stock_quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for product: {$product->title}");
                    }
    
                    // Update stock
                    $product->decrement('stock_quantity', $item['quantity']);
    
                    // Calculate total price
                    $totalPrice += $product->price * $item['quantity'];
    
                    // Prepare order items
                    $orderItems[] = [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ];
                }
    
                // Create order
                $order = Order::create([
                    'user_id' => $validated['user_id'],
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                    'payment_method' => $validated['payment_method'], // Save payment method
                ]);
    
                // Add items to order
                foreach ($orderItems as $orderItem) {
                    $order->items()->create($orderItem);
                }
    
                return $order;
            });
    
            // Notify the user about the order confirmation
            $user = User::find($validated['user_id']);
            $user->notify(new OrderConfirmation($order));
    
            return response()->json([
                'message' => 'Order created successfully.',
                'order_id' => $order->id,
            ], 201);
    
        } catch (\Exception $e) {
            // Handle exceptions and return error response
            return response()->json([
                'message' => 'Order creation failed.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
    
    // Update order status
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $validated['status'];
        $order->save();

        return response()->json(['message' => 'Order status updated successfully.']);
    }

    public function userOrders(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)->with('items.product')->get();
        return response()->json($orders);
    }

}