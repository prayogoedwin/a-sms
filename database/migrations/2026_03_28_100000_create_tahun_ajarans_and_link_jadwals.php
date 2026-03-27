<?php

use App\Models\TahunAjaran;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahun_ajarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 30)->unique();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();
        });

        TahunAjaran::firstOrCreate(
            ['nama' => '2025/2026'],
            ['tanggal_mulai' => '2025-07-01', 'tanggal_selesai' => '2026-06-30']
        );
        TahunAjaran::firstOrCreate(
            ['nama' => '2024/2025'],
            ['tanggal_mulai' => '2024-07-01', 'tanggal_selesai' => '2025-06-30']
        );

        Schema::table('jadwals', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')->nullable()->after('semester')->constrained('tahun_ajarans')->restrictOnDelete();
        });

        $rows = DB::table('jadwals')->select('id', 'tahun_ajaran')->get();
        foreach ($rows as $row) {
            $nama = $row->tahun_ajaran ?: '2025/2026';
            $ta = TahunAjaran::firstOrCreate(
                ['nama' => $nama],
                ['tanggal_mulai' => null, 'tanggal_selesai' => null]
            );
            DB::table('jadwals')->where('id', $row->id)->update(['tahun_ajaran_id' => $ta->id]);
        }

        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropColumn('tahun_ajaran');
        });
    }

    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->string('tahun_ajaran', 20)->nullable()->after('semester');
        });

        $rows = DB::table('jadwals')
            ->leftJoin('tahun_ajarans', 'jadwals.tahun_ajaran_id', '=', 'tahun_ajarans.id')
            ->select('jadwals.id', 'tahun_ajarans.nama as ta_nama')
            ->get();

        foreach ($rows as $row) {
            DB::table('jadwals')->where('id', $row->id)->update([
                'tahun_ajaran' => $row->ta_nama ?? '2025/2026',
            ]);
        }

        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropColumn('tahun_ajaran_id');
        });

        Schema::dropIfExists('tahun_ajarans');
    }
};
