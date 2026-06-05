<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_rastreamento', function (Blueprint $table) {

            $table->id();

            $table->foreignId('id_venda')
                ->constrained('tb_venda')
                ->cascadeOnDelete();

            $table->enum('status', [
                   'orcamento_criado',
    'fazendo_materiais',
    'recorte',
    'pintura',
    'estufa_polimerizacao',
    'separacao',
    'aguardando_caminhoneiro',
    'concluido'
            ]);

            $table->text('observacao')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_rastreamento');
    }
};
