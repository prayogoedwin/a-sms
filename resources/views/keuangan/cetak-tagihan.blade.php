@php $ks = app(\App\Services\KeuanganService::class); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Tagihan</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; color: #111; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        .meta { color: #555; font-size: 14px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border-bottom: 1px solid #ddd; padding: 8px 4px; text-align: left; font-size: 14px; }
        .total { font-weight: bold; }
        .footer { margin-top: 30px; font-size: 12px; color: #666; }
        @media print { .no-print { display: none; } body { margin: 0; } }
    </style>
</head>
<body>
    <button class="no-print" onclick="window.print()">Cetak</button>

    @if($mode === 'semua')
        <h1>TAGIHAN PEMBAYARAN</h1>
        <div class="meta">
            Periode: {{ $ks::BULAN_NAMA[$tagihan->bulan] }} {{ $tagihan->tahun }}<br>
            Tahun Ajaran: {{ $tagihan->tahunAjaran->nama }}<br>
            Siswa: {{ $tagihan->siswa->nama }}<br>
            NIS: {{ $tagihan->siswa->nis ?: '—' }}<br>
            Kelas: {{ $tagihan->siswa->kelas ? $tagihan->siswa->kelas->tingkat->nama . ' ' . $tagihan->siswa->kelas->nama_kelas : '—' }}
        </div>
        <table>
            <thead>
                <tr><th>Jenis</th><th>Nominal</th><th>Terbayar</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($tagihan->details as $d)
                    <tr>
                        <td>{{ $d->jenisPembayaran->nama }}</td>
                        <td>{{ $ks->formatRupiah($d->nominal) }}</td>
                        <td>{{ $ks->formatRupiah($d->nominal_terbayar) }}</td>
                        <td>{{ $ks->labelStatus($d->status) }}</td>
                    </tr>
                @endforeach
                <tr class="total">
                    <td>TOTAL</td>
                    <td>{{ $ks->formatRupiah($tagihan->total_nominal) }}</td>
                    <td>{{ $ks->formatRupiah($tagihan->total_terbayar) }}</td>
                    <td>{{ $ks->labelStatus($tagihan->status) }}</td>
                </tr>
                <tr class="total">
                    <td colspan="2">SISA</td>
                    <td colspan="2">{{ $ks->formatRupiah($tagihan->total_nominal - $tagihan->total_terbayar) }}</td>
                </tr>
            </tbody>
        </table>
    @else
        <h1>KWITANSI PEMBAYARAN</h1>
        <div class="meta">
            Periode: {{ $ks::BULAN_NAMA[$tagihan->bulan] }} {{ $tagihan->tahun }}<br>
            Siswa: {{ $tagihan->siswa->nama }}<br>
            NIS: {{ $tagihan->siswa->nis ?: '—' }}<br>
            Kelas: {{ $tagihan->siswa->kelas ? $tagihan->siswa->kelas->tingkat->nama . ' ' . $tagihan->siswa->kelas->nama_kelas : '—' }}<br>
            Jenis: <strong>{{ $detail->jenisPembayaran->nama }}</strong>
        </div>
        <table>
            <tr><td>Nominal Tagihan</td><td>{{ $ks->formatRupiah($detail->nominal) }}</td></tr>
            <tr><td>Terbayar</td><td>{{ $ks->formatRupiah($detail->nominal_terbayar) }}</td></tr>
            <tr class="total"><td>Sisa</td><td>{{ $ks->formatRupiah($detail->nominal - $detail->nominal_terbayar) }}</td></tr>
            <tr class="total"><td>Status</td><td>{{ $ks->labelStatus($detail->status) }}</td></tr>
        </table>
    @endif

    <div class="footer">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>
