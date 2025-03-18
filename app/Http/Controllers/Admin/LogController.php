<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
        ->when($request->has('user_id'), function ($query) use ($request) {
            return $query->where('user_id', $request->user_id);
        })
        ->when($request->has('date'), function ($query) use ($request) {
            return $query->whereDate('created_at', $request->date);
        })
        ->latest()
        ->paginate(10);

        return view('admin.logs.index', compact('logs'));
    }
}
