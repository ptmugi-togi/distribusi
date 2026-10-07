<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>NATIONAL STOCK POSITION</title>
        <style>
            body { 
                font-family: sans-serif; 
                font-size: 8pt; 
                color: #000; 
            }
            table { 
                width: 100%; 
                border-collapse: collapse; 
            }
            .header-table td { 
                padding: 2px 0; 
                border: none; 
            }
            .data-table th {
                border-top: 1px solid #000; 
                border-bottom: 1px solid #000; 
                padding: 4px 2px; 
                font-weight: bold; 
                font-size: 7.5pt; 
            }
            .data-table td { 
                padding: 2px; 
                vertical-align: top; 
                border: none; 
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
            .subgroup-header { 
                font-weight: bold; 
                padding-top: 8px; 
                padding-bottom: 4px; 
            }
            .breakdown { 
                font-size: 7pt; 
                padding-left: 15px; 
                color: #222; 
            }
        </style>
    </head>
    <body>
        <table class="header-table" style="margin-bottom: 10px;">
            <tr>
                <td width="35%" class="bold">PT. MUGI</td>
                <td width="30%" class="center bold" style="font-size: 12pt;">National Stock Position</td>
                <td width="35%" class="right">PRINT DATE : {{ date('d-m-Y / H:i:s') }}</td>
            </tr>
        </table>

        <table class="header-table" style="margin-bottom: 10px;">
            <tr>
                <td width="50%"></td>
                <td width="50%" class="right">
                    <b>SORT BY :</b> {{ strtoupper($sort_by == 'opron' ? 'PRODUCT NO.' : ($sort_by == 'prona' ? 'PRODUCT NAME' : $sort_by)) }}
                </td>
            </tr>
        </table>

        <table class="data-table">
            <thead>
                <tr>
                    <th width="10%" style="text-align: left;">KODE BARANG</th>
                    <th width="25%" style="text-align: left;">NAMA BARANG</th>
                    <th width="5%" class="center">AVAIL<br>D3</th>
                    <th width="5%" class="center">BOP</th>
                    <th width="5%" class="center">RUSAK</th>
                    <th width="5%" class="center">BPB</th>
                    <th width="5%" class="center">INDEN</th>
                    <th width="5%" class="center">PST</th>
                    @foreach($branches as $b)
                        <th width="3%" class="center">{{ $b->braco }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($groupedItems as $subgroupName => $products)
                    <tr>
                        <td colspan="{{ 8 + count($branches) }}" class="subgroup-header">
                            PRODUCT SUB-GROUP : {{ $subgroupName }}
                        </td>
                    </tr>

                    @foreach($products as $item)
                        @php $pst = $item->avail_d3 + $item->bop - $item->bpb + $item->inden; @endphp
                        <tr>
                            <td>{{ $item->opron }}</td>
                            <td>{{ $item->prona }}</td>
                            <td class="center">{{ $item->avail_d3 > 0 ? $item->avail_d3 : '.' }}</td>
                            <td class="center">{{ $item->bop > 0 ? $item->bop : '.' }}</td>
                            <td class="center">{{ $item->rusak > 0 ? $item->rusak : '.' }}</td>
                            <td class="center">{{ $item->bpb > 0 ? $item->bpb : '.' }}</td>
                            <td class="center">{{ $item->inden > 0 ? $item->inden : '.' }}</td>
                            <td class="center bold">{{ $pst > 0 ? $pst : '.' }}</td>

                            @foreach($branches as $b)
                                @php $qty = $item->branch_stocks[$b->braco] ?? 0; @endphp
                                <td class="center">{{ $qty > 0 ? $qty : '.' }}</td>
                            @endforeach
                        </tr>

                        @if($item->inden_breakdown->isNotEmpty())
                            <tr>
                                <td colspan="5"></td>
                                <td colspan="{{ 3 + count($branches) }}" class="breakdown">
                                    <strong>INDEN BREAKDOWN :</strong><br>
                                    @foreach($item->inden_breakdown as $bd)
                                        &nbsp;&nbsp;{{ $bd->qty }} {{ $bd->potype }} {{ $bd->pono }}<br>
                                    @endforeach
                                </td>
                            </tr>
                        @endif

                        @if($item->bpb_breakdown->isNotEmpty())
                            <tr>
                                <td colspan="5"></td>
                                <td colspan="{{ 3 + count($branches) }}" class="breakdown">
                                    <strong>BPB BREAKDOWN :</strong><br>
                                    @foreach($item->bpb_breakdown as $bd)
                                        &nbsp;&nbsp;{{ $bd->qty }} {{ $bd->braco }} {{ $bd->reqno }} : {{ $bd->aloka }}<br>
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="{{ 8 + count($branches) }}" style="height: 10px; border: none !important;"></td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="{{ 8 + count($branches) }}" class="center" style="padding: 10px;">Data tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </body>
</html>