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
        Schema::connection('osano')->create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->text('image')->nullable();
            $table->text('bank_name')->nullable();
            $table->text('account_name')->nullable();
            $table->text('account_number')->nullable();
            $table->text('description')->nullable();
            
            $table->boolean('is_active')->default(1);
            $table->enum('type', ['bank', 'qris'])->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::connection('osano')->dropIfExists('payments');
    }
};
