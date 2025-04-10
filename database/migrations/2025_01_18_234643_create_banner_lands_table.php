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
        Schema::create('banner_lands', function (Blueprint $table) {
            $table->id();
            $table->string('tittle_ar');
            $table->string('tittle_en');
            $table->text('desc_ar');
            $table->text('desc_en');
            $table->string('image');
            $table->string('name_btn_ar');
            $table->string('name_btn_en');
            $table->string('link_btn');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banner_lands');
    }
};
