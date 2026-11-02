<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Portofolio_1164 : menyimpan seluruh data CV
     * (biodata, pendidikan, keahlian, dan foto).
     */
    public function up(): void
    {
        Schema::create('Portofolio_1164', function (Blueprint $table) {
            $table->increments('id_portofolio');

            // biodata
            $table->string('nama_lengkap', 150);
            $table->string('jabatan', 150);
            $table->string('email', 150);
            $table->string('telepon', 30)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->text('ringkasan')->nullable();

            // pendidikan
            $table->string('institusi', 150)->nullable();
            $table->string('jurusan', 150)->nullable();
            $table->string('tahun_lulus', 10)->nullable();

            // keahlian (satu keahlian per baris)
            $table->text('keahlian')->nullable();

            // foto (nama file hasil upload) + pilihan tampil / sembunyikan
            $table->string('foto', 255)->nullable();
            $table->boolean('tampilkan_foto')->default(true);

            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Portofolio_1164');
    }
};
