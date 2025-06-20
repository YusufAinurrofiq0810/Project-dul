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
        Schema::create('angkutans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('perusahaan_id');
            $table->foreign('perusahaan_id')->references('id')->on('perusahaans')->onDelete('cascade');
            $table->string('TNKB')->nullable();
            $table->string('No_uji')->nullable();
            $table->string('No_KP')->nullable();
            $table->string('No_NIB')->nullable();
            $table->string('No_Mesin')->nullable();
            $table->string('No_SK')->nullable();
            $table->date('Tanggal_SK')->nullable();
            $table->string('Kode_Trayek')->nullable();
            $table->string('No_Seri')->nullable();
            $table->integer('Daya_Angkut')->nullable();
            $table->integer('KG')->nullable();
            $table->string('Tahun_Pembuatan')->nullable();
            $table->unsignedBigInteger('merek_id');
            $table->foreign('merek_id')->references('id')->on('mereks')->onDelete('cascade');
            $table->string('Alamat');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angkutans');
    }
};
