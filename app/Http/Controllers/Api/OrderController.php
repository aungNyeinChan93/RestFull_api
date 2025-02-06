<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\OrderListResource;
use App\Http\Resources\OrderShowResource;
use App\Http\Resources\OrderStoreResource;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrdersProducts;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    //index
    public function index()
    {
        $orders = Order::query()->with(['user', 'orders_products'])
            ->filter(request(['user_id', 'user_name', 'product_id', 'product_name']))
            ->paginate(4)->withQueryString();

        OrderListResource::collection($orders);

        return response()->json([
            'message' => 'success',
            'orders' => $orders,
        ], 200);
    }


    // store
    public function store(Request $request)
    {
        $fileds = $request->validate([
            'total_amount' => 'required|string',
            'shipping_address' => 'nullable|string',
            'note' => 'nullable|string',
            'screen_short' => 'nullable|file|image|mimes:png,jpg',
        ]);

        if ($request->hasFile('screen_short')) {
            $fileds['screen_short'] = $request->file('screen_short')->store('/screen_short/', 'public');
        }

        $order = Order::create([...$fileds, ...['user_id' => request()->user()->id]]);

        // DB::table('orders_products')->insert([
        //     'order_id' => $order->id,
        //     'product_id' => $request->product_id,
        //     'quantity' => $request->quantity,
        // ]);

        foreach ($request->orders_products as $item) {
            OrdersProducts::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        $order = $order->load(['user', 'orders_products']);

        return response()->json([
            'message' => 'success',
            'order' => new OrderStoreResource($order),
        ], 200);
    }

    // show
    public function show(Order $order)
    {
        $order = $order->load(['user', 'orders_products']);
        return response()->json([
            'message' => 'success',
            'order' => new OrderShowResource($order),
        ], 200);
    }

    // myorder
    public function myOrder()
    {
        $orders = request()->user()->orders()->get();

        $orders = $orders->load(['user', 'orders_products']);
        return response()->json([
            'message' => 'success',
            'order' => OrderShowResource::collection($orders),
        ], 200);
    }

    // destroy
    public function destroy(Order $order)
    {
        $order = $order->load('user', 'orders_products');
        $order->delete();
        return response()->json([
            'message' => 'success',
            "order_id" => $order->id,
        ]);
    }

    // update
    public function update(Request $request ,Order $order)
    {
        $fileds = $request->validate([
            'total_amount' => 'required|string',
            'shipping_address' => 'nullable|string',
            'note' => 'nullable|string',
            'screen_short' => 'nullable|file|image|mimes:png,jpg',
        ]);

        if ($request->hasFile('screen_short')) {
            $fileds['screen_short'] = $request->file('screen_short')->store('/screen_short/', 'public');
        }

        $order->update([...$fileds, ...['user_id' => request()->user()->id]]);

        $orders_porducts = OrdersProducts::where('order_id',$order->id)->get();

        foreach ($orders_porducts as $key => $value) {
            $value->delete();
        }

        foreach ($request->orders_products as $item) {
            OrdersProducts::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        $order = $order->load(['user', 'orders_products']); //for n+1 issue

        return response()->json([
            'message' => 'success',
            'order' => new OrderStoreResource($order),
        ], 200);
    }
}
