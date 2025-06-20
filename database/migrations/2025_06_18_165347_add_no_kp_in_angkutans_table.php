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
            if (!Schema::hasColumn('angkutans', 'No_KP')) {
                $table->string('No_KP')->nullable()->after('No_uji');
            }
            $table->dropColumn('Tanggal_SK');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('angkutans', function (Blueprint $table) {
            if (Schema::hasColumn('angkutans', 'No_KP')) {
                $table->dropColumn('No_KP');
            }
            if (!Schema::hasColumn('angkutans', 'Tanggal_SK')) {
                $table->date('Tanggal_SK')->nullable()->after('No_SK');
            }
        });
    }
};
