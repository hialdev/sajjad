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
        Schema::connection('osano')->create('stock_movement_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('movement_id');
            $table->text('name')->nullable();
            $table->text('file')->nullable();

            $table->timestamps();

            $table->foreign('movement_id')
                ->references('id')
                ->on('stock_movements')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('osano')->dropIfExists('stock_movement_files');
    }
};
