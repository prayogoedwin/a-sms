<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orang_tua_id')->constrained('orang_tuas')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->date('tanggal_transfer');
            $table->decimal('total_nominal', 12, 2);
            $table->enum('metode', ['transfer'])->default('transfer');
            $table->string('bukti_path');
            $table->string('keterangan')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('diverifikasi_pada')->nullable();
            $table->timestamps();
        });

        Schema::create('pengajuan_pembayaran_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_pembayaran_id')->constrained('pengajuan_pembayarans')->cascadeOnDelete();
            $table->foreignId('tagihan_bulanan_detail_id')->constrained('tagihan_bulanan_details')->cascadeOnDelete();
            $table->decimal('nominal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_pembayaran_details');
        Schema::dropIfExists('pengajuan_pembayarans');
    }
};
