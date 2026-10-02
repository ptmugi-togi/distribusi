<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>PURCHASE ORDER LIST</title>
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
                    PURCHASE ORDER LIST<br>
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
                <td width="33%">
                    <b>PO STATUS :</b> {{ strtoupper($po_status) }}
                </td>
                <td width="33%">
                </td>
                <td width="33%" class="right">
                    <b>SORT BY :</b> {{ strtoupper($sort_by == 'pono' ? 'PO NO.' : ($sort_by == 'prona' ? 'PRODUCT NAME' : $sort_by)) }}
                </td>
            </tr>
        </table>

        <table class="table-bordered">
            <thead>
                <tr>
                    <th width="11%">PO NO.</th>
                    <th width="14%">SUPPLIER</th>
                    <th width="7%">PO DATE</th>
                    <th width="18%">PRODUCT NAME</th>
                    <th width="6%">QTY ORDER</th>
                    <th width="6%">QTY REC.</th>
                    <th width="5%">STDQU</th>
                    <th width="5%">CCY</th>
                    <th width="8%">PRICE</th>
                    <th width="10%">AMOUNT</th>
                    <th width="10%">EXPECTED DLV.</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $pono => $rows)
                    @php
                        $rowCount = count($rows);
                        $firstRow = $rows->first();
                        $totalAmount = $rows->sum('netpr');
                    @endphp
                    @foreach($rows as $index => $item)
                        <tr>
                            @if($index === 0)
                                <td rowspan="{{ $rowCount }}" class="bold">{{ $firstRow->pono }}</td>
                                <td rowspan="{{ $rowCount }}">{{ $firstRow->supna }}</td>
                                <td rowspan="{{ $rowCount }}" class="center">{{ $firstRow->podat ? date('d-m-Y', strtotime($firstRow->podat)) : '-' }}</td>
                            @endif

                            {{-- Kolom Detail Item --}}
                            <td>{{ $item->prona }}</td>
                            <td class="right">{{ number_format($item->poqty, 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($item->rcqty, 0, ',', '.') }}</td>
                            <td class="center">{{ $item->stdqu ?? '-' }}</td>
                            <td class="center">{{ $item->curco ?? 'IDR' }}</td>
                            <td class="right">
                                @if ($item->curco != 'IDR')
                                    {{ number_format($item->price, 2, '.', ',') }}
                                @else
                                    {{ number_format($item->price, 0, ',', '.') }}
                                @endif
                            </td>
                            @if($index === 0)
                                <td rowspan="{{ $rowCount }}" class="right">
                                    @if ($item->curco != 'IDR')
                                        {{ number_format($totalAmount, 2, '.', ',') }}
                                    @else
                                        {{ number_format($totalAmount, 0, ',', '.') }}
                                    @endif
                                </td>
                            @endif
                            <td class="center">{{ $item->edeld ? date('d-m-Y', strtotime($item->edeld)) : '-' }}</td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="11" class="center">Data tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>