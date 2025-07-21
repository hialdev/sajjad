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
        Schema::connection('osano')->create('customer_order_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_order_id');
            $table->uuid('product_id')->nullable();
            $table->uuid('store_id')->nullable();

            $table->uuid('partner_id')->nullable();
            $table->boolean('is_from_partner')->default(0);
            
            $table->bigInteger('qty')->nullable();
            $table->decimal('length', 12, 2)->nullable(); // untuk meteran

            $table->decimal('price_sale', 15, 2);
            $table->decimal('price_buy', 15, 2)->nullable()->default(0);
            
            $table->text('description')->nullable();
            
            $table->timestamps();

            $table->foreign('customer_order_id')
                ->references('id')
                ->on('customer_orders')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('osano')->dropIfExists('customer_order_products');
    }
};
