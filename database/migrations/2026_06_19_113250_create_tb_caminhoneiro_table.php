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
        Schema::create('tb_caminhoneiro', function (Blueprint $table) {

            $table->id();

            $table->string('nome', 100);

            $table->string('cpf', 14);

            $table->string('telefone', 20);

            $table->string('placa_caminhao', 20);

            $table->enum('status', [
                'Livre',
                'EmViagem',
                'Folga'
            ])->default('Livre');

            $table->foreignId('id_admin')->constrained('users');

            $table->boolean('removido')->default(false);

            $table->dateTime('created_at')->useCurrent();

            $table->dateTime('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_caminhoneiro');
    }
};
