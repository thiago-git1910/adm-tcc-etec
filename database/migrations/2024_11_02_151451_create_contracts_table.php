<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {


    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id('id'); // Auto-increment primary key

            // Foreign key for idSolicitarPedido without auto-increment
            $table->unsignedBigInteger('idSolicitarPedido');

            // IDs for contratante and contratado
            $table->uuid('idContratante');
            $table->uuid('idContratado');

            // Contract details
            $table->string('valor');
            $table->date('data');
            $table->time('hora');
            $table->string('desc_servicoRealizado');
            $table->enum('status', ['pendente', 'aceito', 'concluido', 'cancelado'])->default('pendente');
            $table->string('forma_pagamento');
            $table->timestamps();

            // Foreign keys
            $table->foreign('idSolicitarPedido')->references('idSolicitarPedido')->on('tbSolicitarPedido')->onDelete('cascade');
            $table->foreign('idContratante')->references('idContratante')->on('tbContratante')->onDelete('cascade');
            $table->foreign('idContratado')->references('idContratado')->on('tbContratado')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
