<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'employee_name',
        'sales_point', 
        'revenue',
        'report_date'
    ];

    protected $casts = [
        'revenue' => 'decimal:2',
        'report_date' => 'date'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');  // User вместо Employee
    }
}
