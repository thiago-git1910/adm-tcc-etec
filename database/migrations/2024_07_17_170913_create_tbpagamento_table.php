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
        Schema::create('tbpagamento', function (Blueprint $table) {
            $table->id('idPagamento');
            $table->float('pixPagamento');
            $table->float('debitoPagamento');
            $table->float('creditoPagamento');
            $table->float('boletoPagamento');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbpagamento');

    }

};
