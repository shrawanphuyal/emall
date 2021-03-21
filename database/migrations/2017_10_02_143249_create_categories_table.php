<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('categories', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255);
			$table->string('slug', 271);
			$table->string('image', 500)->nullable();
			$table->boolean('show_on_menu')->default(0);
			$table->boolean('exclusive')->default(0);
			$table->unsignedInteger('priority')->default(999);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('categories');
	}
}
