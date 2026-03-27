<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tingkats', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->unsignedTinyInteger('urutan')->nullable();
            $table->timestamps();
        });

        Schema::create('mata_pelajarans', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->nullable()->unique();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('nip', 50)->nullable()->unique();
            $table->string('nama');
            $table->string('telepon', 30)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });

        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('pegawai_id')->nullable()->unique()->constrained('pegawais')->nullOnDelete();
            $table->string('nip', 50)->nullable()->unique();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tingkat_id')->constrained('tingkats')->restrictOnDelete();
            $table->string('nama_kelas', 50);
            $table->foreignId('wali_kelas_guru_id')->nullable()->constrained('gurus')->nullOnDelete();
            $table->timestamps();
            $table->unique(['tingkat_id', 'nama_kelas']);
        });

        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();
            $table->string('nis', 50)->nullable()->unique();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->restrictOnDelete();
            $table->foreignId('guru_id')->constrained('gurus')->restrictOnDelete();
            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('tahun_ajaran', 20);
            $table->enum('semester', ['ganjil', 'genap']);
            $table->timestamps();
        });

        Schema::create('nilai_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwals')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->decimal('nilai_angka', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('dinilai_oleh_guru_id')->nullable()->constrained('gurus')->nullOnDelete();
            $table->timestamps();
            $table->unique(['jadwal_id', 'siswa_id']);
        });

        Schema::create('absensi_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwals')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'terlambat', 'pulang_cepat', 'alpha']);
            $table->text('keterangan')->nullable();
            $table->foreignId('diinput_oleh_guru_id')->nullable()->constrained('gurus')->nullOnDelete();
            $table->timestamps();
            $table->unique(['jadwal_id', 'siswa_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_siswas');
        Schema::dropIfExists('nilai_siswas');
        Schema::dropIfExists('jadwals');
        Schema::dropIfExists('siswas');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('gurus');
        Schema::dropIfExists('pegawais');
        Schema::dropIfExists('mata_pelajarans');
        Schema::dropIfExists('tingkats');
    }
};
