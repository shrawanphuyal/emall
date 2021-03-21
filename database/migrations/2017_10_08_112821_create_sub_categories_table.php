<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubCategoriesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('sub_categories', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('category_id');
			$table->string('name', 255);
			$table->string('slug', 271);
			$table->string('image', 500)->nullable();
			$table->timestamps();

			$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('sub_categories');
	}
}
