<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Report - Payment List</title>
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
                            <b>PAYMENT LIST BY CASH</b><br>
                            ---------------------------------------------------------------------<br>
                            DATE : {{ date('d-m-Y',strtotime($start)) }} S/D {{ date('d-m-Y',strtotime($end)) }}
                        </td>

                        <td width="10%">
                        <td width="8%" style="text-align:left">
                            <b>CURR</b><br>
                            <b>PRINT DATE</b> <br>
                            <b>PAGE</b><br>
                        </td>
                        <td width="1%">
                            <b>:</b><br>
                            <b>:</b><br>
                            <b>:</b><br>
                        </td>
                        <td width="15%">
                            <b>IDR</B><br>
                            <b>{{ date('d-m-Y') }} / {{ date('H:i:s') }}</B><br>
                            <b>{PAGENO}</B><br>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            DC = Daily Collection
                        </td>
                    </tr>
                </table>
            </htmlpageheader>

            <sethtmlpageheader name="docHeader" value="on" show-this-page="1" />
            
            <!-- body -->
            <table>
                <thead>
                    <tr>
                        <th>DC#</th>
                        <th>IOR#</th>
                        <th>DC Date</th>
                        <th>CCY</th>
                        <th>Invoice#</th>
                        <th>Due Date</th>
                        <th>Billing Amount</th>
                        <th>Payment Amount</th>
                        <th>PRATE</th>
                        <th width="15%">Total Amount</th>
                        <th width="20%">Customer</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($items as $dc => $rows)

                        @php
                            $header = $rows->first();
                            $total_dc = 0;
                            $total_blamt = 0;
                            $total_payva = 0;
                        @endphp

                        {{-- DETAIL --}}
                        @foreach($rows as $row)
                            @php
                                $row_total = $row->payva * $header->prate;

                                $total_blamt += $row->blamt;
                                $total_payva += $row->payva;
                                $total_dc += $row_total;
                            @endphp

                            <tr>
                                {{-- kalau baris pertama --}}
                                @if($loop->first)
                                    <td><b>{{ $header->vcrno }}</b></td>
                                    <td class="center">{{ $header->iorno }}</td>
                                    <td class="center">{{ date('d-m-Y', strtotime($header->pdate)) }}</td>
                                    <td class="center">{{ $header->curco }}</td>
                                @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @endif

                                {{-- detail invoice --}}
                                <td class="center">{{ $row->invrn }}</td>
                                <td class="center">{{ isset($row->pdate) ? date('d-m-Y', strtotime($row->pdate)) : '-' }}</td>

                                <td class="right">{{ number_format($row->blamt,0,',','.') }}</td>
                                <td class="right">{{ number_format($row->payva,0,',','.') }}</td>
                                <td class="right">{{ number_format($header->prate,2) }}</td>
                                <td class="right">{{ number_format($row_total,0,',','.') }}</td>
                                <td>{{ $row->cusna }}</td>
                            </tr>
                        @endforeach

                        {{-- TOTAL PER DC --}}
                        <tr>
                            <td colspan="6" class="right"><b>TOTAL :</b></td>
                            <td class="right"><b>{{ number_format($total_blamt,0,',','.') }}</b></td>
                            <td class="right"><b>{{ number_format($total_payva,0,',','.') }}</b></td>
                            <td></td>
                            <td class="right"><b>{{ number_format($total_dc,0,',','.') }}</b></td>
                            <td></td>
                        </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>