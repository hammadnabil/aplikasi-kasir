<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Menus;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menus::all();
        return view('manager.menu.index', compact('menus'));
    }

    public function create()
    {
        return view('manager.menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        Menus::create($request->all());

        return redirect()->route('manager.menu.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit(Menus $menu)
    {
        return view('manager.menu.edit', compact('menu'));
    }

    public function update(Request $request, Menus $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $menu->update($request->all());

        return redirect()->route('manager.menu.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Menus $menu)
    {
        $menu->delete();

        return redirect()->route('manager.menu.index')->with('success', 'Menu berhasil dihapus!');
    }

    public function search(Request $request)
{
    $query = $request->input('q');
    $menus = Menus::where('name', 'like', "%{$query}%")->get();
    
    return response()->json($menus);
}

}
