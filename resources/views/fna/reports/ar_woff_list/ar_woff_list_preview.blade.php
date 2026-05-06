<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Report - AR Write Off List</title>
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
                            <b>PT. MUGI, {{ $brana }}</b><br>
                        </td>

                        <td width="32%" class="center">
                            <b>LIST OF WRITE-OFF ACCOUNT RECEIVABLE</b><br>
                            ---------------------------------------------------------------------<br>
                            From : {{ date('d-m-Y',strtotime($start)) }} to {{ date('d-m-Y',strtotime($end)) }}
                        </td>

                        <td width="5%"></td>
                        <td width="10%" style="text-align:left">
                            <b>CURR</b><br>
                            <b>PRINT DATE</b> <br>
                            <b>PAGE</b><br>
                        </td>
                        <td width="1%">
                            <b>:</b><br>
                            <b>:</b><br>
                            <b>:</b><br>
                        </td>
                        <td width="20%">
                            <b>IDR</B><br>
                            <b>{{ date('d-m-Y') }} / {{ date('H:i:s') }}</B><br>
                            <b>{PAGENO}</B><br>
                        </td>
                    </tr>
                </table>
            </htmlpageheader>

            <sethtmlpageheader name="docHeader" value="on" show-this-page="1" />
            
            <!-- body -->
            <table>
                <thead>
                    <tr>
                        <th width="15%">VOUCHER NO.</th>
                        <th>DATE</th>
                        <th>INVOICE NO.</th>
                        <th width="40%">CUSTOMER</th>
                        <th width="20%">VALUE</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $grand_total = 0;
                    @endphp

                    @foreach($items as $dc => $rows)
                        @php
                            $header = $rows->first();
                            $group_total = 0;
                        @endphp

                        {{-- DETAIL --}}
                        @foreach($rows as $row)
                            @php
                                $group_total += $row->trval;
                            @endphp
                            <tr>
                                {{-- baris pertama voucher --}}
                                @if($loop->first)
                                    <td><b>{{ $header->formc }} {{ $header->vcrno }}</b></td>
                                    <td class="center">{{ date('d-m-Y', strtotime($header->tradt)) }}</td>
                                @else
                                    <td></td>
                                    <td></td>
                                @endif

                                {{-- detail invoice --}}
                                <td class="center">{{ $row->invfc }} {{ $row->invrn }}</td>
                                <td>{{ $row->cusna }}</td>
                                <td class="right">{{ number_format($row->trval,0,',','.') }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="4" class="right"><b>Subtotal :</b></td>
                            <td class="right"><b>{{ number_format($group_total,0,',','.') }}</b></td>
                        </tr>

                        @php
                            $grand_total += $group_total;
                        @endphp
                    @endforeach

                    {{-- GRAND TOTAL --}}
                    <tr>
                        <td colspan="4" class="right"><b>TOTAL :</b></td>
                        <td class="right"><b>{{ number_format($grand_total,0,',','.') }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>