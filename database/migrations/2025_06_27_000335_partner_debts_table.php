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
        Schema::connection('osano')->create('partner_debts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_order_id')->nullable();
            $table->uuid('customer_order_product_id')->nullable();

            $table->boolean('is_paid')->default(0);
            $table->text('image')->nullable();
            $table->text('description')->nullable();
            
            $table->decimal('total_debt', 15, 2);
            
            $table->timestamps();

            $table->foreign('customer_order_id')
                ->references('id')
                ->on('customer_orders')
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
         Schema::connection('osano')->dropIfExists('partner_debts');
    }
};
