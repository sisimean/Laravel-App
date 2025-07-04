<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with(['customer', 'items.product'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Calculate total amount
        $totalAmount = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $totalAmount += $product->price * $item['quantity'];
            $orderItems[] = [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $product->price,
            ];
        }

        // Create order
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        // Create order items
        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        return response()->json($order->load('items.product'), 201);
    }

    public function show(Order $order)
    {
        return $order->load(['customer', 'items.product']);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'sometimes|in:pending,processing,completed,cancelled',
        ]);

        $order->update($request->only('status'));

        return response()->json($order->load(['customer', 'items.product']));
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(null, 204);
    }
}