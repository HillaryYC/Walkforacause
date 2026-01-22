<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasStartDate = Schema::hasColumn('causes', 'start_date');
        $hasEndDate = Schema::hasColumn('causes', 'end_date');

        if (!($hasStartDate || $hasEndDate)) {
            return;
        }

        Schema::table('causes', function (Blueprint $table) use ($hasStartDate, $hasEndDate) {
            if ($hasStartDate) {
                $table->dropColumn('start_date');
            }

            if ($hasEndDate) {
                $table->dropColumn('end_date');
            }
        });
    }

    public function down(): void
    {
        $hasStartDate = Schema::hasColumn('causes', 'start_date');
        $hasEndDate = Schema::hasColumn('causes', 'end_date');

        if ($hasStartDate && $hasEndDate) {
            return;
        }

        Schema::table('causes', function (Blueprint $table) use ($hasStartDate, $hasEndDate) {
            if (!$hasStartDate) {
                $table->date('start_date')->nullable();
            }

            if (!$hasEndDate) {
                $table->date('end_date')->nullable();
            }
        });
    }
};
