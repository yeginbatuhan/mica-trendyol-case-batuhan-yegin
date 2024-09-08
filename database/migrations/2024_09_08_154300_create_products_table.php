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
    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->string('product_id')->unique();
      $table->string('product_code')->nullable();
      $table->string('product_main_id')->nullable();
      $table->string('title');
      $table->text('description')->nullable();
      $table->string('barcode')->unique();
      $table->json('attributes')->nullable();
      $table->string('brand')->nullable();
      $table->string('category_name')->nullable();
      $table->string('gender')->nullable();
      $table->decimal('list_price', 10, 2);
      $table->decimal('sale_price', 10, 2);
      $table->unsignedInteger('quantity');
      $table->json('images')->nullable();
      $table->string('product_url')->nullable();
      $table->timestamps();
    });
  }


  /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
