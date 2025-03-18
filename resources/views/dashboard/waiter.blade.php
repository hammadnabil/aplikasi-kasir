@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dashboard Waiter</h2>

    <a href="{{ route('waiter.orders.index') }}" class="btn btn-primary">Lihat Pesanan</a>
    <a href="{{ route('waiter.orders.create') }}" class="btn btn-success">Buat Pesanan</a>
</div>
@endsection
