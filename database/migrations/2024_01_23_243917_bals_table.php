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
        Schema::connection('osano')->create('bals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('nowin_type', ['warehouse', 'store']);
            $table->uuid('nowin_id')->nullable();
            $table->uuid('purchase_receive_id')->nullable();

            $table->string('code');
            $table->string('name');
            $table->text('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_unpack')->default(0);
            
            $table->timestamps();

            $table->foreign('purchase_receive_id')
                ->references('id')
                ->on('purchase_receives')
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
        Schema::connection('osano')->dropIfExists('bals');
    }
};
