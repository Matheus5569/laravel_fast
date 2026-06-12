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
        Schema::table('tb_venda', function ($table) {

            $table->string('baia')->nullable();

            $table->string('caminhoneiro')->nullable();

            $table->text('destino')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_venda', function ($table) {

            $table->dropColumn([
                'baia',
                'caminhoneiro',
                'destino',
            ]);

        });
    }
};
