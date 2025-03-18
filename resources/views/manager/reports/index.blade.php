@extends('layouts.app')

@section('content')
<h2>Laporan Penjualan</h2>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $index => $transaction)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
            <td>{{ $transaction->customer_name ?? 'Umum' }}</td>
            <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
            <td>{{ $transaction->payment_method }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('manager.menu.index') }}" class="btn btn-secondary">Kembali</a>
@endsection
