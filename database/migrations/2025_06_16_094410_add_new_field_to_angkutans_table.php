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
            $table->unsignedBigInteger('jenis_angkutan_id')->nullable()->after('id');
            $table->foreign('jenis_angkutan_id')
                ->references('id')
                ->on('jenis_angkutans')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->date('Masa_berlaku_KP_Start_date')->nullable()->after('jenis_angkutan_id');
            $table->date('Masa_berlaku_KP_End_date')->nullable()->after('Masa_berlaku_KP_Start_date');
            $table->date('Masa_berlaku_SK_Start_date')->nullable()->after('Masa_berlaku_KP_End_date');
            $table->date('Masa_berlaku_SK_End_date')->nullable()->after('Masa_berlaku_SK_Start_date');
            $table->boolean('Keterangan_Perizinan')->default(false)->after('Masa_berlaku_SK_End_date');
            $table->string('NIK')->nullable()->after('Keterangan_Perizinan');
            $table->string('Jenis_BBM')->nullable()->after('NIK');
            $table->date('Masa_Berlaku_STNK')->nullable()->after('Jenis_BBM');
            $table->string('No_Rangka')->nullable()->after('Masa_Berlaku_STNK');
            $table->string('No_Trayek')->nullable()->after('No_Rangka');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('angkutans', function (Blueprint $table) {
            $table->dropForeign(['jenis_angkutan_id']);
            $table->dropColumn([
                'jenis_angkutan_id',
                'Masa_berlaku_KP_Start_date',
                'Masa_berlaku_KP_End_date',
                'Masa_berlaku_SK_Start_date',
                'Masa_berlaku_SK_End_date',
                'Keterangan_Perizinan',
                'NIK',
                'Jenis_BBM',
                'Masa_Berlaku_STNK',
                'No_Rangka',
                'No_Trayek',
            ]);
        });
    }
};
