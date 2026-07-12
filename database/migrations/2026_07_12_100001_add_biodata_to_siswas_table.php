<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('nama');
            $table->string('tempat_lahir')->nullable()->after('jenis_kelamin');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('nisn', 20)->nullable()->unique()->after('nis');
            $table->string('nik', 16)->nullable()->after('nisn');
            $table->string('agama', 30)->nullable()->after('nik');
            $table->text('alamat')->nullable()->after('agama');
            $table->string('telepon', 30)->nullable()->after('alamat');
            $table->enum('status', ['aktif', 'lulus', 'pindah', 'keluar'])->default('aktif')->after('telepon');
            $table->string('nama_ayah')->nullable()->after('status');
            $table->string('nama_ibu')->nullable()->after('nama_ayah');
            $table->string('nama_wali')->nullable()->after('nama_ibu');
            $table->boolean('is_ayah_memiliki_akun')->default(false)->after('nama_wali');
            $table->boolean('is_ibu_memiliki_akun')->default(false)->after('is_ayah_memiliki_akun');
            $table->boolean('is_wali_memiliki_akun')->default(false)->after('is_ibu_memiliki_akun');
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'nisn', 'nik', 'agama',
                'alamat', 'telepon', 'status', 'nama_ayah', 'nama_ibu', 'nama_wali',
                'is_ayah_memiliki_akun', 'is_ibu_memiliki_akun', 'is_wali_memiliki_akun',
            ]);
        });
    }
};
