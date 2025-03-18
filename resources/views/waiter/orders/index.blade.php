@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Daftar Pesanan</h2>
        <a href="{{ route('waiter.orders.create') }}" class="btn btn-primary mb-3">Buat Pesanan</a>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Pesanan</th>
                    <th>Items</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->order_code }}</td>
                        <td>
                            @foreach ($order->decoded_items as $item)
                                <p>Menu: {{ $item['name'] }}, Jumlah: {{ $item['quantity'] }}, x Rp{{number_format($item['price'], 0, ',', '.')}}</p>
                            @endforeach
                        </td>
                        <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <a href="{{ route('waiter.orders.edit', $order->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
