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
        Schema::connection('osano')->create('request_process', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->string('code');
            $table->uuid('request_order_id');
            $table->bigInteger('user_id'); // Penanggung Jawab Pemroses
            $table->text('description')->nullable();

            // Pengiriman
            $table->enum('is_handle_logistic', [0,1])->default(0);
            $table->uuid('transport_id')->nullable();
            
            $table->enum('status', [0,1,2])->default(0); // 0: Menunggu Diproses, 1: Diproses, 2: Selesai

            $table->timestamps();

            $table->foreign('request_order_id')
                ->references('id')
                ->on('request_orders')
                ->onDelete('restrict');

            $table->foreign('transport_id')
                ->references('id')
                ->on('transports')
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
        Schema::connection('osano')->dropIfExists('request_process');
    }
};
