<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <title>Report - Buku Penjualan</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 6pt;
            margin-bottom: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            font-size: inherit;
        }

        th {
            text-align: center;
            vertical-align: middle;
        }

        td {
            vertical-align: middle;
        }

        tr {
            page-break-inside: avoid;
        }

        .no-border td,
        .no-border th {
            border: none !important;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

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

            <table class="no-border" width="100%">
                <tr>
                    <td width="33%">
                        <b>PT. MUGI {{ $brana }}</b>
                        <br>
                    </td>

                    <td width="34%" class="center">
                        <b>BUKU PENJUALAN</b>
                        <br>
                        ---------------------------------------------------------------------
                        <br>
                        DARI :
                        {{ date('d-m-Y', strtotime($start)) }}
                        S/D
                        {{ date('d-m-Y', strtotime($end)) }}
                    </td>

                    <td width="18%">
                    </td>

                    <td width="8%" class="right">
                        <b>TANGGAL</b><br>
                        <b>JAM</b><br>
                        <b>HAL</b><br>
                    </td>

                    <td width="1%">
                        <b>:</b><br>
                        <b>:</b><br>
                        <b>:</b><br>
                    </td>

                    <td width="7%" class="right">
                        <b>{{ date('d-m-Y') }}</b><br>
                        <b>{{ date('H:i:s') }}</b><br>
                        <b>{PAGENO}</b><br>
                    </td>
                </tr>
            </table>
        </htmlpageheader>

        <sethtmlpageheader name="docHeader" value="on" show-this-page="1" />

        @php
            $groupedItems = $items->groupBy(function ($row) {
                return $row->formc . '|' . $row->invno;
            });

            $grandGross = 0;
            $grandDisc  = 0;
            $grandUangMuka = 0;
            $grandDpp = 0;
            $grandPpn = 0;
            $grandPiutang = 0;
            $grandInstalasi = 0;
            $grandUangMukaSA = 0;
            $grandUangMukaSB = 0;
        @endphp

        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>DATE</th>
                    <th>FAKTUR</th>
                    <th>FAKTUR PAJAK</th>
                    <th>NAMA CUSTOMER</th>
                    <th>NO REF.</th>
                    <th>GROSS SALES</th>
                    <th>DISCOUNT</th>
                    <th>UANG MUKA</th>
                    <th>DPP</th>
                    <th>PPN</th>
                    <th>PIUTANG</th>
                    <th>INSTALASI</th>
                    <th>UANG MUKA SA</th>
                    <th>UANG MUKA SB</th>
                    <th width="3%">GRP</th>
                </tr>
            </thead>

            @php
                $groupedItems = $items->groupBy(function ($row) {
                    return $row->formc . '|' . $row->invno;
                });

                $no = 1;
            @endphp

            <tbody>
                @foreach(['SC', 'SD'] as $formc)
                    @php
                        $formItems = $groupedItems->filter(function ($rows) use ($formc) {
                            return $rows->first()->formc === $formc;
                        });

                        $subtotal = [
                            'gross'      => 0,
                            'discount'   => 0,
                            'uangMuka'   => 0,
                            'dpp'        => 0,
                            'ppn'        => 0,
                            'piutang'    => 0,
                            'instalasi'  => 0,
                            'uangMukaSA' => 0,
                            'uangMukaSB' => 0,
                        ];
                    @endphp


                    @foreach($formItems as $invoice => $rows)
                        @php
                            $header = $rows->first();

                            $grossSales = $rows->sum('gramt');
                            $discount   = $header->odisa == 0 ? null : $header->odisa;
                            $uangMuka   = $header->dpamt == 0 ? null : $header->dpamt;
                            $dpp        = $header->dpamt == 0 ? null : $header->dpamt;
                            $ppn        = $header->txamt;
                            $piutang    = $uangMuka + $ppn;
                            $instalasi  = $header->instf;
                            $uangMukaSA = ($header->sorfc === 'SA' && $header->invtp == 1) ? $header->header_gramt : 0;
                            $uangMukaSB = ($header->sorfc === 'SB') ? $header->header_gramt : 0;

                            // subtotal
                            $subtotal['gross']      += $grossSales;
                            $subtotal['discount']   += $discount;
                            $subtotal['uangMuka']   += $uangMuka;
                            $subtotal['dpp']        += $dpp;
                            $subtotal['ppn']        += $ppn;
                            $subtotal['piutang']    += $piutang;
                            $subtotal['instalasi']  += $instalasi;
                            $subtotal['uangMukaSA'] += $uangMukaSA;
                            $subtotal['uangMukaSB'] += $uangMukaSB;
                        @endphp

                        <tr>
                            <td class="center">
                                {{ $no++ }}
                            </td>
                            <td class="center">
                                {{ $header->invdt ? date('d-m-Y', strtotime($header->invdt)) : '' }}
                            </td>
                            <td class="center">
                                {{ $header->formc }} {{ $header->invno }}
                            </td>
                            <td class="center">
                                {{ $header->fpnum ?? '' }}
                            </td>
                            <td>
                                {{ $header->cusna ?? '' }}
                            </td>
                            <td class="center">
                                @if($header->formc === 'SD')
                                    {{ ($header->dorfc ?? '') . ($header->donom ?? '') }}
                                @else
                                    {{ ($header->sorfc ?? '') . ($header->sorno ?? '') }}
                                @endif
                            </td>
                            <td class="right">
                                {{ $grossSales ? number_format($grossSales, 0, ',', '.') : '' }}
                            </td>
                            <td class="right">
                                {{ $discount ? number_format($discount, 0, ',', '.') : '' }}
                            </td>
                            <td class="right">
                                {{ $uangMuka ? number_format($uangMuka, 0, ',', '.') : '' }}
                            </td>
                            <td class="right">
                                {{ $dpp ? number_format($dpp, 0, ',', '.') : '' }}
                            </td>
                            <td class="right">
                                {{ $ppn ? number_format($ppn, 0, ',', '.') : '' }}
                            </td>
                            <td class="right">
                                {{ $piutang ? number_format($piutang, 0, ',', '.') : '' }}
                            </td>
                            <td class="right">
                                {{ $instalasi ? number_format($instalasi, 0, ',', '.') : '' }}
                            </td>
                            <td class="right">
                                @if ($header->sorfc === 'SA' && $header->invtp === 1)
                                    {{ $uangMukaSA ? number_format($uangMukaSA, 0, ',', '.') : '' }}
                                @endif
                            </td>
                            <td class="right">
                                @if ($header->sorfc === 'SB')
                                    {{ $uangMukaSB ? number_format($uangMukaSB, 0, ',', '.') : '' }}
                                @endif
                            </td>
                            <td class="center">
                                {{ $header->group ?? '' }}
                            </td>
                        </tr>
                    @endforeach

                    {{-- SUB TOTAL SC / SD --}}
                    @if($formItems->count() > 0)
                        @php
                            $grandGross += $subtotal['gross'];
                            $grandDisc  += $subtotal['discount'];
                            $grandUangMuka  += $subtotal['uangMuka'];
                            $grandDpp   += $subtotal['dpp'];
                            $grandPpn   += $subtotal['ppn'];
                            $grandPiutang   += $subtotal['piutang'];
                            $grandInstalasi += $subtotal['instalasi'];
                            $grandUangMukaSA += $subtotal['uangMukaSA'];
                            $grandUangMukaSB += $subtotal['uangMukaSB'];
                        @endphp

                        <tr>
                            <td colspan="6" class="right">
                                <b>SUB TOTAL {{ $formc }}</b>
                            </td>
                            <td class="right">
                                <b>
                                    {{ $subtotal['gross'] ? number_format($subtotal['gross'], 0, ',', '.') : '' }}
                                </b>
                            </td>
                            <td class="right">
                                <b>
                                    {{ $subtotal['discount'] ? number_format($subtotal['discount'], 0, ',', '.') : '' }}
                                </b>
                            </td>
                            <td class="right">
                                <b>
                                    {{ $subtotal['uangMuka'] ? number_format($subtotal['uangMuka'], 0, ',', '.') : '' }}
                                </b>
                            </td>
                            <td class="right">
                                <b>
                                    {{ $subtotal['dpp'] ? number_format($subtotal['dpp'], 0, ',', '.') : '' }}
                                </b>
                            </td>
                            <td class="right">
                                <b>
                                    {{ $subtotal['ppn'] ? number_format($subtotal['ppn'], 0, ',', '.') : '' }}
                                </b>
                            </td>
                            <td class="right">
                                <b>
                                    {{ $subtotal['piutang'] ? number_format($subtotal['piutang'], 0, ',', '.') : '' }}
                                </b>
                            </td>
                            <td class="right">
                                <b>
                                    {{ $subtotal['instalasi'] ? number_format($subtotal['instalasi'], 0, ',', '.') : '' }}
                                </b>
                            </td>
                            <td class="right">
                                <b>
                                    {{ $subtotal['uangMukaSA'] ? number_format($subtotal['uangMukaSA'], 0, ',', '.') : '' }}
                                </b>
                            </td>
                            <td class="right">
                                <b>
                                    {{ $subtotal['uangMukaSB'] ? number_format($subtotal['uangMukaSB'], 0, ',', '.') : '' }}
                                </b>
                            </td>
                            <td></td>
                        </tr>
                    @endif
                @endforeach
            </tbody>

            {{-- GRAND TOTAL --}}
            <tfoot>
                <tr>
                    <td colspan="6" class="right">
                        <b>GRAND TOTAL</b>
                    </td>
                    <td class="right">
                        <b>
                            {{ number_format($grandGross, 0, ',', '.') }}
                        </b>
                    </td>
                    <td class="right">
                        <b>
                            {{ number_format($grandDisc, 0, ',', '.') }}
                        </b>
                    </td>
                    <td class="right">
                        <b>
                            {{ number_format($grandUangMuka, 0, ',', '.') }}
                        </b>
                    </td>
                    <td class="right">
                        <b>
                            {{ number_format($grandDpp, 0, ',', '.') }}
                        </b>
                    </td>
                    <td class="right">
                        <b>
                            {{ number_format($grandPpn, 0, ',', '.') }}
                        </b>
                    </td>
                    <td class="right">
                        <b>
                            {{ number_format($grandPiutang, 0, ',', '.') }}
                        </b>
                    </td>
                    <td class="right">
                        <b>
                            {{ number_format($grandInstalasi, 0, ',', '.') }}
                        </b>
                    </td>
                    <td class="right">
                        <b>
                            {{ number_format($grandUangMukaSA, 0, ',', '.') }}
                        </b>
                    </td>
                    <td class="right">
                        <b>
                            {{ number_format($grandUangMukaSB, 0, ',', '.') }}
                        </b>
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>