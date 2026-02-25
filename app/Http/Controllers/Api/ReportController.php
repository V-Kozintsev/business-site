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
    $query = DailyReport::query()  // ❌ УБЕРИ leftJoin!
        ->select([
            'id', 'sales_point', 'revenue', 'report_date',
            'employee_name', 'employee_id'
        ]);

    // Фильтры ✅
    if ($request->date_from) $query->where('report_date', '>=', $request->date_from);
    if ($request->date_to) $query->where('report_date', '<=', $request->date_to);

    if (Auth::user()?->hasRole('admin')) {
        // Admin видит ВСЕ ✅
        $reports = $query->orderBy('report_date', 'desc')->get();
    } else {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json([], 401);  // ✅ Гость = пусто
        }
        
        $query->where('employee_id', $userId);
        
        // ❌ ?all=true для менеджера = ошибка!
        if (!$request->boolean('all')) {  // boolean() игнорирует фейковые параметры
            $query->whereMonth('report_date', now()->month);
        }
        
        $reports = $query->orderBy('report_date', 'desc')->get();
    }

    return response()->json($reports);
}






    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_name' => 'required|string|max:255',
        'sales_point' => 'required|string|max:255',
        'revenue' => 'required|numeric|min:0',
        'report_date' => 'required|date',
        'employee_id' => 'required|exists:users,id'
        ]);

         \Log::info('Форма store(): ', $request->all());
        \Log::info('Валидация store(): ', $validated);

        $report = DailyReport::create($validated);



         return response()->json(['success' => true, 'report' => $report]);
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
