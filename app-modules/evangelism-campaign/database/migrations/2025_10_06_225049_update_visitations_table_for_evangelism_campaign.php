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
        Schema::table('visitations', function (Blueprint $table) {
            // Rename user_id to visited_by
            $table->renameColumn('user_id', 'visited_by');

            // Drop existing columns that will be replaced
            $table->dropColumn(['type', 'location', 'outcome']);

            // Add new columns
            $table->integer('duration_minutes')->nullable()->after('purpose');
            $table->enum('attendance_status', ['home', 'not_home', 'moved'])->default('home')->after('duration_minutes');
            $table->text('prayer_requests')->nullable()->after('notes');
            $table->text('needs_identified')->nullable()->after('prayer_requests');
            $table->boolean('follow_up_required')->default(false)->after('needs_identified');

            // Change visit_date from timestamp to date
            $table->date('visit_date')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitations', function (Blueprint $table) {
            // Revert column name
            $table->renameColumn('visited_by', 'user_id');

            // Drop new columns
            $table->dropColumn(['duration_minutes', 'attendance_status', 'prayer_requests', 'needs_identified', 'follow_up_required']);

            // Restore old columns
            $table->string('type')->nullable()->after('visit_date');
            $table->string('location')->nullable()->after('type');
            $table->text('outcome')->nullable()->after('purpose');

            // Change visit_date back to timestamp
            $table->timestamp('visit_date')->nullable()->change();
        });
    }
};
