<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique();
            $table->string('nama');
            $table->enum('frekuensi', ['bulanan', 'tahunan', 'sekali']);
            $table->boolean('wajib')->default(true);
            $table->unsignedTinyInteger('bulan_berlaku')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('tarif_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_pembayaran_id')->constrained('jenis_pembayarans')->cascadeOnDelete();
            $table->foreignId('tingkat_id')->constrained('tingkats')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();
            $table->decimal('nominal', 12, 2);
            $table->timestamps();

            $table->unique(['jenis_pembayaran_id', 'tingkat_id', 'tahun_ajaran_id'], 'tarif_pembayaran_unique');
        });

        Schema::create('siswa_jenis_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('jenis_pembayaran_id')->constrained('jenis_pembayarans')->cascadeOnDelete();
            $table->decimal('nominal_override', 12, 2)->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->unique(['siswa_id', 'jenis_pembayaran_id']);
        });

        Schema::create('tagihan_bulanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();
            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->decimal('total_nominal', 12, 2)->default(0);
            $table->decimal('total_terbayar', 12, 2)->default(0);
            $table->enum('status', ['belum_lunas', 'sebagian', 'lunas'])->default('belum_lunas');
            $table->timestamp('dicetak_pada')->nullable();
            $table->timestamps();

            $table->unique(['siswa_id', 'tahun_ajaran_id', 'bulan', 'tahun'], 'tagihan_bulanan_unique');
        });

        Schema::create('tagihan_bulanan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_bulanan_id')->constrained('tagihan_bulanans')->cascadeOnDelete();
            $table->foreignId('jenis_pembayaran_id')->constrained('jenis_pembayarans')->cascadeOnDelete();
            $table->decimal('nominal', 12, 2);
            $table->decimal('nominal_terbayar', 12, 2)->default(0);
            $table->enum('status', ['belum_lunas', 'sebagian', 'lunas'])->default('belum_lunas');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['tagihan_bulanan_id', 'jenis_pembayaran_id'], 'tagihan_detail_unique');
        });

        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->date('tanggal');
            $table->decimal('total_nominal', 12, 2);
            $table->enum('metode', ['tunai', 'transfer'])->default('tunai');
            $table->string('keterangan')->nullable();
            $table->foreignId('dicatat_oleh')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('pembayaran_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_id')->constrained('pembayarans')->cascadeOnDelete();
            $table->foreignId('tagihan_bulanan_detail_id')->constrained('tagihan_bulanan_details')->cascadeOnDelete();
            $table->decimal('nominal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_details');
        Schema::dropIfExists('pembayarans');
        Schema::dropIfExists('tagihan_bulanan_details');
        Schema::dropIfExists('tagihan_bulanans');
        Schema::dropIfExists('siswa_jenis_pembayarans');
        Schema::dropIfExists('tarif_pembayarans');
        Schema::dropIfExists('jenis_pembayarans');
    }
};
