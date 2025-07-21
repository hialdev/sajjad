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
        Schema::connection('osano')->create('customer_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->string('code');
            $table->uuid('customer_id');
            $table->uuid('payment_id')->nullable();
            $table->uuid('user_id'); // Penanggung Jawab Pemroses
            $table->text('description')->nullable();

            $table->decimal('total_price', 15, 2)->nullable();
            $table->integer('tax')->nullable();
            $table->decimal('total_price_taxed', 15, 2)->nullable();

            // Pengiriman
            $table->text('payment_receipt')->nullable();
            $table->boolean('is_finished')->default(0);

            $table->timestamps();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('restrict');

            $table->foreign('payment_id')
                ->references('id')
                ->on('payments')
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
        Schema::connection('osano')->dropIfExists('customer_orders');
    }
};
