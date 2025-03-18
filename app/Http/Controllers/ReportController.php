<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    public function index()
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->get();
        return view('manager.reports.index', compact('transactions'));
    }
    public function dailyReport()
    {
        $report = Transaction::whereDate('created_at', today())->get();
        return response()->json($report);
    }
    public function monthlyReport()
    {
        $report = Transaction::whereMonth('created_at', date('m'))->get();
        return response()->json($report);
    }
}
