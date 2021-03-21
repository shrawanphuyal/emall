<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('products', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('category_id')->nullable();
			$table->unsignedInteger('sub_category_id')->nullable();
			$table->unsignedInteger('sub_sub_category_id')->nullable();
			$table->unsignedInteger('vendor_id')->nullable();
			$table->string('title', 255);
			$table->string('slug', 271);
			$table->string('image', 300)->nullable();
			$table->unsignedInteger('quantity');
			$table->unsignedInteger('admin_profit_percentage')->default(0);
			$table->unsignedInteger('discount')->default(0);
			$table->boolean('discount_type')->default(1); // 1 => amount, 0 => percentage
			$table->float('price', 8, 2);
			$table->text('description')->nullable();
			$table->text('specification')->nullable();
			$table->text('review')->nullable();
			$table->boolean('gender')->nullable(); // 1 => male, 0 => female, null => otherP
			$table->boolean('featured')->default(0);
			$table->boolean('sale')->default(0);
			$table->boolean('hot')->default(0);
			$table->timestamps();

			$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
			$table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
			$table->foreign('sub_sub_category_id')->references('id')->on('sub_sub_categories')->onDelete('cascade');
			$table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('products');
	}
}
