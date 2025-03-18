@extends('layouts.app')

@section('content')
<div class="container" x-data="orderApp()">
    <h2>Buat Pesanan</h2>

    <form id="orderForm" @submit.prevent="submitOrder">
        @csrf
        <div class="mb-3">
            <label for="menuSearch">Cari Menu:</label>
            <input type="text" id="menuSearch" x-model="searchKeyword" @input="filterMenus" @keydown.down="moveDown" @keydown.up="moveUp" @keydown.enter="selectMenu" class="form-control" placeholder="Ketik nama menu..." autocomplete="off">
            <ul id="menuList" class="list-group mt-2">
                <template x-for="(menu, index) in filteredMenus" :key="menu.id">
                    <li class="list-group-item" :class="{ 'active': highlightedIndex === index }" @click="addMenuToOrder(menu)" x-text="`${menu.name} - Rp${menu.price}`"></li>
                </template>
            </ul>
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
                <template x-for="(item, index) in selectedMenus" :key="item.id">
                    <tr :class="{ 'table-active': selectedRowIndex === index, 'table-primary': editingRowIndex === index }">
                        <td x-text="item.name"></td>
                        <td>
                            <input type="number" x-model="item.quantity" min="1" class="form-control qty-input" :disabled="editingRowIndex !== index">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" @click="removeItem(index)">Hapus</button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Konfirmasi Pesanan</button>
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('orderApp', () => ({
        menus: @json($menus), // Data menu dari backend
        searchKeyword: '',
        filteredMenus: [],
        selectedMenus: [],
        highlightedIndex: -1, // Indeks menu yang di-highlight
        selectedRowIndex: -1, // Indeks baris yang dipilih di daftar pesanan
        editingRowIndex: -1, // Indeks baris yang sedang diedit

        init() {
            this.filterMenus(); // Filter menu saat pertama kali load
        },

        // Fungsi untuk memfilter menu berdasarkan kata kunci
        filterMenus() {
            if (this.searchKeyword.length > 1) {
                this.filteredMenus = this.menus.filter(menu =>
                    menu.name.toLowerCase().includes(this.searchKeyword.toLowerCase())
                );
            } else {
                this.filteredMenus = [];
            }
            this.highlightedIndex = -1; // Reset highlight
        },

        // Fungsi untuk menambahkan menu ke daftar pesanan
        addMenuToOrder(menu) {
            if (this.selectedMenus.some(m => m.id === menu.id)) return;

            this.selectedMenus.push({
                id: menu.id,
                name: menu.name,
                quantity: 1
            });

            this.searchKeyword = '';
            this.filteredMenus = [];
        },

        // Fungsi untuk menghapus item dari daftar pesanan
        removeItem(index) {
            this.selectedMenus.splice(index, 1);
            if (this.selectedRowIndex === index) this.selectedRowIndex = -1;
            if (this.editingRowIndex === index) this.editingRowIndex = -1;
        },

        // Navigasi ke bawah di daftar menu
        moveDown() {
            if (this.highlightedIndex < this.filteredMenus.length - 1) {
                this.highlightedIndex++;
            }
        },

        // Navigasi ke atas di daftar menu
        moveUp() {
            if (this.highlightedIndex > 0) {
                this.highlightedIndex--;
            }
        },

        // Memilih menu dengan tombol Enter
        selectMenu() {
            if (this.highlightedIndex >= 0 && this.highlightedIndex < this.filteredMenus.length) {
                this.addMenuToOrder(this.filteredMenus[this.highlightedIndex]);
            }
        },

        // Submit pesanan
        submitOrder() {
            fetch("{{ route('waiter.orders.store') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    _token: "{{ csrf_token() }}",
                    menus: this.selectedMenus.map(m => ({ id: m.id, quantity: m.quantity }))
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                window.location.href = "{{ route('waiter.orders.index') }}";
            })
            .catch(error => console.error("Error:", error));
        }
    }));
});
</script>
@endsection