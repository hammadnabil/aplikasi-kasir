@extends('layouts.app')

@section('title', 'Dashboard Manajer')

@section('content')
<h2>Dashboard Manajer</h2>
<p>Selamat datang, {{ auth()->user()->name }}!</p>

<div class="row">
    <div class="col-md-4">
        <a href="{{ route('manager.menu.index') }}" class="btn btn-info w-100">Kelola Menu</a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('manager.transactions.index') }}" class="btn btn-success w-100">catatan transaksi</a>
    </div>
</div>
@endsection
