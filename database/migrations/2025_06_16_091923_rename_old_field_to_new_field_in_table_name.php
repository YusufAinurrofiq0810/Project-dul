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
        Schema::table('angkutans', function (Blueprint $table) {
            $table->renameColumn('Daya_Angkut', 'Daya_Angkut_Orang');
            $table->renameColumn('KG', 'Daya_Angkut_KG');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('angkutans', function (Blueprint $table) {
            $table->renameColumn('Daya_Angkut_Orang', 'Daya_Angkut');
            $table->renameColumn('Daya_Angkut_KG', 'KG');
        });
    }
};
