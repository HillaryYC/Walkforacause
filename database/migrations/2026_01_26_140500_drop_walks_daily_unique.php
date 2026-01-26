<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('walks', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('walks', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'cause_id', 'walked_on']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('walks', function (Blueprint $table) {
            $table->unique(['user_id', 'cause_id', 'walked_on']);
            $table->dropIndex(['user_id']);
        });
    }
};
