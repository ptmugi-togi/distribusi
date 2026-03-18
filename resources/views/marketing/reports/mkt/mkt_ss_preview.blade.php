<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Report-MKT-OC Per Sales Rep</title>
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
                    @endphp

                    @foreach($grouped as $sgrup => $ssgroups)

                        <tr>
                            <td colspan="13" style="border-top:2px solid #000;">
                                <b>* {{ strtoupper($sgrup) }}</b>
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

                        @endforeach

                    @endforeach

                    @php
                        $allRows = $ssgroups->flatten();
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
                </tbody>
            </table>
        </div>
    </body>
</html>