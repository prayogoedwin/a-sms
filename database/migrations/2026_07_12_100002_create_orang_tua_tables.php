<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orang_tuas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('nama');
            $table->string('nik', 16)->nullable();
            $table->string('telepon', 30)->nullable();
            $table->text('alamat')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->timestamps();
        });

        Schema::create('siswa_orang_tua', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orang_tua_id')->constrained('orang_tuas')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->enum('hubungan', ['ayah', 'ibu', 'wali']);
            $table->boolean('is_penanggung_jawab')->default(false);
            $table->timestamps();

            $table->unique(['orang_tua_id', 'siswa_id']);
            $table->unique(['siswa_id', 'hubungan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa_orang_tua');
        Schema::dropIfExists('orang_tuas');
    }
};
