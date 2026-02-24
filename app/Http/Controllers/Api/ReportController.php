<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
class ReportController extends Controller
{
   public function index(Request $request)
{
    $query = DailyReport::leftJoin('users', 'daily_reports.employee_id', '=', 'users.id')
        ->select(
            'daily_reports.id',
            'daily_reports.sales_point', 
            'daily_reports.revenue', 
            'daily_reports.report_date',
            'users.name as employee_name',
            'daily_reports.employee_id'
        );

    if ($request->date_from) {
        $query->where('daily_reports.report_date', '>=', $request->date_from);
    }
    if ($request->date_to) {
        $query->where('daily_reports.report_date', '<=', $request->date_to);
    }

    // ✅ БЕЗОПАСНАЯ проверка авторизации
    if (Auth::check() && Auth::user()->hasRole('admin')) {
        $reports = $query->orderBy('daily_reports.report_date', 'desc')->get();
    } else {
        $userId = Auth::check() ? Auth::id() : null;
        if ($userId) {
            $query->where('daily_reports.employee_id', $userId);
            if (!$request->has('all')) {
                $query->whereMonth('daily_reports.report_date', now()->month);
            }
        }
        $reports = $query->orderBy('daily_reports.report_date', 'desc')->get();
    }

    return response()->json($reports);
}




    public function store(Request $request)
    {
        $validated = $request->validate([
            'sales_point' => 'required|string|max:255',
            'revenue' => 'required|numeric|min:0',
            'report_date' => 'required|date',
            'employee_id' => 'required|exists:users,id',
        ]);

        DailyReport::create($validated);

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
