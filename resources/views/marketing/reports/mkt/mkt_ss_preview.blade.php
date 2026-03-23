<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Report-MKT-OC GROUP PRODUCT</title>
<style>
    body {
        font-family: sans-serif;
        font-size: 8pt;
        margin-bottom: 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #000;
        padding: 5px;
        font-size: inherit;
    }

    tr {
        page-break-inside: avoid;
    }
    
    .no-border td, .no-border th {
        border: none !important;
    }

    .right {
        text-align: right;
    }

    .center {
        text-align: center;
    }

    .content {
        flex: 1;
    }

    /* Bagian total + tanda tangan */
    .footer-summary {
        margin-top: 20px;
        page-break-inside: avoid;
    }

    .footer-summary table {
        width: 100%;
        border: none;
    }

    .footer-summary td {
        border: none;
        padding: 5px;
        font-size: 8pt;
    }
</style>
</head>
    <body>
        <div class="content">
            <htmlpageheader name="docHeader">
                <!-- Header -->
                <table class="no-border" width="100%">
                    <tr>
                        <td width="33%">
                            <b>PT. MUGI {{ $brana }}</b><br>
                        </td>

                        <td width="34%" class="center">
                            <b>SALES REPORT/GROUP PRODUCT</b><br>
                            ---------------------------------------------------------------------<br>
                            PERIOD : {{ date('d-m-Y',strtotime($start)) }} - {{ date('d-m-Y',strtotime($end)) }}
                        </td>

                        <td width="10%">
                        <td width="8%" style="text-align:left">
                            <b>PAGE</b><br>
                            <b>CURR</b><br>
                            <b>PRINT DATE</b> <br>
                        </td>
                        <td width="1%">
                            <b>:</b><br>
                            <b>:</b><br>
                            <b>:</b><br>
                        </td>
                        <td width="15%">
                            <b>{PAGENO}</B><br>
                            <b>IDR</B><br>
                            <b>{{ date('d-m-Y') }} / {{ date('H:i:s') }}</B><br>
                        </td>
                    </tr>
                </table>
            </htmlpageheader>

            <sethtmlpageheader name="docHeader" value="on" show-this-page="1" />
            
            <!-- body -->
            <table>
                <thead>
                    <tr>
                        <th rowspan="2">GROUP PRODUCT</th>
                        <th rowspan="2">SALES#</th>
                        <th rowspan="2">NUMBER</th>
                        <th rowspan="2">DATE</th>
                        <th rowspan="2">CUSTOMER NAME</th>
                        <th rowspan="2">PRODUCT NAME</th>
                        <th rowspan="2">QTY</th>
                        <th rowspan="2" class="center">GROSS AMOUNT</th>
                        <th rowspan="2" class="center">OFFICIAL DISCOUNT</th>
                        <th rowspan="2" class="center">EXTRA BONUS</th>
                        <th colspan="2" class="center">TOTAL DISC + EB</th>
                        <th rowspan="2" class="center">NET AMOUNT</th>
                    </tr>

                    <tr>
                        <th class="center">AMOUNT</th>
                        <th class="center">%</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        // subtotal SSGRUP
                        $sub_qty = 0;
                        $sub_gross = 0;
                        $sub_odisa = 0;
                        $sub_edisa = 0;
                        $sub_totalDisc = 0;
                        $sub_total = 0;

                        // group per SSGRUP
                        $grouped = collect($items)
                        ->groupBy('msgrup_name')
                        ->map(function($item){
                            return $item->groupBy('mssgrup_name');
                        });

                        // grand total
                        $grand = collect();
                    @endphp

                    @foreach($grouped as $sgrup => $ssgroups)

                        <tr>
                            <td colspan="13" style="border-top:2px solid #000;">
                                <b>**  {{ strtoupper($sgrup) }}</b>
                            </td>
                        </tr>

                        @foreach($ssgroups as $ssgrup => $rows)

                            @foreach($rows as $i => $row)
                            <tr>
                                @if($i == 0)
                                    <td rowspan="{{ count($rows) }}" class="center">
                                        <b>{{ $ssgrup != '-' ? $ssgrup : '' }}</b>
                                    </td>
                                @endif

                                <td>{{ $row->sreno }}</td>
                                <td>{{ $row->nomor_oc }}</td>
                                <td>{{ date('d-m-y',strtotime($row->date)) }}</td>
                                <td>{{ $row->customer }}</td>
                                <td>{{ $row->product }}</td>
                                <td class="center">{{ $row->qty }}</td>
                                <td class="right">{{ number_format($row->gross,0,',','.') }}</td>
                                <td class="right">{{ number_format($row->disc,0,',','.') }}</td>
                                <td class="right">{{ number_format($row->edisa,0,',','.') }}</td>
                                <td class="right">{{ number_format($row->totalDisc,0,',','.') }}</td>
                                <td class="right">{{ $row->gross != 0 ? number_format(($row->totalDisc / $row->gross)*100,2) : 0 }}%</td>
                                <td class="right">{{ number_format($row->net,0,',','.') }}</td>
                            </tr>
                            @endforeach

                            @php
                                $total_qty = $rows->sum('qty');
                                $total_gross = $rows->sum('gross');
                                $total_disc = $rows->sum('disc');
                                $total_edisa = $rows->sum('edisa');
                                $total_totalDisc = $rows->sum('totalDisc');
                                $total_net = $rows->sum('net');
                            @endphp

                            <tr>
                                <td colspan="6">
                                    <b>* TOTAL {{ strtoupper($ssgrup != '-' ? $ssgrup : 'LAIN-LAIN') }}</b>
                                </td>
                                <td class="center"><b>{{ $total_qty }}</b></td>
                                <td class="right"><b>{{ number_format($total_gross,0,',','.') }}</b></td>
                                <td class="right"><b>{{ number_format($total_disc,0,',','.') }}</b></td>
                                <td class="right"><b>{{ number_format($total_edisa,0,',','.') }}</b></td>
                                <td class="right"><b>{{ number_format($total_totalDisc,0,',','.') }}</b></td>
                                <td class="right">
                                    <b>{{ $total_gross != 0 ? number_format(($total_totalDisc / $total_gross)*100,2) : 0 }}%</b>
                                </td>
                                <td class="right"><b>{{ number_format($total_net,0,',','.') }}</b></td>
                            </tr>

                        @endforeach

                        @php
                            $allRows = $ssgroups->flatten();
                            $grand = $grand->merge($ssgroups->flatten());
                        @endphp

                        <tr style="background:#eee;">
                            <td colspan="6">
                                <b>** TOTAL {{ strtoupper($sgrup) }}</b>
                            </td>
                            <td class="center"><b>{{ $allRows->sum('qty') }}</b></td>
                            <td class="right"><b>{{ number_format($allRows->sum('gross'),0,',','.') }}</b></td>
                            <td class="right"><b>{{ number_format($allRows->sum('disc'),0,',','.') }}</b></td>
                            <td class="right"><b>{{ number_format($allRows->sum('edisa'),0,',','.') }}</b></td>
                            <td class="right"><b>{{ number_format($allRows->sum('totalDisc'),0,',','.') }}</b></td>
                            <td class="right">
                                <b>{{ $allRows->sum('gross') != 0 ? number_format(($allRows->sum('totalDisc') / $allRows->sum('gross'))*100,2) : 0 }}%</b>
                            </td>
                            <td class="right"><b>{{ number_format($allRows->sum('net'),0,',','.') }}</b></td>
                        </tr>
                    @endforeach
                    
                    {{-- grand total --}}
                    @php
                        $gt_qty = $grand->sum('qty');
                        $gt_gross = $grand->sum('gross');
                        $gt_disc = $grand->sum('disc');
                        $gt_edisa = $grand->sum('edisa');
                        $gt_totalDisc = $grand->sum('totalDisc');
                        $gt_net = $grand->sum('net');
                    @endphp

                    <tr style="background:#ddd; border-top:3px solid #000;">
                        <td colspan="6">
                            <b>GRAND TOTAL</b>
                        </td>
                        <td class="center"><b>{{ $gt_qty }}</b></td>
                        <td class="right"><b>{{ number_format($gt_gross,0,',','.') }}</b></td>
                        <td class="right"><b>{{ number_format($gt_disc,0,',','.') }}</b></td>
                        <td class="right"><b>{{ number_format($gt_edisa,0,',','.') }}</b></td>
                        <td class="right"><b>{{ number_format($gt_totalDisc,0,',','.') }}</b></td>
                        <td class="right">
                            <b>{{ $gt_gross != 0 ? number_format(($gt_totalDisc / $gt_gross)*100,2) : 0 }}%</b>
                        </td>
                        <td class="right"><b>{{ number_format($gt_net,0,',','.') }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>