<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_baia', function (Blueprint $table) {

            $table->id();

            $table->string('nome', 100);

            $table->text('descricao')->nullable();

            $table->enum('status', [
                'Livre',
                'Ocupada',
                'Manutencao'
            ])->default('Livre');

            $table->foreignId('id_admin')->constrained('users');

            $table->boolean('removido')->default(false);

            $table->dateTime('create_at')->useCurrent();

            $table->dateTime('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_baia');
    }
};