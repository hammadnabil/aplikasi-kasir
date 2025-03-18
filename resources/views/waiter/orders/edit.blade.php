@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Pesanan - {{ $order->order_code }}</h2>

    <form action="{{ route('waiter.orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="menuSearch">Cari Menu:</label>
            <input type="text" id="menuSearch" class="form-control" placeholder="Ketik nama menu..." autocomplete="off">
            <ul id="menuList" class="list-group mt-2"></ul>
        </div>

        <h4>Daftar Pesanan</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="orderItems">
                @foreach($order->decoded_items as $item)
                    <tr data-id="{{ $item['id'] }}">
                        <td>{{ $item['name'] }}</td>
                        <td>
                            <input type="number" name="menus[{{ $item['id'] }}][quantity]" value="{{ $item['quantity'] }}" min="1" class="form-control qty-input">
                        </td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button></td>
                    </tr>
                @endforeach
            </tbody>
            
        </table>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let menus = @json($menus);
    let selectedMenus = @json($order->decoded_items);

    document.getElementById("menuSearch").addEventListener("input", function () {
        let keyword = this.value.toLowerCase();
        let list = document.getElementById("menuList");
        list.innerHTML = "";

        if (keyword.length > 1) {
            let filteredMenus = menus.filter(menu => menu.name.toLowerCase().includes(keyword));
            filteredMenus.forEach(menu => {
                let item = document.createElement("li");
                item.textContent = `${menu.name} - Rp${menu.price}`;
                item.classList.add("list-group-item");
                item.addEventListener("click", function () {
                    addMenuToOrder(menu);
                });
                list.appendChild(item);
            });
        }
    });

    function addMenuToOrder(menu) {
        if (selectedMenus.find(m => m.id == menu.id)) return;

        selectedMenus.push({ id: menu.id, name: menu.name, quantity: 1 });

        let row = document.createElement("tr");
        row.setAttribute("data-id", menu.id);
        row.innerHTML = `
            <td>${menu.name}</td>
            <td><input type="number" name="menus[${menu.id}][quantity]" value="1" min="1" class="form-control qty-input"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button></td>
        `;

        row.querySelector(".remove-item").addEventListener("click", function () {
            selectedMenus = selectedMenus.filter(m => m.id !== menu.id);
            row.remove();
        });

        document.getElementById("orderItems").appendChild(row);
    }

    document.querySelectorAll(".remove-item").forEach(button => {
        button.addEventListener("click", function () {
            let row = this.closest("tr");
            let menuId = row.getAttribute("data-id");
            selectedMenus = selectedMenus.filter(m => m.id != menuId);
            row.remove();
        });
    });

});
</script>
@endsection
