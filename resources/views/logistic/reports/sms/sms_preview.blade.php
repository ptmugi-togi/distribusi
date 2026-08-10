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
    <table class="no-border" style="margin-bottom:5px; font-size:7pt;">
        <tr>
            <td style="width:22%; vertical-align:top;">
                PT. MUGI PUSAT <br>
                WAREHOUSE <br>
                INVENTORY TYPE <br>
                PRODUCT SUB-GROUP <br>
                PRODUCT SUB - SUBGROUP <br>
            </td>
            <td style="width: 1%; vertical-align:top;">
                &nbsp;<br>
                :<br>
                :<br>
                :<br>
                :
            </td>
            <td style="width: 18%; vertical-align:top;">
                &nbsp;<br>
                {{ $warco }}<br>
                {{ $itype ?? '-' }}<br>
                {{ $sgrup ?? '-' }}<br>
                {{ $ssgrup ?? '-' }}
            </td>
            <td style="width:33%; text-align:center; vertical-align:top;">
                STOCK MOVEMENT SUMMARY <br>
                --------------------------------------------- <br>
                AS OF : {{ \Carbon\Carbon::parse($asof)->format('d-m-Y') }}
            </td>
            <td style="width: 5%"></td>
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
            $group = $items->groupBy('descr_itype');

            $grandAwal = 0;
            $grandMasuk = 0;
            $grandKeluar = 0;
            $grandAkhir = 0;
        @endphp

        @foreach($group as $namaGroup => $rows)
            <tr>
                <td colspan="7">
                    <strong>Inventory Type : {{ $namaGroup }}</strong>
                </td>
            </tr>

            @php
            $subAwal = 0;
            $subMasuk = 0;
            $subKeluar = 0;
            $subAkhir = 0;
            @endphp

            @foreach($rows as $i)

                <tr>
                    <td>{{ $i->opron }}</td>
                    <td>{{ $i->prona }}</td>
                    <td>{{ $i->stdqu }}</td>
                    <td class="right">{{ number_format($i->awal) }}</td>
                    <td class="right">{{ number_format($i->masuk) }}</td>
                    <td class="right">{{ number_format($i->keluar) }}</td>
                    <td class="right">{{ number_format($i->akhir) }}</td>
                </tr>

                @php
                    $subAwal += $i->awal;
                    $subMasuk += $i->masuk;
                    $subKeluar += $i->keluar;
                    $subAkhir += $i->akhir;

                    $grandAwal += $i->awal;
                    $grandMasuk += $i->masuk;
                    $grandKeluar += $i->keluar;
                    $grandAkhir += $i->akhir;
                @endphp

            @endforeach

            <tr style="font-weight:bold">
                <td colspan="3">TOTAL {{ $namaGroup }}</td>
                <td class="right">{{ number_format($subAwal) }}</td>
                <td class="right">{{ number_format($subMasuk) }}</td>
                <td class="right">{{ number_format($subKeluar) }}</td>
                <td class="right">{{ number_format($subAkhir) }}</td>
            </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th colspan="3">GRAND TOTAL</th>
            <th class="right">{{ number_format($grandAwal) }}</th>
            <th class="right">{{ number_format($grandMasuk) }}</th>
            <th class="right">{{ number_format($grandKeluar) }}</th>
            <th class="right">{{ number_format($grandAkhir) }}</th>
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
