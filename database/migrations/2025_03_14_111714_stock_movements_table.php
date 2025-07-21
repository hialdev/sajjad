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
        Schema::connection('osano')->create('stock_movements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->date('date');
            $table->text('image')->nullable();
            $table->uuid('transport_id')->nullable();
            $table->enum('from_type', ['store', 'warehouse']);
            $table->uuid('from_id');
            $table->enum('to_type', ['store', 'warehouse']);
            $table->uuid('to_id');
            $table->uuid('user_id')->nullable(); // Employee
            $table->text('description')->nullable();
            $table->enum('status', [0,1,2])->default(0);

            $table->timestamps();

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
        Schema::connection('osano')->dropIfExists('stock_movements');
    }
};
