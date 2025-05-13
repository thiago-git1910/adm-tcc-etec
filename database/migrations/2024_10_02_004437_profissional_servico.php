<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profissional_servico', function (Blueprint $table) {
            $table->uuid('idcontratado');

            $table->foreign('idcontratado')->references('idContratado')->on('tbcontratado')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('idServicos')->constrained('tbservicos', 'idServicos')
                  ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down(): void
    {

        Schema::table('profissional_servico', function (Blueprint $table) {
            $table->dropForeign(['idContratado']);
            $table->dropForeign(['idServicos']);

        });
        Schema::dropIfExists('profissional_servico');
    }
};
