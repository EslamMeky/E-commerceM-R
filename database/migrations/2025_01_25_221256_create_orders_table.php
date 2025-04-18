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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("transaction_id")->nullable();
            $table->string("order_id");
            $table->string("type_user");
            $table->integer("user_id")->nullable();
            $table->string("code_user")->nullable();
            $table->string("payment_method")->nullable();
            $table->string("status");
            $table->decimal('amount_cents', 10, 2);
            $table->string('currency');
            $table->decimal('discount', 10, 2)->nullable(); // الخصم
            $table->decimal('before_discount', 10, 2)->nullable(); // المبلغ قبل الخصم
            $table->json('shipping_data'); // بيانات الشحن
            $table->json('items'); // بيانات المنتجات
            $table->string('commission_paid')->default('false');
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
        Schema::dropIfExists('orders');
    }
};
