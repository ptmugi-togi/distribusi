<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS-{{ $warco }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 8pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-ttd {
            width: 50% !important;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
            font-size: inherit;
        }

        .no-border td, .no-border th {
            border: none !important;
        }

        .right { text-align: right; }
        .center { text-align: center; }
        .td-top { vertical-align: top; word-wrap: break-word; }

        thead { display: table-header-group; }
    </style>
</head>
<body>

{{-- Header dokumen --}}
<htmlpageheader name="docHeader">
    <table class="no-border" style="margin-bottom:5px; font-size:8pt;">
        <tr>
            <td style="width:20%; vertical-align:top;">
                PT. MUGI PUSAT <br>
                WAREHOUSE <br>
                INVENTORY TYPE <br>
                PRODUCT SUB-GROUP <br>
            </td>
            <td style="width: 1%; vertical-align:top;">
                &nbsp;<br>
                :<br>
                :<br>
                :
            </td>
            <td style="width: 12%; vertical-align:top;">
                &nbsp;<br>
                {{ $warco }}<br>
                {{ $invtype ?? '-' }}<br>
                {{ $subgroup ?? '-' }}
            </td>
            <td style="width:33%; text-align:center; vertical-align:top;">
                STOCK MOVEMENT SUMMARY <br>
                --------------------------------------------- <br>
                AS OF : {{ \Carbon\Carbon::parse($asof)->format('d-m-Y') }}
            </td>
            <td style="width: 13%"></td>
            <td style="width:8%; vertical-align:top;">
                DATE <br>
                TIME <br>
                PAGE
            </td>
            <td style="width: 1%; vertical-align:top;">
                : <br>
                : <br>
                :
            </td>
            <td style="width: 11%; vertical-align:top;">
                {{ date('d-m-Y') }} <br>
                {{ date('H:i:s') }} <br>
                {PAGENO}
            </td>
        </tr>
    </table>
</htmlpageheader>

{{-- Aktifkan header di semua halaman --}}
<sethtmlpageheader name="docHeader" value="on" show-this-page="1" />

{{-- Tabel data --}}
<table width="100%" border="1" cellspacing="0" cellpadding="4">
    <thead>
        <tr>
            <th>Kode Produk</th>
            <th>Nama Produk</th>
            <th>Satuan</th>
            <th>Awal</th>
            <th>Masuk</th>
            <th>Keluar</th>
            <th>Akhir</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalAwal = 0;
            $totalMasuk = 0;
            $totalKeluar = 0;
            $totalAkhir = 0;
        @endphp

        @foreach($items as $i)
        <tr>
            <td>{{ $i->opron }}</td>
            <td>{{ $i->prona }}</td>
            <td>{{ $i->stdqu }}</td>
            <td class="right">{{ number_format($i->awal,0) }}</td>
            <td class="right">{{ number_format($i->masuk,0) }}</td>
            <td class="right">{{ number_format($i->keluar,0) }}</td>
            <td class="right">{{ number_format($i->akhir,0) }}</td>
        </tr>

        @php
            $totalAwal += $i->awal;
            $totalMasuk += $i->masuk;
            $totalKeluar += $i->keluar;
            $totalAkhir += $i->akhir;
        @endphp

        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th colspan="3" class="left">TOTAL:</th>
            <th class="right">{{ number_format($totalAwal,0) }}</th>
            <th class="right">{{ number_format($totalMasuk,0) }}</th>
            <th class="right">{{ number_format($totalKeluar,0) }}</th>
            <th class="right">{{ number_format($totalAkhir,0) }}</th>
        </tr>
    </tfoot>
</table>
<table class="table-ttd" border="1" style="margin-top:30px; font-size:8pt;">
    <tr>
        <th>Dibuat</th>
        <th colspan="2">Mengetahui</th>
    </tr>
    <tr>
        <td class="center" style="width: 33%"><br><br><br><br><br>Logistik</td>
        <td class="center" style="width: 33%"><br><br><br><br><br>F & A</td>
        <td class="center" style="width: 33%"><br><br><br><br><br>Logistik Manager</td>
    </tr>
</table>
</body>
</html>
