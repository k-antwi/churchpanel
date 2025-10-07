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
        Schema::table('contacts', function (Blueprint $table) {
            // Make person_id nullable
            $table->foreignId('person_id')->nullable()->change();

            // Add new fields as nullable first (check if they don't exist)
            if (!Schema::hasColumn('contacts', 'first_name')) {
                $table->string('first_name')->nullable()->after('person_id');
            }
            if (!Schema::hasColumn('contacts', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('contacts', 'email')) {
                $table->string('email')->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('contacts', 'address')) {
                $table->text('address')->nullable()->after('email');
            }
            if (!Schema::hasColumn('contacts', 'mobile')) {
                $table->string('mobile')->nullable()->after('address');
            }
            if (!Schema::hasColumn('contacts', 'social_handle')) {
                $table->string('social_handle')->nullable()->after('mobile');
            }
        });

        // Populate first_name and last_name from person relationship
        $contacts = DB::table('contacts')
            ->whereNotNull('person_id')
            ->get();

        foreach ($contacts as $contact) {
            $person = DB::table('people')->find($contact->person_id);
            if ($person) {
                DB::table('contacts')
                    ->where('id', $contact->id)
                    ->update([
                        'first_name' => $person->first_name,
                        'last_name' => $person->last_name,
                        'email' => $person->email,
                        'mobile' => $person->mobile_phone,
                        'address' => $person->address_line,
                    ]);
            }
        }

        // For contacts without person_id, set placeholder values
        DB::table('contacts')
            ->whereNull('first_name')
            ->orWhereNull('last_name')
            ->update([
                'first_name' => DB::raw('COALESCE(first_name, "Unknown")'),
                'last_name' => DB::raw('COALESCE(last_name, "Contact")'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Revert person_id to not nullable
            $table->foreignId('person_id')->nullable(false)->change();

            // Drop new fields
            $table->dropColumn([
                'first_name',
                'last_name',
                'email',
                'address',
                'mobile',
                'social_handle',
            ]);
        });
    }
};
