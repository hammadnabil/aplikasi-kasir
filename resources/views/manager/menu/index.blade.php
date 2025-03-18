@extends('layouts.app')

@section('content')
<h2>Daftar Menu</h2>
<a href="{{ route('manager.menu.create') }}" class="btn btn-primary">Tambah Menu</a>
<a href="{{ route('dashboard')}}"class="btn btn-warning">kembali</a>

<table class="table">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Harga</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($menus as $menu)
        <tr>
            <td>{{ $menu->name }}</td>
            <td>{{ $menu->price }}</td>
            <td>{{ $menu->description }}</td>
            <td>
                <a href="{{ route('manager.menu.edit', $menu->id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('manager.menu.destroy', $menu->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
