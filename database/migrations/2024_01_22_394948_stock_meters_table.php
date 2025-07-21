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
        Schema::connection('osano')->create('stock_meters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id');
            $table->enum('nowin_type', ['warehouse', 'store']);
            $table->uuid('nowin_id')->nullable();
            $table->boolean('is_onway')->default(0);
                                            
            $table->bigInteger('sold_length')->nullable();
            $table->bigInteger('length')->nullable();
            
            $table->timestamps();

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
        Schema::connection('osano')->dropIfExists('stock_meters');
    }
};
