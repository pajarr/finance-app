@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <h1 class="h3 mb-4">Laporan Keuangan</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header bg-light py-3">
            <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="from_date" class="form-label">Dari Tanggal</label>
                    <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date', date('Y-m-01')) }}">
                </div>
                <div class="col-md-4">
                    <label for="to_date" class="form-label">Sampai Tanggal</label>
                    <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date', date('Y-m-t')) }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Tampilkan Laporan
                    </button>
                </div>
            </form>
        </div>
        
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card border-left-primary shadow h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Pemasukan</div>
                                    <div class="h3 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-arrow-circle-up fa-2x text-primary opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card border-left-danger shadow h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Total Pengeluaran</div>
                                    <div class="h3 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-arrow-circle-down fa-2x text-danger opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card border-left-success shadow h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Saldo</div>
                                    <div class="h3 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-wallet fa-2x text-success opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Income/Expense Chart -->
            <div class="row">
                <div class="col-xl-8 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-light py-3">
                            <h6 class="m-0 font-weight-bold">Grafik Pemasukan dan Pengeluaran</h6>
                        </div>
                        <div class="card-body">
                            <div style="height: 350px;">
                                <canvas id="incomeExpenseChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-light py-3">
                            <h6 class="m-0 font-weight-bold">Distribusi Pengeluaran</h6>
                        </div>
                        <div class="card-body">
                            <div style="height: 350px;">
                                <canvas id="expenseCategoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Expense Table -->
            <div class="card shadow mb-4">
                <div class="card-header bg-light py-3">
                    <h6 class="m-0 font-weight-bold">Rincian Pengeluaran per Kategori</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Kategori</th>
                                    <th class="text-end">Jumlah</th>
                                    <th class="text-end">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expensesByCategory as $categoryName => $amount)
                                <tr>
                                    <td>{{ $categoryName }}</td>
                                    <td class="text-end">Rp {{ number_format($amount, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        @if($totalExpense > 0)
                                            {{ round(($amount / $totalExpense) * 100, 1) }}%
                                        @else
                                            0%
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td>Total</td>
                                    <td class="text-end">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
                                    <td class="text-end">100%</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Export Button -->
            <div class="text-center">
                <a href="{{ route('reports.export') }}?from_date={{ request('from_date', date('Y-m-01')) }}&to_date={{ request('to_date', date('Y-m-t')) }}" class="btn btn-success">
                    <i class="fas fa-file-excel me-1"></i> Export ke Excel
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Income/Expense Chart
        const incomeExpenseChart = document.getElementById('incomeExpenseChart').getContext('2d');
        new Chart(incomeExpenseChart, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: {!! json_encode($incomeData) !!},
                        backgroundColor: 'rgba(78, 115, 223, 0.7)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pengeluaran',
                        data: {!! json_encode($expenseData) !!},
                        backgroundColor: 'rgba(231, 74, 59, 0.7)',
                        borderColor: 'rgba(231, 74, 59, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                }
            }
        });

        // Expense Category Chart
        const expenseCategoryCtx = document.getElementById('expenseCategoryChart').getContext('2d');
        new Chart(expenseCategoryCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($expensesByCategory)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($expensesByCategory)) !!},
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                        '#fd7e14', '#6f42c1', '#20c9a6', '#5a5c69', '#858796'
                    ],
                    hoverOffset: 4
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