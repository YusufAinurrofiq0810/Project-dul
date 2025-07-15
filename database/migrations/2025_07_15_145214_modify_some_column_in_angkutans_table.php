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
            $table->dropColumn('NIK');
            $table->string('tipe')->nullable()->after('keterangan');

            $table->dropColumn(['No_Trayek', 'Kode_Trayek']);
            $table->string('trayek')->nullable()->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('angkutans', function (Blueprint $table) {
            $table->string('NIK')->nullable()->after('Keterangan_Perizinan');
            $table->dropColumn('tipe');
            
            $table->string('No_Trayek')->nullable()->after('No_Rangka');
            $table->string('Kode_Trayek')->nullable()->after('No_Rangka');
            $table->dropColumn('trayek');
        });
    }
};
