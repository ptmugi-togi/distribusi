<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>OUTSTANDING PAYMENT</title>

        <style>
            body {
                font-family: sans-serif;
                font-size: 7.5pt;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                border: 1px solid #000;
                padding: 3px;
                font-size: 7.5pt;
            }

            th {
                text-align: center;
                font-weight: bold;
            }

            .right {
                text-align: right;
            }

            .center {
                text-align: center;
            }

            .no-border td {
                border: none !important;
            }

            tr {
                page-break-inside: avoid;
            }

            .subtotal-row {
                font-weight: bold;
            }

            .grandtotal-row {
                font-weight: bold;
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
                        <b>OUTSTANDING PAYMENT</b><br>
                        ---------------------------------------------------------------------<br>
                        PERIOD : {{ $periode }}
                    </td>

                    <td width="15%">
                        <b>PRINT DATE</b><br>
                        <b>CURRENCY</b>
                    </td>

                    <td width="15%">
                        : {{ date('d-m-Y H:i:s') }}<br>
                        : IDR
                    </td>
                </tr>
            </table>
            <br>
        </htmlpageheader>

        <sethtmlpageheader name="docHeader" value="on" show-this-page="1" />

        @php
            $grand_inv_idr = 0;
            $grand_os_idr  = 0;
        @endphp

        <table>
            <thead>
                <tr>
                    <th width="14%">SUPPLIER</th>
                    <th width="9%">NO. INVOICE</th>
                    <th width="7%">TGL INV</th>
                    <th width="4%">CURR</th>
                    <th width="9%">AMOUNT INV</th>
                    <th width="7%">RATE INV</th>
                    <th width="10%">NILAI IDR INVOICE</th>
                    <th width="9%">VOUCHER PAY</th>
                    <th width="9%">PAYMENT</th>
                    <th width="7%">RATE PAY</th>
                    <th width="7%">OUTSTANDING AMOUNT</th>
                    <th width="8%">OUTSTANDING IDR</th>
                </tr>
            </thead>

            <tbody>
                @forelse($items->groupBy('supno') as $supno => $rows)
                    @php
                        $sup_inv_idr  = 0;
                        $sup_os_idr   = 0;
                        $seenInvoices = [];
                    @endphp

                    @foreach($rows as $row)
                        @php
                            $invKey      = trim($row->invno);
                            $isFirstInv  = !in_array($invKey, $seenInvoices);
                            if ($isFirstInv) {
                                $seenInvoices[] = $invKey;
                            }

                            $curcoInv    = trim($row->curco_inv ?? 'IDR');
                            $invRate     = (float) ($row->invrate ?? 1);
                            $invAmount   = (float) ($row->inv_amount ?? 0);
                            $nilaiIdrInv = ($curcoInv === 'IDR') ? $invAmount : ($invAmount * $invRate);

                            $hasPayment  = !empty(trim($row->pay_vcrno ?? ''));
                            $voucherStr  = $hasPayment
                                ? trim(($row->pay_formc ?? '') . ' ' . ($row->pay_vcrno ?? ''))
                                : '-';
                            $payVal      = (float) ($row->payval ?? 0);
                            $payRate     = (float) ($row->payrate ?? 0);

                            // Outstanding Amount dalam mata uang invoice
                            $osAmt = max(0, (float) ($row->inv_amount ?? 0) - (float) ($row->inv_paid ?? 0));

                            // Ambil payrate terakhir pada invoice ini jika dibayar lebih dari 1x di periode berjalan
                            $latestPayRateOnInv = (float) ($rows->where('invno', $row->invno)->where('payrate', '>', 0)->last()->payrate ?? 0);
                            $effectiveRate      = $latestPayRateOnInv > 0 ? $latestPayRateOnInv : $invRate;
                            $osIdr              = ($curcoInv === 'IDR') ? $osAmt : ($osAmt * $effectiveRate);

                            // Hitung dan jumlahkan Nilai IDR Invoice & Outstanding IDR per invoice
                            if ($isFirstInv) {
                                $sup_inv_idr   += $nilaiIdrInv;
                                $grand_inv_idr += $nilaiIdrInv;

                                $sup_os_idr    += $osIdr;
                                $grand_os_idr  += $osIdr;
                            }
                        @endphp

                        <tr>
                            {{-- Nama Supplier tampil pada baris pertama grup supplier --}}
                            @if($loop->first)
                                <td>{{ trim($row->supna) }}</td>
                            @else
                                <td></td>
                            @endif

                            {{-- Detail invoice tampil pada baris pertama invoice tersebut --}}
                            @if($isFirstInv)
                                <td>{{ $invKey }}</td>
                                <td class="center">{{ $row->invdt ? date('d-m-Y', strtotime($row->invdt)) : '-' }}</td>
                                <td class="center">{{ $curcoInv }}</td>
                                <td class="right">{{ number_format($invAmount, 2, ',', '.') }}</td>
                                <td class="right">{{ number_format($invRate, 2, ',', '.') }}</td>
                                <td class="right">{{ number_format($nilaiIdrInv, 2, ',', '.') }}</td>
                            @else
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            @endif

                            {{-- Detail Voucher Payment (formc + vcrno), payval, & rate pay --}}
                            <td class="center">{{ $voucherStr }}</td>
                            <td class="right">{{ $hasPayment ? number_format($payVal, 2, ',', '.') : '-' }}</td>
                            <td class="right">{{ $hasPayment ? number_format($payRate, 2, ',', '.') : '-' }}</td>

                            {{-- Outstanding Amount & Outstanding IDR per invoice --}}
                            @if($isFirstInv)
                                <td class="right">{{ number_format($osAmt, 2, ',', '.') }}</td>
                                <td class="right">{{ number_format($osIdr, 2, ',', '.') }}</td>
                            @else
                                <td></td>
                                <td></td>
                            @endif
                        </tr>
                    @endforeach

                    {{-- TOTAL PER SUPPLIER (kolom IDR) --}}
                    <tr class="subtotal-row">
                        <td colspan="6" class="right">* TOTAL BY {{ trim($rows->first()->supna) }} :</td>
                        <td class="right">{{ number_format($sup_inv_idr, 2, ',', '.') }}</td>
                        <td colspan="4"></td>
                        <td class="right">{{ number_format($sup_os_idr, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="center">Tidak ada data Outstanding Payment.</td>
                    </tr>
                @endforelse

                @if($items->count() > 0)
                    <tr class="grandtotal-row">
                        <td colspan="6" class="right">GRAND TOTAL :</td>
                        <td class="right">{{ number_format($grand_inv_idr, 2, ',', '.') }}</td>
                        <td colspan="4"></td>
                        <td class="right">{{ number_format($grand_os_idr, 2, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </body>
</html>