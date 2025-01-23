<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name_ar')->unique();
            $table->string('name_en')->unique();
            $table->string('image');
            $table->json('OtherImage')->nullable();
            $table->text('desc_ar');
            $table->text('desc_en');
            $table->decimal('main_price', 10, 2)->nullable();
            $table->decimal('price_discount', 10, 2);
            $table->json('colors');
            $table->json('sizes');
            $table->integer('stock');
            $table->integer('barcode')->nullable();
            $table->tinyInteger('out_of_stock')->default(0);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
