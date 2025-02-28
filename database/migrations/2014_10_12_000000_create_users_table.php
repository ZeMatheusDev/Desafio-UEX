<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('senha');
            $table->string('nome');
            $table->string('cpf')->unique();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
            $table->string('status')->default('active');
            $table->boolean('deleted')->default(false);
            $table->string('token')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};