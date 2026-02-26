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
    $query = DailyReport::query()
        ->select([
            'id', 'sales_point', 'revenue', 'report_date',
            'employee_name', 'employee_id'
        ]);

    // 🔥 МЕНЕДЖЕР — ТОЛЬКО СВОИ!
    if (!Auth::user()?->hasRole('admin')) {
        $query->where('employee_id', Auth::id());
    }

    // Фильтры дат (работают для всех)
    if ($request->date_from) {
        $query->whereDate('report_date', '>=', $request->date_from);
    }
    if ($request->date_to) {
        $query->whereDate('report_date', '<=', $request->date_to);
    }

    $reports = $query->orderBy('report_date', 'desc')->get();
    return response()->json($reports);
}







    public function store(Request $request)
{
    // Автозаполнение employee_id текущим юзером (лучше!)
    $data = $request->validate([
        'employee_name' => 'required|string|max:255',
        'sales_point' => 'required|string|max:255',
        'revenue' => 'required|numeric|min:0',
        'report_date' => 'required|date',
    ]);
    
    $data['employee_id'] = Auth::id();  // ✅ Manager создаёт СВОЙ отчёт
    
    \Log::info('Store data: ', $data);
    $report = DailyReport::create($data);
    return response()->json(['success' => true, 'report' => $report]);
}

public function update(Request $request, DailyReport $report)
{   

    // 
    if (Auth::user()->hasRole('admin')) {
        $validated = $request->validate([
            'employee_name' => 'required|string|max:255',
            'sales_point' => 'required|string|max:255',
            'revenue' => 'required|numeric|min:0',
            'report_date' => 'required|date',
        ]);
        $report->update($validated);
        return response()->json($report);
    }
    
    // Manager: только СВОИ за месяц
    if ($report->employee_id !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    if ($report->report_date->month != now()->month) {
        return response()->json(['error' => 'Только текущий месяц'], 403);
    }
    
    // Manager валидация + update
    $validated = $request->validate([
        'employee_name' => 'required|string|max:255',
        'sales_point' => 'required|string|max:255',
        'revenue' => 'required|numeric|min:0',
        'report_date' => 'required|date',
    ]);
    $report->update($validated);
    return response()->json($report);
}


    public function destroy(DailyReport $report)
{
    // ✅ ADMIN МОЖЕТ ВСЁ!
    if (Auth::user()->hasRole('admin')) {
        $report->delete();
        return response()->json(['success' => true]);
    }
    
    // Manager: только СВОИ за месяц
    if ($report->employee_id !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    if ($report->report_date->month != now()->month) {
        return response()->json(['error' => 'Только текущий месяц'], 403);
    }
    $report->delete();
    return response()->json(['success' => true]);
}

    

}
