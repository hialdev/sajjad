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
        Schema::connection('osano')->create('purchase_receive_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('purchase_receive_id');
            $table->uuid('purchase_product_id');
            $table->bigInteger('receive_qty');
            $table->text('description')->nullable();

            $table->timestamps();

            $table->foreign('purchase_product_id')
                ->references('id')
                ->on('purchase_order_products')
                ->onDelete('restrict');

            $table->foreign('purchase_receive_id')
                ->references('id')
                ->on('purchase_receives')
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
        Schema::connection('osano')->dropIfExists('purchase_receive_products');
    }
};
