<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('order_products', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('order_id');
			$table->unsignedInteger('product_id');
			$table->unsignedInteger('rate');
			$table->unsignedInteger('quantity');
			$table->unsignedInteger('size');

			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('order_products');
	}
}
