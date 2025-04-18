@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Pemasukan</h5>
                    <p class="card-text h3">Rp {{ number_format($incomeTotal, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Pengeluaran</h5>
                    <p class="card-text h3">Rp {{ number_format($expenseTotal, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Saldo</h5>
                    <p class="card-text h3">Rp {{ number_format($balance, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Transaksi</h5>
            <a href="{{ route('transactions.create') }}" class="btn btn-primary">Tambah Transaksi</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                            <th>Kategori</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                            <td>{{ $transaction->description }}</td>
                            <td>{{ $transaction->category->name }}</td>
                            <td>
                                @if($transaction->type == 'income')
                                <span class="badge bg-success">Pemasukan</span>
                                @else
                                <span class="badge bg-danger">Pengeluaran</span>
                                @endif
                            </td>
                            <td>
                                <span class="{{ $transaction->type == 'income' ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection