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
        Schema::create('wellbeing_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->morphs('recordable'); // polymorphic relationship columns
            $table->date('record_date');
            $table->enum('type', ['spiritual', 'physical', 'financial', 'emotional']);
            $table->enum('status', ['excellent', 'good', 'fair', 'poor', 'critical']);
            $table->text('prayer_requests')->nullable();
            $table->text('needs')->nullable();
            $table->text('assistance_provided')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wellbeing_records');
    }
};
