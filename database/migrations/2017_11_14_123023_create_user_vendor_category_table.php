<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserVendorCategoryTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('user_vendor_category', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('vendor_category_id');
			$table->unique(['user_id', 'vendor_category_id']);

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('vendor_category_id')->references('id')->on('vendor_categories')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('user_vendor_category');
	}
}
