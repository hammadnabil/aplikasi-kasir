<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menus;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
{
    $orders = Order::with('orderMenus.menu')->where('user_id', Auth::id())->latest()->get();
    return view('waiter.orders.index', compact('orders'));
}


    public function create()
    {
        $menus = Menus::all();
        return view('waiter.orders.create', compact('menus'));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'menus' => 'required|array',
        'menus.*.id' => 'exists:menus,id',
        'menus.*.quantity' => 'integer|min:1',
    ]);

    $order = Order::create([
        'user_id' => Auth::id(),
        'items' => json_encode($data['menus']),
        'status' => 'pending',
    ]);

    return response()->json(['message' => 'Pesanan berhasil dibuat!', 'order' => $order]);
}


}
