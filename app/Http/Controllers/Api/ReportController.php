<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        // Только текущий месяц для менеджера
        $reports = DailyReport::where('employee_id', Auth::id())
            ->whereMonth('report_date', now()->month)
            ->orderBy('report_date', 'desc')
            ->get(['id', 'sales_point', 'revenue', 'report_date']);
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
    // Только свои отчёты
    if ($report->employee_id !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $report->delete();
    return response()->json(['success' => true]);
}

}
