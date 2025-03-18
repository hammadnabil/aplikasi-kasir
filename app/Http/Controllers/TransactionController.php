<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user')->orderBy('created_at', 'desc');

        // Filter berdasarkan pegawai (kasir) yang menangani transaksi
        if ($request->has('kasir') && $request->kasir != '') {
            $query->where('user_id', $request->kasir);
        }

        // Filter berdasarkan rentang tanggal transaksi
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $transactions = $query->paginate(10);
        $kasirs = User::where('role_id', 3)->get(); // Ambil daftar kasir (role_id = 3)

        return view('manager.transactions.index', compact('transactions', 'kasirs'));
    }
}
