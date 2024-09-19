<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('status');
            $table->string('currency_from');
            $table->decimal('amount_from')->unsigned();
            $table->string('currency_to');
            $table->decimal('amount_to')->unsigned();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('currency_from');
            $table->index('amount_from');
            $table->index('currency_to');
            $table->index('amount_to');
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
