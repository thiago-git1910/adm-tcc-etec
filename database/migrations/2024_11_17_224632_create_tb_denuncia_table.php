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
        Schema::create('tb_denuncia', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('idContratante')->nullable()
            ->constrained('tbcontratante', 'idContratante')
            ->onUpdate('cascade')->onDelete('set null');

            $table->foreignUuid('idContratado')->nullable()
            ->constrained('tbcontratado', 'idContratado')
            ->onUpdate('cascade')->onDelete('set null');
            $table->string('motivo')->default('Não especificado');
            $table->string('descricao');
            $table->string('categoria');
            $table->enum('status', ['emAberto', 'emAnalise', 'concluido', 'cancelado'])->default('emAberto');
            $table->string('imagemDenuncia', 999)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_denuncia');
    }
};
