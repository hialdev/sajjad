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
        Schema::connection('osano')->create('purchase_receives', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('image'); // Bukti Penerimaan
            $table->string('code');
            $table->date('date');
            $table->uuid('purchase_order_id');
            $table->uuid('warehouse_id');
            $table->bigInteger('user_id'); // Penanggung Jawab Penerima
            $table->boolean('is_lock')->default(0);
            $table->boolean('is_stocked')->default(0);
            $table->text('description')->nullable();

            $table->timestamps();

            $table->foreign('purchase_order_id')
                ->references('id')
                ->on('purchase_orders')
                ->onDelete('restrict');

            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
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
        Schema::connection('osano')->dropIfExists('purchase_receives');
    }
};
