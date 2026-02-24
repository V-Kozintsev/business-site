<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
{
    if($request->has('all')) {
        // ✅ ВСЕ отчёты менеджера (для архива)
        $reports = DailyReport::where('employee_id', Auth::id())
            ->leftJoin('users', 'daily_reports.employee_id', '=', 'users.id')
            ->select(
                'daily_reports.id',
                'daily_reports.sales_point', 
                'daily_reports.revenue', 
                'daily_reports.report_date',
                'users.name as employee_name'
            )
            ->orderBy('report_date', 'desc')
            ->get();
    } else {
        // ❌ Текущая логика (только месяц)
        $reports = DailyReport::where('employee_id', Auth::id())
            ->whereMonth('report_date', now()->month)
            ->leftJoin('users', 'daily_reports.employee_id', '=', 'users.id')
            ->select(
                'daily_reports.id',
                'daily_reports.sales_point', 
                'daily_reports.revenue', 
                'daily_reports.report_date',
                'users.name as employee_name'
            )
            ->orderBy('report_date', 'desc')
            ->get();
    }
    return response()->json($reports);
}


    public function store(Request $request)
    {
        $validated = $request->validate([
            'sales_point' => 'required|string|max:255',
            'revenue' => 'required|numeric|min:0',
            'report_date' => 'required|date',
        ]);

        DailyReport::create([
            'employee_id' => Auth::id(),
            'sales_point' => $validated['sales_point'],
            'revenue' => $validated['revenue'],
            'report_date' => $validated['report_date'],
        ]);

        return response()->json(['success' => true]);
    }
    public function update(Request $request, DailyReport $report)
    {   
        if ($report->employee_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if ($report->report_date->month != now()->month) {
            return response()->json(['error' => 'Только текущий месяц'], 403);
        }
        $validated = $request->validate([
            'sales_point' => 'required|string|max:255',
            'revenue' => 'required|numeric|min:0',
            'report_date' => 'required|date',
        ]);

        $report->update($validated);

        return response()->json($report);
    }
    public function destroy(DailyReport $report)
    {
        if ($report->employee_id !== Auth::id()) {  // ← ДОБАВЬ
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if ($report->report_date->month != now()->month) {
            return response()->json(['error' => 'Только текущий месяц'], 403);
        }
        $report->delete();
        return response()->json(['success' => true]);
    }

}
