<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Penyewaan</title>
    <style>
        /* Style sesuai kebutuhan Anda */
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    @php
        $getBulan = request()->input('bulan');
        $tahun = request()->input('tahun');
        $total = 0;

        $namaBulanIndonesia = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        $bulan = $namaBulanIndonesia[$getBulan - 1];
    @endphp

    <h3 style="text-align: center">Laporan Transaksi Penyewaan - Bulan {{ $bulan }} Tahun {{ $tahun }}</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Customer</th>
                <th>Tanggal Sewa</th>
                <th>Tanggal Kembali</th>
                <th>Durasi Sewa</th>
                <th>Tanggal Pengembalian Mobil</th>
                <th>Status</th>
                <th>Lama Terlambat</th>
                <th>Denda per Hari (Rp)</th>
                <th>Total Denda (Rp)</th>
                <th>Total Bayar (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksis as $index => $transaksi)
                <tr>
                    <td style="text-align: center">{{ $index + 1 }}</td>
                    <td>{{ $transaksi->customer->nama }}</td>
                    <td style="text-align: center">{{ $transaksi->tgl_sewa }}</td>
                    <td style="text-align: center">{{ $transaksi->tgl_kembali }}</td>
                    <td style="text-align: center">{{ $transaksi->durasi_sewa }} Hari</td>
                    <td style="text-align: center">
                        @if ($transaksi->tgl_pengembalian_mobil == NULL)
                        -
                        @else
                        {{ $transaksi->tgl_pengembalian_mobil }}
                        @endif
                    </td>
                    <td style="text-align: center">
                        @if($transaksi->status == '0')
                            Belum Dikembalikan
                        @elseif($transaksi->status == '1')
                            Sudah Dikembalikan
                        @else
                            Not Found.
                        @endif
                    </td>
                    <td style="text-align: center">{{ $transaksi->lama_telat }} Hari</td>
                    <td style="text-align: right">{{ number_format($transaksi->denda_per_hari) }}</td>
                    <td style="text-align: right">{{ number_format($transaksi->total_denda) }}</td>
                    <td style="text-align: right">{{ number_format($transaksi->total_bayar) }}</td>

                    @php $total += $transaksi->total_bayar; @endphp
                </tr>
            @endforeach
            <tr>
                <td colspan="8" style="text-align: right; font-weight: bold;">Total Transaksi</td>
                <td colspan="3" style="text-align: right">Rp. {{ number_format($total) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
