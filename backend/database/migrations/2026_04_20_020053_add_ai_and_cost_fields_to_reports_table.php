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
        Schema::table('reports', function (Blueprint $table) {
            $table->decimal('confidence_score', 5, 4)->nullable()->after('priority_score');
            $table->boolean('is_ai_classified')->default(false)->after('confidence_score');
            $table->decimal('estimated_cost_asphalt', 15, 2)->nullable()->after('road_width');
            $table->decimal('estimated_cost_concrete', 15, 2)->nullable()->after('estimated_cost_asphalt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn([
                'confidence_score',
                'is_ai_classified',
                'estimated_cost_asphalt',
                'estimated_cost_concrete',
            ]);
        });
    }
};
