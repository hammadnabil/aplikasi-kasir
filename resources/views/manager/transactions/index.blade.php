@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="my-4">Catatan Transaksi</h2>

    
    <form action="{{ route('manager.transactions.index') }}" method="GET">
        <div class="row">
            <div class="col-md-4">
                <label for="kasir">Filter Kasir:</label>
                <select name="kasir" class="form-control">
                    <option value="">Semua Kasir</option>
                    @foreach($kasirs as $kasir)
                        <option value="{{ $kasir->id }}" {{ request('kasir') == $kasir->id ? 'selected' : '' }}>
                            {{ $kasir->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="start_date">Tanggal Awal:</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date">Tanggal Akhir:</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2 mt-4">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">kembali</a>
            </div>
        </div>
    </form>

    
    <div class="table-responsive mt-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kasir</th>
                    <th>Nomor Transaksi</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $index => $transaction)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $transaction->user->name }}</td>
                        <td>{{ $transaction->transaction_code }}</td>
                        <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                        <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
