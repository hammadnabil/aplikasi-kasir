@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Buat Pesanan</h2>

        <form id="orderForm">
            @csrf
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
                <tbody id="orderItems"></tbody>
            </table>

            <button type="submit" class="btn btn-primary">Konfirmasi Pesanan</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let menus = @json($menus);
            let selectedMenus = [];
            let currentFocus = -1;
            let isEditingOrder = false; // Mode edit daftar pesanan
            let editIndex = 0; // Indeks item yang sedang diedit
    
            document.getElementById("menuSearch").focus();
    
            document.getElementById("menuSearch").addEventListener("input", function () {
                let keyword = this.value.toLowerCase();
                let list = document.getElementById("menuList");
                list.innerHTML = "";
    
                if (keyword.length > 1) {
                    let filteredMenus = menus.filter(menu => menu.name.toLowerCase().includes(keyword));
                    filteredMenus.forEach((menu, index) => {
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
    
            document.getElementById("menuSearch").addEventListener("keydown", function (e) {
                let list = document.getElementById("menuList");
                let items = list.getElementsByTagName("li");
    
                if (e.key === "ArrowDown") {
                    e.preventDefault();
                    if (!isEditingOrder) {
                        currentFocus++;
                        if (currentFocus >= items.length) currentFocus = 0;
                        setActive(items);
                    }
                } else if (e.key === "ArrowUp") {
                    e.preventDefault();
                    if (!isEditingOrder) {
                        currentFocus--;
                        if (currentFocus < 0) currentFocus = items.length - 1;
                        setActive(items);
                    }
                } else if (e.key === "Enter") {
                    e.preventDefault();
                    if (!isEditingOrder && currentFocus > -1) {
                        items[currentFocus].click();
                    }
                } else if (e.ctrlKey && e.key === "e") {
                    e.preventDefault();
                    startEditingOrder();
                }
            });
    
            function setActive(items) {
                if (!items.length) return false;
                removeActive(items);
                if (currentFocus >= items.length) currentFocus = 0;
                if (currentFocus < 0) currentFocus = (items.length - 1);
                items[currentFocus].classList.add("active");
            }
    
            function removeActive(items) {
                for (let i = 0; i < items.length; i++) {
                    items[i].classList.remove("active");
                }
            }
    
            function addMenuToOrder(menu) {
                if (selectedMenus.find(m => m.id === menu.id)) return;
    
                selectedMenus.push({ id: menu.id, name: menu.name, quantity: 1 });
    
                let row = document.createElement("tr");
                row.innerHTML = `
                    <td>${menu.name}</td>
                    <td><input type="number" name="menus[${menu.id}][quantity]" value="1" min="1" class="form-control qty-input"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button></td>
                `;
    
                let qtyInput = row.querySelector(".qty-input");
                let removeButton = row.querySelector(".remove-item");
    
                qtyInput.addEventListener("keydown", function (e) {
                    let orderItems = document.querySelectorAll(".qty-input");
    
                    if (e.key === "ArrowUp") {
                        e.preventDefault();
                        if (editIndex > 0) {
                            editIndex--;
                            orderItems[editIndex].focus();
                        }
                    } else if (e.key === "ArrowDown") {
                        e.preventDefault();
                        if (editIndex < orderItems.length - 1) {
                            editIndex++;
                            orderItems[editIndex].focus();
                        }
                    } else if (e.key === "Enter") {
                        e.preventDefault();
                        isEditingOrder = false;
                        document.getElementById("menuSearch").focus();
                    }
                });
    
                qtyInput.addEventListener("change", function () {
                    let menuIndex = selectedMenus.findIndex(m => m.id === menu.id);
                    selectedMenus[menuIndex].quantity = parseInt(this.value);
                });
    
                removeButton.addEventListener("click", function () {
                    selectedMenus = selectedMenus.filter(m => m.id !== menu.id);
                    row.remove();
                });
    
                document.getElementById("orderItems").appendChild(row);
                document.getElementById("menuSearch").value = "";
                document.getElementById("menuList").innerHTML = "";
                currentFocus = -1;
            }
    
            function startEditingOrder() {
                let orderItems = document.querySelectorAll(".qty-input");
                if (orderItems.length > 0) {
                    isEditingOrder = true;
                    editIndex = 0;
                    orderItems[0].focus();
                }
            }
    
            function submitOrder() {
                let formData = {
                    _token: "{{ csrf_token() }}",
                    menus: selectedMenus.map(m => ({ id: m.id, quantity: m.quantity }))
                };
    
                fetch("{{ route('waiter.orders.store') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(formData)
                })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        window.location.href = "{{ route('waiter.orders.index') }}";
                    })
                    .catch(error => console.error("Error:", error));
            }
    
            document.getElementById("orderForm").addEventListener("submit", function (e) {
                e.preventDefault();
                submitOrder();
            });
    
            document.addEventListener("keydown", function (e) {
                if (e.ctrlKey && e.key === "Enter") {
                    e.preventDefault();
                    submitOrder(); // Panggil fungsi submitOrder saat Ctrl + Enter ditekan
                }
            });
    
        });
    </script>


@endsection