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
        Schema::connection('osano')->create('bal_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bal_id');
            $table->uuid('product_id');
            $table->bigInteger('qty');
            
            $table->timestamps();

            $table->foreign('bal_id')
                ->references('id')
                ->on('bals')
                ->onDelete('restrict');

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
        Schema::connection('osano')->dropIfExists('bal_products');
    }
};
