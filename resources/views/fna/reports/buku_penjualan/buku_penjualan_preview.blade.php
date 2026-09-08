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

            th, td {
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

            .no-border td, .no-border th {
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

            .group-summary {
                margin-top: 20px;
                page-break-inside: avoid;
            }

            .group-summary table {
                width: 100%;
                border-collapse: collapse;
            }

            .group-summary th, .group-summary td {
                border: 1px solid #000;
                padding: 4px;
                font-size: 7pt;
            }

            .group-summary th {
                text-align: center;
                vertical-align: middle;
            }

            .group-summary td {
                vertical-align: middle;
            }

            .tax-summary {
                margin-top: 20px;
                page-break-inside: avoid;
            }

            .tax-summary table {
                width: 65%;
                border-collapse: collapse;
            }

            .tax-summary th, .tax-summary td {
                border: 1px solid #000;
                padding: 4px;
                font-size: 7pt;
            }

            .tax-summary th {
                text-align: center;
                vertical-align: middle;
            }

            .tax-summary td {
                vertical-align: middle;
            }

            .journal-summary {
                margin-top: 20px;
                page-break-inside: avoid;
            }

            .journal-summary table {
                width: 65%;
                border-collapse: collapse;
            }

            .journal-summary th, .journal-summary td {
                border: 1px solid #000;
                padding: 4px;
                font-size: 7pt;
            }

            .journal-summary th {
                text-align: center;
                vertical-align: middle;
            }

            .journal-summary td {
                vertical-align: middle;
            }

            .signature-container {
                margin-top: 20px;
                width: 100%;
                page-break-inside: avoid;
            }

            .signature-date {
                font-size: 7pt;
                font-weight: bold;
                margin-bottom: 2px;
            }

            .signature-table {
                width: 65%;
                border-collapse: collapse;
            }

            .signature-table th, .signature-table td {
                border: 1px solid #000;
                text-align: center;
                padding: 4px;
            }

            .signature-table th {
                font-size: 7pt;
                font-weight: bold;
                height: 15px;
            }

            .signature-table td {
                height: 80px;
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
                            DARI : {{ date('d-m-Y', strtotime($start)) }} S/D {{ date('d-m-Y', strtotime($end)) }}
                        </td>

                        <td width="18%"></td>

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
                $no = 1;
            @endphp

            {{-- TABEL UTAMA BUKU PENJUALAN --}}
            <table>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>DATE</th>
                        <th>FAKTUR</th>
                        <th width="9%">FAKTUR PAJAK</th>
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
                        <th>GRP</th>
                    </tr>
                </thead>

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
                                $discount   = $rows->sum('odisa');
                                $discount   = $discount == 0 ? null : $discount;
                                $uangMuka   = $header->dpamt == 0 ? null : $header->dpamt;
                                $dpp        = ($header->netbe - $header->dpamt) == 0 ? null : ($header->netbe - $header->dpamt);
                                $ppn        = $header->txamt;
                                $piutang    = $dpp + $ppn;
                                $instalasi  = $header->instf;
                                $uangMukaSA = ($header->sorfc === 'SA' && $header->invtp == 1) ? $header->header_gramt : 0;
                                $uangMukaSB = ($header->sorfc === 'SB') ? $header->header_gramt : 0;

                                // subtotal accumulation
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
                                <td class="center">{{ $no++ }}</td>
                                <td class="center">{{ $header->invdt ? date('d-m-Y', strtotime($header->invdt)) : '' }}</td>
                                <td class="center">{{ $header->formc }} {{ $header->invno }}</td>
                                <td class="center">{{ $header->fpnum ?? '' }}</td>
                                <td>{{ $header->cusna ?? '' }}</td>
                                <td class="center">
                                    @if($header->formc === 'SD')
                                        {{ ($header->dorfc ?? '') . ($header->donom ?? '') }}
                                    @else
                                        {{ ($header->sorfc ?? '') . ($header->sorno ?? '') }}
                                    @endif
                                </td>
                                <td class="right">{{ $grossSales ? number_format($grossSales, 0, ',', '.') : '' }}</td>
                                <td class="right">{{ $discount ? number_format($discount, 0, ',', '.') : '' }}</td>
                                <td class="right">{{ $uangMuka ? number_format($uangMuka, 0, ',', '.') : '' }}</td>
                                <td class="right">{{ $dpp ? number_format($dpp, 0, ',', '.') : '' }}</td>
                                <td class="right">{{ $ppn ? number_format($ppn, 0, ',', '.') : '' }}</td>
                                <td class="right">{{ $piutang ? number_format($piutang, 0, ',', '.') : '' }}</td>
                                <td class="right">{{ $instalasi ? number_format($instalasi, 0, ',', '.') : '' }}</td>
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
                                    @if ($header->formc === 'SD')
                                        {{ $header->tofee ?? '' }}
                                    @else
                                        {{ $header->group ?? '' }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        {{-- SUB TOTAL SC / SD --}}
                        @if($formItems->count() > 0)
                            @php
                                $grandGross      += $subtotal['gross'];
                                $grandDisc       += $subtotal['discount'];
                                $grandUangMuka   += $subtotal['uangMuka'];
                                $grandDpp        += $subtotal['dpp'];
                                $grandPpn        += $subtotal['ppn'];
                                $grandPiutang    += $subtotal['piutang'];
                                $grandInstalasi  += $subtotal['instalasi'];
                                $grandUangMukaSA += $subtotal['uangMukaSA'];
                                $grandUangMukaSB += $subtotal['uangMukaSB'];
                            @endphp

                            <tr>
                                <td colspan="6" class="right"><b>SUB TOTAL {{ $formc }}</b></td>
                                <td class="right"><b>{{ $subtotal['gross'] ? number_format($subtotal['gross'], 0, ',', '.') : '' }}</b></td>
                                <td class="right"><b>{{ $subtotal['discount'] ? number_format($subtotal['discount'], 0, ',', '.') : '' }}</b></td>
                                <td class="right"><b>{{ $subtotal['uangMuka'] ? number_format($subtotal['uangMuka'], 0, ',', '.') : '' }}</b></td>
                                <td class="right"><b>{{ $subtotal['dpp'] ? number_format($subtotal['dpp'], 0, ',', '.') : '' }}</b></td>
                                <td class="right"><b>{{ $subtotal['ppn'] ? number_format($subtotal['ppn'], 0, ',', '.') : '' }}</b></td>
                                <td class="right"><b>{{ $subtotal['piutang'] ? number_format($subtotal['piutang'], 0, ',', '.') : '' }}</b></td>
                                <td class="right"><b>{{ $subtotal['instalasi'] ? number_format($subtotal['instalasi'], 0, ',', '.') : '' }}</b></td>
                                <td class="right"><b>{{ $subtotal['uangMukaSA'] ? number_format($subtotal['uangMukaSA'], 0, ',', '.') : '' }}</b></td>
                                <td class="right"><b>{{ $subtotal['uangMukaSB'] ? number_format($subtotal['uangMukaSB'], 0, ',', '.') : '' }}</b></td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>

                {{-- GRAND TOTAL --}}
                <tfoot>
                    <tr>
                        <td colspan="6" class="right"><b>GRAND TOTAL</b></td>
                        <td class="right"><b>{{ number_format($grandGross, 0, ',', '.') }}</b></td>
                        <td class="right"><b>{{ number_format($grandDisc, 0, ',', '.') }}</b></td>
                        <td class="right"><b>{{ number_format($grandUangMuka, 0, ',', '.') }}</b></td>
                        <td class="right"><b>{{ number_format($grandDpp, 0, ',', '.') }}</b></td>
                        <td class="right"><b>{{ number_format($grandPpn, 0, ',', '.') }}</b></td>
                        <td class="right"><b>{{ number_format($grandPiutang, 0, ',', '.') }}</b></td>
                        <td class="right"><b>{{ number_format($grandInstalasi, 0, ',', '.') }}</b></td>
                        <td class="right"><b>{{ number_format($grandUangMukaSA, 0, ',', '.') }}</b></td>
                        <td class="right"><b>{{ number_format($grandUangMukaSB, 0, ',', '.') }}</b></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            {{-- TABEL REKAP GROUP --}}
            <div class="group-summary">
                <br><br>
                <div style="font-weight: bold; font-size: 7pt;">
                    REKAP PER GROUP {{ $brana }}, PERIODE {{ date('d-m-Y', strtotime($start)) }} S/D {{ date('d-m-Y', strtotime($end)) }}
                </div>
                <br>
                @php
                    $groupedByGroup = $items->groupBy(function ($row) {
                        return trim($row->group ?? '') ?: 'OTHERS';
                    });

                    $groupGrandGross      = 0;
                    $groupGrandDisc       = 0;
                    $groupGrandUangMuka   = 0;
                    $groupGrandDpp        = 0;
                    $groupGrandPpn        = 0;
                    $groupGrandPiutang    = 0;
                    $groupGrandInstalasi  = 0;
                    $groupGrandUangMukaSA = 0;
                    $groupGrandUangMukaSB = 0;

                    // Penampung rekap data group untuk perhitungan Jurnal
                    $groupDataList = [];
                @endphp

                <table>
                    <thead>
                        <tr>
                            <th>GROUP</th>
                            <th>GROSS</th>
                            <th>DISCOUNT</th>
                            <th>UANG MUKA</th>
                            <th>DPP</th>
                            <th>PPN</th>
                            <th>PIUTANG</th>
                            <th>INSTALASI</th>
                            <th>UANG MUKA SA</th>
                            <th>UANG MUKA SB</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($groupedByGroup as $groupName => $groupRows)
                            @php
                                $groupInvoices = $groupRows->groupBy(function ($row) {
                                    return $row->formc . '|' . $row->invno;
                                });

                                $groupGross      = 0;
                                $groupDiscount   = 0;
                                $groupUangMuka   = 0;
                                $groupDpp        = 0;
                                $groupPpn        = 0;
                                $groupPiutang    = 0;
                                $groupInstalasi  = 0;
                                $groupUangMukaSA = 0;
                                $groupUangMukaSB = 0;

                                foreach ($groupInvoices as $invoiceRows) {
                                    $header    = $invoiceRows->first();
                                    $gGross    = (float) $invoiceRows->sum('gramt');
                                    $discount  = (float) $invoiceRows->sum('odisa');
                                    $uangMuka  = (float) ($header->dpamt ?? 0);
                                    $netbe     = (float) ($header->netbe ?? 0);
                                    $ppn       = (float) ($header->txamt ?? 0);
                                    $instalasi = (float) ($header->instf ?? 0);
                                    $dpp       = $netbe - $uangMuka;
                                    $piutang   = $dpp + $ppn;

                                    $groupGross     += $gGross;
                                    $groupDiscount  += $discount;
                                    $groupUangMuka  += $uangMuka;
                                    $groupDpp       += $dpp;
                                    $groupPpn       += $ppn;
                                    $groupPiutang   += $piutang;
                                    $groupInstalasi += $instalasi;

                                    if ($header->sorfc === 'SA' && (int) $header->invtp === 1) {
                                        $groupUangMukaSA += (float) ($header->header_gramt ?? 0);
                                    }

                                    if ($header->sorfc === 'SB') {
                                        $groupUangMukaSB += (float) ($header->header_gramt ?? 0);
                                    }
                                }

                                $groupGrandGross      += $groupGross;
                                $groupGrandDisc       += $groupDiscount;
                                $groupGrandUangMuka   += $groupUangMuka;
                                $groupGrandDpp        += $groupDpp;
                                $groupGrandPpn        += $groupPpn;
                                $groupGrandPiutang    += $groupPiutang;
                                $groupGrandInstalasi  += $groupInstalasi;
                                $groupGrandUangMukaSA += $groupUangMukaSA;
                                $groupGrandUangMukaSB += $groupUangMukaSB;

                                // Simpan ke array penampung
                                $groupDataList[strtoupper(trim($groupName))] = [
                                    'gross'    => $groupGross,
                                    'discount' => $groupDiscount,
                                    'uangMuka' => $groupUangMuka,
                                ];
                            @endphp

                            <tr>
                                <td>{{ $groupName }}</td>
                                <td class="right">{{ $groupGross != 0 ? number_format($groupGross, 0, ',', '.') : '' }}</td>
                                <td class="right">{{ $groupDiscount != 0 ? number_format($groupDiscount, 0, ',', '.') : '' }}</td>
                                <td class="right">{{ $groupUangMuka != 0 ? number_format($groupUangMuka, 0, ',', '.') : '' }}</td>
                                <td class="right">{{ $groupDpp != 0 ? number_format($groupDpp, 0, ',', '.') : '' }}</td>
                                <td class="right">{{ $groupPpn != 0 ? number_format($groupPpn, 0, ',', '.') : '' }}</td>
                                <td class="right">{{ $groupPiutang != 0 ? number_format($groupPiutang, 0, ',', '.') : '' }}</td>
                                <td class="right">{{ $groupInstalasi != 0 ? number_format($groupInstalasi, 0, ',', '.') : '' }}</td>
                                <td class="right">{{ $groupUangMukaSA != 0 ? number_format($groupUangMukaSA, 0, ',', '.') : '' }}</td>
                                <td class="right">{{ $groupUangMukaSB != 0 ? number_format($groupUangMukaSB, 0, ',', '.') : '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td class="right"><b>TOTAL</b></td>
                            <td class="right"><b>{{ number_format($groupGrandGross, 0, ',', '.') }}</b></td>
                            <td class="right"><b>{{ number_format($groupGrandDisc, 0, ',', '.') }}</b></td>
                            <td class="right"><b>{{ number_format($groupGrandUangMuka, 0, ',', '.') }}</b></td>
                            <td class="right"><b>{{ number_format($groupGrandDpp, 0, ',', '.') }}</b></td>
                            <td class="right"><b>{{ number_format($groupGrandPpn, 0, ',', '.') }}</b></td>
                            <td class="right"><b>{{ number_format($groupGrandPiutang, 0, ',', '.') }}</b></td>
                            <td class="right"><b>{{ number_format($groupGrandInstalasi, 0, ',', '.') }}</b></td>
                            <td class="right"><b>{{ number_format($groupGrandUangMukaSA, 0, ',', '.') }}</b></td>
                            <td class="right"><b>{{ number_format($groupGrandUangMukaSB, 0, ',', '.') }}</b></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- REKAP PPN BERDASARKAN KODE TRANSAKSI FP --}}
            <div class="tax-summary">
                <br><br>
                <div style="font-weight: bold; font-size: 7pt;">
                    REKAP PPN {{ $brana }}, PERIODE {{ date('d-m-Y', strtotime($start)) }} S/D {{ date('d-m-Y', strtotime($end)) }}
                </div>
                <br>

                @php
                    $taxDescriptions = [
                        '01' => 'Kepada Selain Pemungut PPN',
                        '02' => 'Kepada Pemungut Bendaharawan',
                        '03' => 'Kepada Pemungut PPN Lainnya',
                        '04' => 'Menggunakan DPP Nilai Lain',
                        '05' => 'Pajak Masukannya di deemed',
                        '06' => 'Penyerahan Lainnya',
                        '07' => 'PPN-nya Tidak Dipungut',
                        '08' => 'Dibebaskan dari Pengenaan PPN',
                        '09' => 'Penyerahan Aktiva Pasal 16 D',
                        'XX' => 'Export/tanpa nomor seri'
                    ];

                    $taxSummary = [];
                    foreach ($taxDescriptions as $code => $description) {
                        $taxSummary[$code] = [
                            'description' => $description,
                            'dpp' => 0,
                            'ppn' => 0,
                        ];
                    }

                    $invoiceItems = $items->groupBy(function ($row) {
                        return $row->formc . '|' . $row->invno;
                    });

                    foreach ($invoiceItems as $invoiceRows) {
                        $header = $invoiceRows->first();
                        $fpnum  = trim((string) ($header->fpnum ?? ''));
                        $code   = substr($fpnum, 0, 2);

                        if ($code === '' || $code === '00') {
                            $code = 'XX';
                        }

                        if (!isset($taxSummary[$code])) {
                            continue;
                        }

                        $netbe    = (float) ($header->netbe ?? 0);
                        $uangMuka = (float) ($header->dpamt ?? 0);
                        $ppn      = (float) ($header->txamt ?? 0);
                        $dpp      = $netbe - $uangMuka;

                        $taxSummary[$code]['dpp'] += $dpp;
                        $taxSummary[$code]['ppn'] += $ppn;
                    }

                    $taxGrandDpp = 0;
                    $taxGrandPpn = 0;
                @endphp

                <table>
                    <thead>
                        <tr>
                            <th width="15%">KODE TRANSAKSI</th>
                            <th width="45%">DESCRIPTION</th>
                            <th width="20%">D P P</th>
                            <th width="20%">P P N</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($taxSummary as $code => $tax)
                            @php
                                $taxGrandDpp += $tax['dpp'];
                                $taxGrandPpn += $tax['ppn'];
                            @endphp

                            <tr>
                                <td class="center">{{ $code }}</td>
                                <td>{{ $tax['description'] }}</td>
                                <td class="right">{{ $tax['dpp'] != 0 ? number_format($tax['dpp'], 0, ',', '.') : '' }}</td>
                                <td class="right">{{ $tax['ppn'] != 0 ? number_format($tax['ppn'], 0, ',', '.') : '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="2" class="right"><b>TOTAL PAJAK</b></td>
                            <td class="right"><b>{{ number_format($taxGrandDpp, 0, ',', '.') }}</b></td>
                            <td class="right"><b>{{ number_format($taxGrandPpn, 0, ',', '.') }}</b></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- REKAP JURNAL PENJUALAN CABANG --}}
            <div class="journal-summary">
                <br>
                <div style="font-weight: bold; font-size: 7pt;">
                    JURNAL PENJUALAN CABANG {{ $brana }} PERIODE: {{ date('d-m-Y', strtotime($start)) }} S/D {{ date('d-m-Y', strtotime($end)) }}
                </div>
                <br>

                @php
                    // Group ACSLS & AccNo
                    $acslsMapping = [
                        'AVERY'                => ['sales' => '701.001', 'disc' => '721.001'],
                        'ZHONGHANG'            => ['sales' => '701.002', 'disc' => '721.002'],
                        'PREVENTATION'         => ['sales' => '701.003', 'disc' => '721.003'],
                        'FLINTEC'              => ['sales' => '701.004', 'disc' => '721.004'],
                        'PRECISA'              => ['sales' => '701.005', 'disc' => '721.005'],
                        'PRECISA/TECHCOMP'     => ['sales' => '701.005', 'disc' => '721.005'],
                        'RADWAG'               => ['sales' => '701.006', 'disc' => '721.006'],
                        'OTHERS'               => ['sales' => '701.007', 'disc' => '721.007'],
                        'LAINNYA'              => ['sales' => '701.007', 'disc' => '721.007'],
                        'SPAREPART'            => ['sales' => '703.001', 'disc' => '723.001'],
                        'SERVICE'              => ['sales' => '704.001', 'disc' => '724.001'],
                        'REPAIR'               => ['sales' => '704.001', 'disc' => '724.001'],
                        'MAINTENANCE CONTRACT' => ['sales' => '443.001', 'disc' => '444.001'],
                    ];

                    // Hitung Discount Uang Muka (dari sorfc SA / SB)
                    $discUangMuka = 0;
                    foreach ($items as $row) {
                        if (in_array($row->sorfc, ['SA', 'SB'])) {
                            $discUangMuka += (float) ($row->odisa ?? 0);
                        }
                    }

                    // Penampung Baris Jurnal
                    $debetJournal  = [];
                    $kreditJournal = [];

                    // Helper buat ambil accdesc dari database $mstacc
                    $getAccDesc = function($accNo, $fallback) use ($mstacc) {
                        return isset($mstacc[$accNo]) ? $mstacc[$accNo]->accdesc : $fallback;
                    };

                    // DEBET: Piutang Dagang (021.001)
                    if ($groupGrandPiutang > 0) {
                        $debetJournal['021.001'] = [
                            'accno'   => '021.001',
                            'accdesc' => $getAccDesc('021.001', 'PIUTANG DAGANG'),
                            'amount'  => $groupGrandPiutang
                        ];
                    }

                    // DEBET: Discount Uang Muka Penjualan (442.001)
                    if ($discUangMuka > 0) {
                        $debetJournal['442.001'] = [
                            'accno'   => '442.001',
                            'accdesc' => $getAccDesc('442.001', 'DISCOUNT UANG MUKA PENJUALAN'),
                            'amount'  => $discUangMuka
                        ];
                    }

                    // KREDIT: R/K - Pusat (431.001) -> Total PPN
                    if ($groupGrandPpn > 0) {
                        $kreditJournal['431.001'] = [
                            'accno'   => '431.001',
                            'accdesc' => $getAccDesc('431.001', 'R/K - PUSAT'),
                            'amount'  => $groupGrandPpn
                        ];
                    }

                    // KREDIT: Uang Muka Penjualan (441.001) -> (UM SA + UM SB) - UANG MUKA
                    $kreditUM = ($groupGrandUangMukaSA + $groupGrandUangMukaSB) - $groupGrandUangMuka;
                    if ($kreditUM > 0) {
                        $kreditJournal['441.001'] = [
                            'accno'   => '441.001',
                            'accdesc' => $getAccDesc('441.001', 'UANG MUKA PENJUALAN'),
                            'amount'  => $kreditUM
                        ];
                    }

                    // KREDIT: Pendapatan Instalasi (704.003)
                    if ($groupGrandInstalasi > 0) {
                        $kreditJournal['704.003'] = [
                            'accno'   => '704.003',
                            'accdesc' => $getAccDesc('704.003', 'PENDAPATAN - INSTALASI'),
                            'amount'  => $groupGrandInstalasi
                        ];
                    }

                    // DEBET & KREDIT berdasarkan Rekap Group (Penjualan & Discount)
                    foreach ($groupDataList as $gName => $gVal) {
                        $name = strtoupper(trim($gName));
                        $sAcc = $acslsMapping[$name]['sales'] ?? '701.007';
                        $dAcc = $acslsMapping[$name]['disc']  ?? '721.007';

                        // Penjualan (Kredit) -> Nilai Gross Group
                        $salesVal = (float) ($gVal['gross'] ?? 0);
                        if ($salesVal > 0) {
                            if (!isset($kreditJournal[$sAcc])) {
                                $kreditJournal[$sAcc] = [
                                    'accno'   => $sAcc,
                                    'accdesc' => $getAccDesc($sAcc, 'PENJUALAN BRG - ' . $name),
                                    'amount'  => 0
                                ];
                            }
                            $kreditJournal[$sAcc]['amount'] += $salesVal;
                        }

                        // Discount (Debet)
                        $discVal = (float) ($gVal['discount'] ?? 0);
                        if ($discVal > 0) {
                            if (!isset($debetJournal[$dAcc])) {
                                $debetJournal[$dAcc] = [
                                    'accno'   => $dAcc,
                                    'accdesc' => $getAccDesc($dAcc, 'DISCOUNT - ' . $name),
                                    'amount'  => 0
                                ];
                            }
                            $debetJournal[$dAcc]['amount'] += $discVal;
                        }
                    }

                    $totalDebet  = array_sum(array_column($debetJournal, 'amount'));
                    $totalKredit = array_sum(array_column($kreditJournal, 'amount'));
                @endphp

                <table>
                    <thead>
                        <tr>
                            <th width="50%">DESCRIPTION</th>
                            <th width="15%">ACCOUNT</th>
                            <th width="17%">DEBET</th>
                            <th width="18%">CREDIT</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- BARIS DEBET (> 0) --}}
                        @foreach($debetJournal as $row)
                            <tr>
                                <td>{{ $row['accdesc'] }}</td>
                                <td class="center">{{ $row['accno'] }}</td>
                                <td class="right">{{ number_format($row['amount'], 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        @endforeach

                        {{-- BARIS KREDIT (> 0) --}}
                        @foreach($kreditJournal as $row)
                            <tr>
                                <td>{{ $row['accdesc'] }}</td>
                                <td class="center">{{ $row['accno'] }}</td>
                                <td></td>
                                <td class="right">{{ number_format($row['amount'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="left"><b>GRAND TOTAL</b></td>
                            <td class="right"><b>{{ number_format($totalDebet, 0, ',', '.') }}</b></td>
                            <td class="right"><b>{{ number_format($totalKredit, 0, ',', '.') }}</b></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- SIGNATURE SECTION --}}
            <div class="signature-container">
                <div class="signature-date">
                    {{ date('d F Y', strtotime($end)) }}
                </div>

                <table class="signature-table">
                    <thead>
                        <tr>
                            <th width="33.33%">Dibuat</th>
                            <th width="33.33%">Diperiksa</th>
                            <th width="33.33%">Dibukukan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>