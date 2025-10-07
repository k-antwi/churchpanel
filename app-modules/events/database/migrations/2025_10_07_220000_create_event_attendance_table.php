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
        Schema::create('event_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->morphs('attendanceable'); // Creates attendanceable_id and attendanceable_type
            $table->enum('attendance_status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->dateTime('check_in_time')->nullable();
            $table->dateTime('check_out_time')->nullable();
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('check_in_method', ['manual', 'qr_code', 'nfc', 'app'])->default('manual');
            $table->text('notes')->nullable();
            $table->boolean('first_time_visitor')->default(false);
            $table->foreignId('brought_by')->nullable()->constrained('people')->onDelete('set null');
            $table->timestamps();

            $table->index(['event_id', 'attendance_status']);
            $table->index('check_in_time');
            $table->index('first_time_visitor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_attendance');
    }
};
