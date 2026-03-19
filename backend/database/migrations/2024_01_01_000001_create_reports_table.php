<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('address')->nullable();
            $table->string('district')->nullable(); // kecamatan
            $table->string('damage_level', 20); // ringan, sedang, berat
            $table->text('description')->nullable();
            $table->decimal('road_length', 8, 2)->nullable(); // meter
            $table->decimal('road_width', 8, 2)->nullable(); // meter
            $table->decimal('priority_score', 5, 2)->default(0);
            $table->integer('report_count')->default(1); // jumlah laporan di lokasi sama
            $table->decimal('traffic_level', 3, 2)->default(0.5); // 0-1
            $table->decimal('facility_proximity', 3, 2)->default(0); // 0-1
            $table->string('status', 20)->default('submitted'); // submitted, verified, scheduled, under_repair, completed
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('repair_started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['latitude', 'longitude']);
            $table->index('status');
            $table->index('priority_score');
            $table->index('district');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
