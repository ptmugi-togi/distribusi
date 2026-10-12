<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>OUTSTANDING WORK ORDER LIST</title>
        <style>
            body {
                font-family: sans-serif;
                font-size: 8pt;
                color: #000;
            }

            table.table-bordered {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }

            table.table-bordered th, 
            table.table-bordered td {
                border: 1px solid #000000;
                padding: 5px;
                vertical-align: middle;
            }

            table.table-bordered th {
                font-weight: bold;
                text-align: center;
            }

            table.no-border {
                width: 100%;
                border-collapse: collapse;
            }

            table.no-border td {
                border: none !important;
                padding: 3px 0;
            }

            .right {
                text-align: right;
            }

            .center {
                text-align: center;
            }

            .bold {
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <table class="no-border" style="margin-bottom: 10px;">
            <tr>
                <td width="35%" class="bold">
                    PT. MUGI, {{ strtoupper($brana) }}
                </td>
                <td width="30%" class="center bold">
                    OUTSTANDING WORK ORDER LIST<br>
                    -----------------------------------<br>
                    DATE : {{ date('d-m-Y', strtotime($start)) }} S/D {{ date('d-m-Y', strtotime($end)) }}
                </td>
                <td width="35%" class="right">
                    PRINT DATE : {{ date('d-m-Y / H:i:s') }}
                </td>
            </tr>
        </table>

        <table class="no-border" style="margin-bottom: 10px;">
            <tr>
                <td width="50%">
                    <b>WO STATUS :</b> {{ strtoupper($wo_status) }}
                </td>
                <td width="50%" class="right">
                    <b>SORT BY :</b> {{ strtoupper($sort_by == 'wonum' ? 'WO NO.' : 'PRODUCT NO.') }}
                </td>
            </tr>
        </table>

        <table class="table-bordered">
            <thead>
                <tr>
                    <th width="15%">NO WO</th>
                    <th width="10%">TANGGAL</th>
                    <th width="30%">KODE & NAMA BARANG</th>
                    <th width="10%">QTY WO</th>
                    <th width="10%">QTY BBM</th>
                    <th width="10%">QTY OUTSTANDING</th>
                    <th width="15%">REFERENSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $wonum => $rows)
                    @php
                        $rowCount = count($rows);
                        $firstRow = $rows->first();
                    @endphp
                    @foreach($rows as $index => $item)
                        @php
                            $qtyWo = (float) $item->outqt;
                            $qtyBbm = (float) $item->acqty;
                            $qtyOutstanding = $qtyWo - $qtyBbm;
                        @endphp
                        <tr>
                            @if($index === 0)
                                <td rowspan="{{ $rowCount }}" class="bold center">WO {{ $firstRow->wonum }}</td>
                                <td rowspan="{{ $rowCount }}" class="center">
                                    {{ $firstRow->wodat ? date('d-m-Y', strtotime($firstRow->wodat)) : '-' }}
                                </td>
                            @endif

                            <td>
                                {{ $item->outpr }} - {{ $item->prona ?? 'N/A' }}
                            </td>
                            <td class="right">{{ number_format($qtyWo, 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($qtyBbm, 0, ',', '.') }}</td>
                            <td class="right bold">{{ number_format($qtyOutstanding, 0, ',', '.') }}</td>
                            
                            @if($index === 0)
                                <td rowspan="{{ $rowCount }}">{{ $firstRow->refer ?? '-' }}</td>
                            @endif
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="7" class="center">Data tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>