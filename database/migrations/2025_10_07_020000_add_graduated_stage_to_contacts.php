<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite doesn't support modifying enums directly
        // We need to check the current definition and recreate if needed
        DB::statement("
            CREATE TEMPORARY TABLE contacts_backup AS
            SELECT id, church_id, branch_id, person_id, first_name, last_name, email,
                   address, mobile, social_handle, age_group, marital_status, occupation,
                   contact_source, notes, captured_by, captured_at, stage, created_at, updated_at
            FROM contacts
        ");

        Schema::dropIfExists('contacts');

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('person_id')->nullable()->constrained()->onDelete('set null');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('mobile')->nullable();
            $table->string('social_handle')->nullable();
            $table->string('age_group')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('occupation')->nullable();
            $table->string('contact_source')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('captured_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('captured_at')->nullable();
            $table->enum('stage', ['prospect', 'new_convert', 'believer', 'member', 'graduated'])->default('prospect');
            $table->timestamps();
        });

        DB::statement("
            INSERT INTO contacts (id, church_id, branch_id, person_id, first_name, last_name, email,
                                 address, mobile, social_handle, age_group, marital_status, occupation,
                                 contact_source, notes, captured_by, captured_at, stage, created_at, updated_at)
            SELECT id, church_id, branch_id, person_id, first_name, last_name, email,
                   address, mobile, social_handle, age_group, marital_status, occupation,
                   contact_source, notes, captured_by, captured_at, stage, created_at, updated_at
            FROM contacts_backup
        ");

        DB::statement("DROP TABLE contacts_backup");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate without 'graduated' status
        DB::statement("
            CREATE TEMPORARY TABLE contacts_backup AS SELECT * FROM contacts WHERE stage != 'graduated'
        ");

        Schema::dropIfExists('contacts');

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('person_id')->nullable()->constrained()->onDelete('set null');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('mobile')->nullable();
            $table->string('social_handle')->nullable();
            $table->string('age_group')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('occupation')->nullable();
            $table->string('contact_source')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('captured_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('captured_at')->nullable();
            $table->enum('stage', ['prospect', 'new_convert', 'believer', 'member'])->default('prospect');
            $table->timestamps();
        });

        DB::statement("
            INSERT INTO contacts SELECT * FROM contacts_backup
        ");

        DB::statement("DROP TABLE contacts_backup");
    }
};
