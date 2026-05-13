<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Customer Historical Transaction</title>

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
                        <b>PT. MUGI, {{ $brana ?? '' }}</b><br>
                        <br>
                        CUSTOMER :
                        [{{ $customer->cusno ?? ($items->first()->cusno ?? '-') }}]
                        {{ $customer->cusna ?? ($items->first()->cusna ?? '-') }}
                    </td>

                    <td width="35%" class="center">
                        <b>CUSTOMER HISTORICAL TRANSACTIONS</b><br>
                        ---------------------------------------------------------------------<br>
                    </td>

                    <td width="17%"></td>

                    <td width="4%">
                        <b>TGL</b><br>
                        <b>JAM</b><br>
                        <b>HAL</b><br>
                    </td>

                    <td width="8%">
                        : {{ date('d-m-Y') }}<br>
                        : {{ date('H:i:s') }}<br>
                        : {PAGENO}<br>
                    </td>
                </tr>
            </table>

            <br>
        </htmlpageheader>

        <sethtmlpageheader name="docHeader" value="on" show-this-page="1" />

        @php
            $groups = $items->groupBy(function ($row) {
                return $row->cusno . '|' . $row->formc . '|' . $row->invno;
            });

            $no = 1;

            $grandInvoice = 0;
            $grandPayment = 0;
            $grandCreditNote = 0;
            $grandWriteoff = 0;
            $grandBalance = 0;
        @endphp

        <table>
            <thead>
                <tr>
                    <th rowspan="2" width="3%">NO.</th>
                    <th colspan="4">INVOICE</th>
                    <th colspan="4">PAYMENT</th>
                    <th colspan="2">CREDIT NOTE</th>
                    <th colspan="3">WRITE OFF</th>
                    <th colspan="1">ENDING BALANCE</th>
                </tr>
                <tr>
                    <th width="10%">NUMBER</th>
                    <th width="8%">DATE</th>
                    <th width="4%">CUR</th>
                    <th width="9%">TOTAL</th>

                    <th width="10%">I O R / D C R</th>
                    <th width="8%">DATE</th>
                    <th width="4%">HARI</th>
                    <th width="9%">RUPIAH</th>

                    <th width="8%">DATE</th>
                    <th width="9%">RUPIAH</th>
                    
                    <th width="8%">WO#</th>
                    <th width="8%">DATE</th>
                    <th width="9%">RUPIAH</th>

                    <th width="11%">RUPIAH</th>
                </tr>
            </thead>

            <tbody>
                @forelse($groups as $invoiceKey => $rows)
                    @php
                        $first = $rows->first();
                        $rowspan = $rows->count();

                        $invoiceRp = $first->invoice_rp ?? 0;
                        $balanceRp = $first->balance_rp ?? 0;

                        $grandInvoice += $invoiceRp;
                        $grandBalance += $balanceRp;
                    @endphp

                    @foreach($rows as $idx => $row)
                        @php
                            $paymentRp = $row->payment_rp ?? 0;
                            $creditnoteRp = $row->creditnote_rp ?? 0;
                            $writeoffRp = $row->writeoff_rp ?? 0;

                            $grandPayment += $paymentRp;
                            $grandCreditNote += $creditnoteRp;
                            if($idx == 0){
                                $grandWriteoff += $writeoffRp;
                            }
                        @endphp

                        <tr>
                            <td class="center">{{ $no++ }}</td>

                            @if($idx == 0)
                                <td class="center" rowspan="{{ $rowspan }}" style="vertical-align: middle;">
                                    {{ $first->formc }} {{ $first->invno }}
                                </td>
                                <td class="center" rowspan="{{ $rowspan }}" style="vertical-align: middle;">
                                    {{ $first->invdt ? date('d-m-Y', strtotime($first->invdt)) : '' }}
                                </td>
                                <td class="center" rowspan="{{ $rowspan }}" style="vertical-align: middle;">
                                    {{ $first->curco }}
                                </td>
                                <td class="right" rowspan="{{ $rowspan }}" style="vertical-align: middle;">
                                    {{ number_format($invoiceRp, 0, ',', '.') }}
                                </td>
                            @endif

                            <td class="center">{{ $row->vcrno ?? '' }}</td>
                            <td class="center">{{ !empty($row->payment_date) ? date('d-m-Y', strtotime($row->payment_date)) : '' }}</td>
                            <td class="center">{{ $row->hari ?? '' }}</td>
                            <td class="right">{{ $paymentRp != 0 ? number_format($paymentRp, 0, ',', '.') : '' }}</td>

                            <td class="center"></td>
                            <td class="right">{{ $creditnoteRp != 0 ? number_format($creditnoteRp, 0, ',', '.') : '' }}</td>

                            @if($idx == 0)
                                <td class="center">
                                    {{ $row->wo }} {{ $row->wono ?? '' }}
                                </td>
                                <td class="center">
                                    {{ $writeoffRp != 0 && !empty($row->wodat) ? date('d-m-Y', strtotime($row->wodat)) : '' }}
                                </td>
                                <td class="right">
                                    {{ $writeoffRp != 0 ? number_format($writeoffRp, 0, ',', '.') : '' }}
                                </td>
                            @else
                                <td></td>
                                <td></td>
                                <td></td>
                            @endif

                            @if($idx == 0)
                                <td class="right" rowspan="{{ $rowspan }}" style="vertical-align: middle;">
                                    {{ number_format($balanceRp, 0, ',', '.') }}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="14" class="center">Data tidak ditemukan</td>
                    </tr>
            @endforelse
            <tr class="grand-total">
                <td colspan="4" class="right">GRAND TOTAL :</td>
                <td class="right">{{ number_format($grandInvoice, 0, ',', '.') }}</td>

                <td colspan="3"></td>
                <td class="right">{{ number_format($grandPayment, 0, ',', '.') }}</td>

                <td></td>
                <td class="right">{{ number_format($grandCreditNote, 0, ',', '.') }}</td>

                <td></td>
                <td></td>
                <td class="right">{{ number_format($grandWriteoff, 0, ',', '.') }}</td>

                <td class="right">{{ number_format($grandBalance, 0, ',', '.') }}</td>
            </tr>
        </table>
    </body>
</html>