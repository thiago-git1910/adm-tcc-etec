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
        Schema::create('tbcontratante', function (Blueprint $table) {
            $table->id();
            $table->uuid('idContratante')->unique();
            $table->string('nomeContratante', 55);
            $table->char('cpfContratante', 14)->unique();
            $table->string('password');
            $table->date('nascContratante');
            $table->boolean('is_suspended')->default(false);
            $table->string('emailContratante', 180)->unique();
            $table->char('telefoneContratante', 18)->unique();
            $table->string('ruaContratante', 90);
            $table->string('cepContratante', 9);
            $table->string('numCasaContratante', 14);
            $table->string('complementoContratante')->nullable();
            $table->string('bairroContratante', 90);
            $table->string('ufContratante', 10)->nullable();
            $table->string('cidadeContratante', 50)->nullable();
            $table->string('imagemContratante', 399);
            $table->timestamps();
        });
    }

    public function down()
    {

        Schema::dropIfExists('tbcontratante');
    }
};
