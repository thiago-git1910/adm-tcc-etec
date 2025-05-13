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
        Schema::create('chats', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Relacionamento com a sala de chat
            $table->foreignUuid('chat_room_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            // Relacionamento com a tabela users (opcional)
            $table->foreignUuid('user_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');

            // Relacionamento com contratantes (opcional)
            $table->foreignUuid('idContratante')->nullable()
                ->constrained('tbcontratante', 'idContratante')
                ->onUpdate('cascade')->onDelete('set null');

            // Relacionamento com contratados (opcional)
            $table->foreignUuid('idContratado')->nullable()
                ->constrained('tbcontratado', 'idContratado')
                ->onUpdate('cascade')->onDelete('set null');

            // Conteúdo da mensagem
            $table->text('message');

            // Status de leitura da mensagem
            $table->boolean('is_read')->default(false);

            // Timestamps
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
