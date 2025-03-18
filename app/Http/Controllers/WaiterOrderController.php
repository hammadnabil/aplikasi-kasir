<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

use App\Models\Menus;
use Illuminate\Support\Str;

class WaiterOrderController extends Controller
{
    public function index()
{
    $orders = Order::all()->map(function ($order) {
        $items = json_decode($order->items, true); 
        $order->decoded_items = collect($items ?: [])->map(function ($item) {
            $menu = Menus::find($item['id'] ?? 0);
            $price = $menu ? (int) $menu->price : 0;
            return [
                'name' => $menu ? $menu->name : 'Tidak diketahui',
                'price' => $price,
                'quantity' => (int) ($item['quantity'] ?? 0),
                'subtotal' => $price * ($item['quantity'] ?? 0)
            ];
        });
        $order->total_price = $order->decoded_items->sum('subtotal');
        return $order;
    });

    return view('waiter.orders.index', compact('orders'));
}




    public function create()
    {
        $menus = Menus::all();
        return view('waiter.orders.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menus' => 'required|array',
            'menus.*.id' => 'exists:menus,id',
            'menus.*.quantity' => 'integer|min:1',
            'menus*.price' => 'exist:menus,price'
        ]);

        $order = Order::create([
            'order_code' => Str::random(8),
            'items' => json_encode($request->menus),
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Pesanan berhasil dibuat!',
            'order_code' => $order->order_code
        ]);
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $items = json_decode($order->items, true) ?? [];

        $order->decoded_items = collect($items)->map(function ($item) {
            $menu = Menus::find($item['id'] ?? 0);
            return [
                'id' => $item['id'] ?? 0,
                'name' => $menu->name ?? 'Tidak diketahui',
                'price' => (int) ($menu->price ?? 0),
                'quantity' => (int) ($item['quantity'] ?? 0),
                'subtotal' => ($menu->price ?? 0) * ($item['quantity'] ?? 0)
            ];
        });

        $menus = Menus::all();
        return view('waiter.orders.edit', compact('order', 'menus'));
    }


    public function update(Request $request, $id)
    {

        $request->validate([
            'menus' => 'required|array',
            'menus.*.id' => 'exist:menus,id',
            'menus.*.quantity' => 'integer|min:1',
        ]);

        $order = Order::findOrFail($id);
        $order->items = json_encode($request->menus);
        $order->save();

        return redirect()->route('waiter.orders.index')->with('success', 'Pesanan berhasil diperbarui!');
    }
}
