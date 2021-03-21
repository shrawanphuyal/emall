<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertisementsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('advertisements', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title', 255)->nullable();
			$table->string('url', 500);
			$table->boolean('home')->default(0);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('advertisements');
	}
}
