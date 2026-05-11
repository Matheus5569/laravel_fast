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
        Schema::table('tb_endereco', function (blueprint $table) {
            $table->renameColumn('create_at', 'created_at');
            $table->string('complemento')->nullable()->change();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_endereco', function (Blueprint $table) {

            $table->renameColumn('created_at', 'create_at');
            $table->string('complemento');

        });
    }
};
