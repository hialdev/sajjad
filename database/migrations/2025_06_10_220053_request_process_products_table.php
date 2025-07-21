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
        Schema::connection('osano')->create('request_process_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('request_process_id');
            $table->uuid('product_id')->nullable();
            $table->uuid('from_type')->nullable();
            $table->uuid('from_id')->nullable();
            
            $table->bigInteger('qty');
            
            $table->decimal('length', 12, 2)->nullable();
            
            $table->timestamps();

            $table->foreign('request_process_id')
                ->references('id')
                ->on('request_process')
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
        Schema::connection('osano')->dropIfExists('request_process_products');
    }
};
