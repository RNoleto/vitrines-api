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
        Schema::create('contact_stores', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->string('photo')->nullable();
            $table->string('name');
            $table->string('whatsapp')->nullable();
            $table->integer('ativo')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_stores');
    }
};
