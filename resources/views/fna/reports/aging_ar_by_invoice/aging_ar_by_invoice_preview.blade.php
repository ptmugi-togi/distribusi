<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>AGING A/R BY INVOICE</title>

        <style>
            body{
                font-family: sans-serif;
                font-size: 8pt;
            }

            table{
                width: 100%;
                border-collapse: collapse;
            }

            th, td{
                border: 1px solid #000;
                padding: 3px;
                font-size: 8pt;
            }

            th{
                text-align: center;
                font-weight: bold;
            }

            .right{
                text-align: right;
            }

            .center{
                text-align: center;
            }

            .no-border td{
                border: none !important;
            }

            tr{
                page-break-inside: avoid;
            }
        </style>
    </head>

    <body>
        <htmlpageheader name="docHeader">
            <table class="no-border">
                <tr>
                    <td width="35%">
                        <b>PT. MUGI, {{ $brana }}</b>
                    </td>

                    <td width="35%" class="center">
                        <b>AGING A/R BY INVOICE</b><br>
                        ---------------------------------------------------------------------<br>
                        AS PER : {{ date('d-m-Y', strtotime($asper)) }}
                    </td>

                    <td width="15%">
                        <b>PRINT DATE</b><br>
                        <b>PAGE</b><br>
                        <b>CURRENCY</b>
                    </td>

                    <td width="15%">
                        : {{ date('d-m-Y H:i:s') }}<br>
                        : {PAGENO} <br>
                        : IDR
                    </td>
                </tr>
            </table>

            <br>
        </htmlpageheader>

        <sethtmlpageheader name="docHeader" value="on" show-this-page="1" />

        @php
            $grand_total = 0;

            $notdue = 0;
            $d1_30 = 0;
            $d31_60 = 0;
            $d61_90 = 0;
            $d91_180 = 0;
            $d181_360 = 0;
            $over360 = 0;
        @endphp

        <table>

            <thead>
                <tr>
                    <th width="6%">CUST#</th>
                    <th width="15%">CUSTOMER NAME</th>
                    <th width="7%">INVOICE</th>
                    <th width="7%">INV DATE</th>
                    <th width="7%">DUE DATE</th>
                    <th width="5%">SALES#</th>
                    <th width="9%">AMOUNT</th>
                    <th width="8%">NOT DUE</th>
                    <th width="8%">1-30 DAYS</th>
                    <th width="8%">31-60 DAYS</th>
                    <th width="8%">61-90 DAYS</th>
                    <th width="8%">91-180 DAYS</th>
                    <th width="8%">181-360 DAYS</th>
                    <th width="7%">>360 DAYS</th>
                    <th width="4%">OVER</th>
                    <th width="8%">REASON OF PAYMENT DELAY</th>
                </tr>
            </thead>

            <tbody>

            @foreach($items->groupBy('cusno') as $custno => $rows)

                @php
                    $cust_total = 0;
                @endphp

                @foreach($rows as $row)
                    @php
                        $amt = $row->osamt;
                        $days = $row->overdays;

                        $cust_total += $amt;
                        $grand_total += $amt;

                        $b_notdue = 0;
                        $b1_30 = 0;
                        $b31_60 = 0;
                        $b61_90 = 0;
                        $b91_180 = 0;
                        $b181_360 = 0;
                        $b360 = 0;

                        if ($days < 0) {
                            $notdue += $amt;
                            $b_notdue = $amt;
                        } elseif ($days <= 30) {
                            $d1_30 += $amt;
                            $b1_30 = $amt;
                        } elseif ($days <= 60) {
                            $d31_60 += $amt;
                            $b31_60 = $amt;
                        } elseif ($days <= 90) {
                            $d61_90 += $amt;
                            $b61_90 = $amt;
                        } elseif ($days <= 180) {
                            $d91_180 += $amt;
                            $b91_180 = $amt;
                        } elseif ($days <= 360) {
                            $d181_360 += $amt;
                            $b181_360 = $amt;
                        } else {
                            $over360 += $amt;
                            $b360 = $amt;
                        }
                    @endphp
                    <tr>
                        {{-- customer hanya tampil sekali --}}
                        @if($loop->first)
                            <td>
                                {{ $row->cusno }}
                            </td>
                            <td>
                                {{ $row->cusna }}
                            </td>
                        @else
                            <td></td>
                            <td></td>
                        @endif

                        <td>{{ $row->formc }} {{ $row->invno }}</td>
                        <td class="center">{{ date('d-m-Y', strtotime($row->invdt)) }}</td>
                        <td class="center">{{ date('d-m-Y', strtotime($row->duedt)) }}</td>
                        <td class="center">{{ $row->sreno }}</td>
                        <td class="right">{{ number_format($amt,0,',','.') }}</td>
                        <td class="right">{{ $b_notdue ? number_format($b_notdue,0,',','.') : '0' }}</td>
                        <td class="right">{{ $b1_30 ? number_format($b1_30,0,',','.') : '0' }}</td>
                        <td class="right">{{ $b31_60 ? number_format($b31_60,0,',','.') : '0' }}</td>
                        <td class="right">{{ $b61_90 ? number_format($b61_90,0,',','.') : '0' }}</td>
                        <td class="right">{{ $b91_180 ? number_format($b91_180,0,',','.') : '0' }}</td>
                        <td class="right">{{ $b181_360 ? number_format($b181_360,0,',','.') : '0' }}</td>
                        <td class="right">{{ $b360 ? number_format($b360,0,',','.') : '0' }}</td>
                        <td class="right">{{ $days }}</td>
                        <td>{{ $row->reason ?? '' }}</td>
                    </tr>
                @endforeach

                {{-- TOTAL PER CUSTOMER --}}
                <tr>
                    <td colspan="6" class="right"><b>* TOTAL BY {{ $rows->first()->cusna }} :</b></td>
                    <td class="right"><b>{{ number_format($cust_total,0,',','.') }}</b></td>
                    <td colspan="9"></td>
                </tr>

            @endforeach

                {{-- GRAND TOTAL --}}
                <tr>
                    <td colspan="6" class="right"><b>SUBTOTAL :</b></td>
                    <td class="right"><b>{{ number_format($grand_total,0,',','.') }}</b></td>
                    <td class="right"><b>{{ number_format($notdue,0,',','.') }}</b></td>
                    <td class="right"><b>{{ number_format($d1_30,0,',','.') }}</b></td>
                    <td class="right"><b>{{ number_format($d31_60,0,',','.') }}</b></td>
                    <td class="right"><b>{{ number_format($d61_90,0,',','.') }}</b></td>
                    <td class="right"><b>{{ number_format($d91_180,0,',','.') }}</b></td>
                    <td class="right"><b>{{ number_format($d181_360,0,',','.') }}</b></td>
                    <td class="right"><b>{{ number_format($over360,0,',','.') }}</b></td>
                    <td></td>
                    <td></td>
                </tr>

                {{-- RATIO --}}
                <tr>
                    <td colspan="6" class="right"><b>RATIO :</b></td>
                    <td class="right"><b>100.00%</b></td>
                    <td class="right"><b>{{ $grand_total ? number_format(($notdue / $grand_total) * 100,2) : '0.00' }}%</b></td>
                    <td class="right"><b>{{ $grand_total ? number_format(($d1_30 / $grand_total) * 100,2) : '0.00' }}%</b></td>
                    <td class="right"><b>{{ $grand_total ? number_format(($d31_60 / $grand_total) * 100,2) : '0.00' }}%</b></td>
                    <td class="right"><b>{{ $grand_total ? number_format(($d61_90 / $grand_total) * 100,2) : '0.00' }}%</b></td>
                    <td class="right"><b>{{ $grand_total ? number_format(($d91_180 / $grand_total) * 100,2) : '0.00' }}%</b></td>
                    <td class="right"><b>{{ $grand_total ? number_format(($d181_360 / $grand_total) * 100,2) : '0.00' }}%</b></td>
                    <td class="right"><b>{{ $grand_total ? number_format(($over360 / $grand_total) * 100,2) : '0.00' }}%</b></td>
                    <td></td>
                    <td></td>
                </tr>

            </tbody>

        </table>

    </body>
</html>