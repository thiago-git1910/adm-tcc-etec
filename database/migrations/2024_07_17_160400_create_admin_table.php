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
        Schema::create('tbAdmin', function (Blueprint $table) {
            $table->id('idAdmin');
            $table->string('nomeAdmin', 55);
            $table->string('sobrenomeAdmin', 90);
            $table->char('cpfAdmin', 14)->unique();
            $table->date('nascAdmin');
            $table->string('emailAdmin', 180)->unique();
            $table->string('password', 50);
            $table->string('imagem')->nullable();
            $table->string('tokenAdmin', 50);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbAdmin');
    }
};
