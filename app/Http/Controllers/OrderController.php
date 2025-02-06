<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index()
    {
        $orders = Order::with('orderItems.foodItem')
               ->whereIn('status', ['verify', 'pending'])
               ->get();

        foreach ($orders as $order) {
            foreach ($order->orderItems as $orderItem) {
                // Replace the food_item_id with food item's name
                $orderItem->food_item_name = $orderItem->foodItem->name;
            }
        }
        return response()->json($orders, 200);
    }
    public function complete()
    { 
        $orders = Order::with('orderItems.foodItem')->where('status', 'confirmed')
                ->get();

        foreach ($orders as $order) {
            foreach ($order->orderItems as $orderItem) {
                // Replace the food_item_id with food item's name
                $orderItem->food_item_name = $orderItem->foodItem->name;
            }
        }
        return response()->json($orders, 200);
    }

    /**
     * Store a newly created order with multiple order items.
     */
    public function store(Request $request)
    {  
       

        
        try {
            // Create the order
            $order = Order::create([
                'user_id' => 1,
                'total_price' => $request->total_price,
                'status' => "pending",
            ]);

            // Insert multiple order items
            foreach ($request->order_items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'food_item_id' => $item['food_item_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            
            return response()->json(['message' => 'Order placed successfully', 'order' => $order->load('orderItems')], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified order with items.
     */
    public function show($id)
    {
        $order = Order::with('orderItems')->find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        return response()->json($order, 200);
    }

    /**
     * Update the specified order.
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $request->validate([
            'status' => 'sometimes|string',
            'total_price' => 'sometimes|numeric|min:0',
        ]);

        $order->update($request->only(['status', 'total_price']));
        return response()->json(['message' => 'Order updated successfully', 'order' => $order], 200);
    }

    /**
     * Remove the specified order along with its items.
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        DB::beginTransaction();
        try {
            // Delete related order items first
            OrderItem::where('order_id', $order->id)->delete();
            $order->delete();

            DB::commit();
            return response()->json(['message' => 'Order deleted successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete order', 'message' => $e->getMessage()], 500);
        }
    }
}
