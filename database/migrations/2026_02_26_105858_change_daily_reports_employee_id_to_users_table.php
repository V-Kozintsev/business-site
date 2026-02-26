<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);  // ❌ Удалить старую FK
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');  // ✅ На users
        });
    }

    public function down()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }
};
