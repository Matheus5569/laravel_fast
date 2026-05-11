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
        Schema::create('tb_endereco', function (Blueprint $table) {
            $table->id();
            $table->string('cep' );
            $table->string('rua', 255);
            $table->string('numero');
            $table->string('complemento');
            $table->string('bairro');
            $table->string('cidade');
            $table->string('estado');
            $table->boolean('removido')->default(false);
            $table->dateTime('create_at')->userCurrent();
            $table->dateTime('updated_at')->userCurrent()->userCurrentOnUpdate();
        });   //
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_endereco');
    }
};
