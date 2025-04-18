@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Kategori Transaksi</h1>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Kategori
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            @if(count($categories) > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="70">#</th>
                            <th>Nama Kategori</th>
                            <th>Ikon</th>
                            <th>Jumlah Transaksi</th>
                            <th width="150" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                @if($category->icon)
                                    <i class="{{ $category->icon }} fa-lg"></i>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge rounded-pill text-bg-light">
                                    {{ $category->transactions->count() }} transaksi
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" {{ $category->transactions->count() > 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                <p class="mb-1">Belum ada kategori yang dibuat</p>
                <p class="text-muted">Tambahkan kategori untuk mengorganisasi transaksi Anda</p>
                <a href="{{ route('categories.create') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-plus me-1"></i> Tambah Kategori Pertama
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection