@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
<h2>Dashboard Kasir</h2>
<p>Selamat datang, {{ auth()->user()->name }}!</p>
<a href="#" class="btn btn-primary">Mulai Transaksi</a>
@endsection
