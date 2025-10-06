<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
	public function up(): void
	{
		Schema::create('people', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('title')->nullable();
			$table->string('first_name');
			$table->string('last_name');
			$table->string('email')->unique();
			$table->foreignId('church_id')->nullable()->constrained()->onDelete('set null');
			$table->string('type')->nullable();
			$table->string('empty')->nullable();
			$table->string('last_ip')->nullable();
			$table->date('date_of_birth')->nullable();
			$table->string('address_line')->nullable();
			$table->string('town')->nullable();
			$table->string('city')->nullable();
			$table->string('country')->nullable();
			$table->string('county')->nullable();
			$table->string('postcode')->nullable();
			$table->string('map_url')->nullable();
			$table->string('mobile_phone')->nullable();
			$table->string('phone')->nullable();
			$table->text('bio')->nullable();
			$table->string('site')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('people');
	}
};
