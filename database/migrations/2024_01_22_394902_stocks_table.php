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
        Schema::connection('osano')->create('stocks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id');
            $table->enum('nowin_type', ['warehouse', 'store']);
            $table->uuid('nowin_id')->nullable();
            $table->bigInteger('qty');
            
            $table->enum('trx_type', ['in', 'out', 'onway']);
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
        Schema::connection('osano')->dropIfExists('stocks');
    }
};
