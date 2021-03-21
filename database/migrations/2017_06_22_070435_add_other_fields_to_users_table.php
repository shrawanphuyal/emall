<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOtherFieldsToUsersTable extends Migration {
	public function up() {
		Schema::table('users', function (Blueprint $table) {
			$table->string('social_id')->unique()->nullable();
			$table->string('address')->nullable();
			$table->string('phone')->nullable();
			$table->timestamp('dob')->nullable();
			$table->string('image')->nullable();
			$table->longText('about')->nullable();
			$table->boolean('verified')->default(0);
			$table->string('email_token', 11)->nullable();
			$table->string('auth_token', 250)->nullable();
		});
	}

	public function down() {
		Schema::table('users', function (Blueprint $table) {
			//
		});
	}
}
