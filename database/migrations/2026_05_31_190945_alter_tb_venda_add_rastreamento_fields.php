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
        Schema::table('tb_venda', function (Blueprint $table) {

            $table->string('qr_code')->unique()->nullable();

            $table->enum('status_atual', [
                'orcamento_criado',
                'fazendo_materiais',
                'recorte',
                'pintura',
                'estufa_polimerizacao',
                'separacao',
                'aguardando_caminhoneiro',
                'concluido'
            ])->default('orcamento_criado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_venda', function (Blueprint $table) {
            $table->dropColumn([
                'qr_code',
                'status_atual'
            ]);
        });
    }
};
