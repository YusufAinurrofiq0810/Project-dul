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
            $table->text('keterangan')->nullable()->after('Alamat');
            $table->text('Alamat')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('angkutans', function (Blueprint $table) {
            $table->dropColumn('keterangan');
            $table->string('Alamat')->nullable()->change();
        });
    }
};
