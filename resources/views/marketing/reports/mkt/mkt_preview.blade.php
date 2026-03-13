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
                            <b>PT. MUGI JAKARTA-1</b><br>
                        </td>

                        <td width="34%" class="center">
                            <b>SALES ORDER CLOSED BY SALES REP.</b><br>
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
                        <th rowspan="2">SALES#</th>
                        <th rowspan="2">NUMBER</th>
                        <th rowspan="2">DATE</th>
                        <th rowspan="2">CUSTOMER NAME</th>
                        <th rowspan="2">PRODUCT NAME</th>
                        <th rowspan="2">QTY</th>
                        <th rowspan="2" class="right">GROSS AMOUNT</th>
                        <th rowspan="2" class="right">OFFICIAL DISCOUNT</th>
                        <th rowspan="2" class="right">EXTRA BONUS</th>
                        <th colspan="2" class="center">TOTAL DISC + BONUS</th>
                        <th rowspan="2" class="right">NET AMOUNT</th>
                    </tr>

                    <tr>
                        <th class="right">AMOUNT</th>
                        <th class="right">%</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $current = '';
                        $gross = 0;
                        $odisa = 0;
                        $edisa = 0;
                        $totalDisc = 0;
                        $discp = 0;
                        $total = 0;
                    @endphp

                    @foreach($items as $row)

                        @if($current != $row->sreno && $current != null)
                            <tr>
                                <td colspan="6"><b>TOTAL BY SALES REP [{{ $current }}]</b></td>
                                <td class="right"><b>{{ number_format($gross,0,',','.') }}</b></td>
                                <td class="right"><b>{{ number_format($odisa,0,',','.') }}</b></td>
                                <td class="right"><b>{{ number_format($edisa,0,',','.') }}</b></td>
                                <td class="right"><b>{{ number_format($totalDisc,0,',','.') }}</b></td>
                                <td class="right"><b>{{ number_format($discp,0,',','.') }}</b></td>
                                <td class="right"><b>{{ number_format($total,0,',','.') }}</b></td>
                            </tr>
                            @php
                                $gross = 0;
                                $odisa = 0;
                                $edisa = 0;
                                $totalDisc = 0;
                                $discp = 0;
                                $total = 0;
                            @endphp
                        @endif

                        <tr>
                            <td>{{ $row->sreno }}</td>
                            <td>{{ $row->formc }} {{ $row->number }}</td>
                            <td>{{ date('d-m-y',strtotime($row->date)) }}</td>
                            <td>{{ $row->customer }}</td>
                            <td>{{ $row->product }}</td>
                            <td class="center">{{ $row->qty }}</td>
                            <td class="right">{{ number_format($row->gross,0,',','.') }}</td>
                            <td class="right">{{ number_format($row->disc,0,',','.') }}</td>
                            <td class="right">{{ number_format($row->edisa,0,',','.') }}</td>
                            <td class="right">{{ number_format($row->totalDisc,0,',','.') }}</td>
                            <td class="right">{{ number_format($row->discp,2) }}%</td>
                            <td class="right">{{ number_format($row->net,0,',','.') }}</td>
                        </tr>

                        @php
                            $current = $row->sreno;
                            $gross += $row->gross;
                            $odisa += $row->disc;
                            $edisa += $row->edisa;
                            $totalDisc += $row->totalDisc;
                            $discp += $row->discp;
                            $total += $row->net;
                        @endphp
                    @endforeach

                    <tr>
                        <td colspan="6"><b>TOTAL BY SALES REP [{{ $current }}]</b></td>
                        <td class="right"><b>{{ number_format($gross,0,',','.') }}</b></td>
                        <td class="right"><b>{{ number_format($odisa,0,',','.') }}</b></td>
                        <td class="right"><b>{{ number_format($edisa,0,',','.') }}</b></td>
                        <td class="right"><b>{{ number_format($totalDisc,0,',','.') }}</b></td>
                        <td class="right"><b>{{ number_format($discp,0,',','.') }}</b></td>
                        <td class="right"><b>{{ number_format($total,0,',','.') }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>