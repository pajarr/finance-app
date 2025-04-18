@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <h1 class="h3 mb-4">Dashboard Keuangan</h1>
    
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 card-dashboard bg-primary bg-gradient text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Pemasukan Bulan Ini</div>
                            <div class="h4 mb-0 font-weight-bold">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-plus fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 card-dashboard bg-danger bg-gradient text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Pengeluaran Bulan Ini</div>
                            <div class="h4 mb-0 font-weight-bold">Rp {{ number_format($monthlyExpenses, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-minus fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 card-dashboard bg-success bg-gradient text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Saldo Bulan Ini</div>
                            <div class="h4 mb-0 font-weight-bold">Rp {{ number_format($monthlyIncome - $monthlyExpenses, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 card-dashboard bg-info bg-gradient text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Rasio Penghematan</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h4 mb-0 font-weight-bold">
                                        @if($monthlyIncome > 0)
                                            {{ round(100 - ($monthlyExpenses / $monthlyIncome * 100), 1) }}%
                                        @else
                                            0%
                                        @endif
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm ml-2">
                                        <div class="progress-bar bg-white" role="progressbar" 
                                            style="width: {{ $monthlyIncome > 0 ? 100 - ($monthlyExpenses / $monthlyIncome * 100) : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-piggy-bank fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-light">
                    <h6 class="m-0 font-weight-bold">Ikhtisar Pengeluaran per Kategori</h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-light">
                    <h6 class="m-0 font-weight-bold">Transaksi Terbaru</h6>
                    <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if(count($latestTransactions) > 0)
                        <div class="list-group">
                            @foreach($latestTransactions as $transaction)
                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">
                                                @if($transaction->type == 'income')
                                                    <i class="fas fa-arrow-circle-up text-success"></i>
                                                @else
                                                    <i class="fas fa-arrow-circle-down text-danger"></i>
                                                @endif
                                            </span>
                                            <strong>{{ $transaction->description }}</strong>
                                        </div>
                                        <small class="text-muted">
                                            {{ $transaction->transaction_date->format('d M Y') }} • 
                                            {{ $transaction->category->name }}
                                        </small>
                                    </div>
                                    <span class="{{ $transaction->type == 'income' ? 'text-success' : 'text-danger' }} fw-bold">
                                        {{ $transaction->type == 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <p>Belum ada transaksi</p>
                            <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i> Tambah Transaksi
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryLabels = [
            @foreach($expensesByCategory as $category => $amount)
                '{{ $category }}',
            @endforeach
        ];
        
        const categoryData = [
            @foreach($expensesByCategory as $amount)
                {{ $amount }},
            @endforeach
        ];
        
        const backgroundColors = [
            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
            '#fd7e14', '#6f42c1', '#20c9a6', '#5a5c69', '#858796'
        ];

        const ctx = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryData,
                    backgroundColor: backgroundColors.slice(0, categoryData.length),
                    hoverOffset: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    });
</script>
@endsection