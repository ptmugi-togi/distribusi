<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outstranding BPB</title>
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
                    {{ $userBraco }} <br>
                    INVENTORY TYPE <br>
                </td>
                <td style="width: 1%; vertical-align:top;">
                    &nbsp;<br>
                    :
                </td>
                <td style="width: 12%; vertical-align:top;">
                    &nbsp;<br>
                    {{ $invtype ?? '-' }}<br>
                </td>
                <td style="width:33%; text-align:center; vertical-align:top;">
                    OUTSTANDING BON PERMINTAAN BARANG <br>
                </td>
                <td style="width: 5%"></td>
                <td style="width:8%; vertical-align:top;">
                    DATE <br>
                    PAGE
                </td>
                <td style="width: 1%; vertical-align:top;">
                    : <br>
                    :
                </td>
                <td style="width: 20%; vertical-align:top;">
                    {{ date('d-m-Y') }} {{ date('H:i:s') }}<br>
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
                <th>NO. BPB</th>
                <th>TGL BPB</th>
                <th>ALOKASI</th>
                <th>KODE BARANG</th>
                <th>NAMA BARANG</th>
                <th>JUMLAH</th>
                <th>EST. KIRIM</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grouped = $items->groupBy('reqno');
            @endphp

            @foreach($grouped as $reqno => $rows)
                @foreach($rows as $index => $i)
                <tr>
                    @if($index == 0)
                        <td rowspan="{{ $rows->count() }}">RA-{{ $reqno }}</td>
                        <td rowspan="{{ $rows->count() }}">
                            {{ \Carbon\Carbon::parse($i->reqdt)->format('d-m-Y') }}
                        </td>
                        <td rowspan="{{ $rows->count() }}">
                            {{ $i->aloka ?? '-' }}
                        </td>
                    @endif

                    <td>{{ $i->opron }}</td>
                    <td class="td-top">{{ $i->prona }}</td>
                    <td class="right">{{ number_format($i->outstanding,0) }} {{ $i->stdqu }}</td>
                    <td>{{ \Carbon\Carbon::parse($i->eariv)->format('d-m-Y') }}</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>

        <tfoot>
            <tr class="no-border" >
                <td colspan="7" class="center" style="height: 50px"><b>** END OF REPORT **</b></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
