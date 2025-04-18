<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->orderBy('transaction_date', 'desc')
            ->with('category')
            ->paginate(10);
        
        $incomeTotal = Transaction::where('user_id', Auth::id())
            ->where('type', 'income')
            ->sum('amount');
            
        $expenseTotal = Transaction::where('user_id', Auth::id())
            ->where('type', 'expense')
            ->sum('amount');
            
        $balance = $incomeTotal - $expenseTotal;
        
        return view('transactions.index', compact('transactions', 'incomeTotal', 'expenseTotal', 'balance'));
    }

    public function create()
    {
        $categories = Category::where('user_id', Auth::id())->get();
        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $validated['user_id'] = Auth::id();

        Transaction::create($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function edit(Transaction $transaction)
    {
        // $this->authorize('update', $transaction);
        
        $categories = Category::where('user_id', Auth::id())->get();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        // $this->authorize('update', $transaction);
        
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy(Transaction $transaction)
    {
        // $this->authorize('delete', $transaction);
        
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus!');
    }
    
    public function dashboard()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        $monthlyExpenses = Transaction::where('user_id', Auth::id())
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');
            
        $monthlyIncome = Transaction::where('user_id', Auth::id())
            ->where('type', 'income')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');
            
        $expensesByCategory = Transaction::where('user_id', Auth::id())
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map(function($group) {
                return $group->sum('amount');
            });
            
        $latestTransactions = Transaction::where('user_id', Auth::id())
            ->orderBy('transaction_date', 'desc')
            ->take(5)
            ->with('category')
            ->get();

        return view('dashboard', compact(
            'monthlyExpenses', 
            'monthlyIncome', 
            'expensesByCategory', 
            'latestTransactions'
        ));
    }
}
