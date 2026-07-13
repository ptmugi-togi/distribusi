<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>{{ $dn->dnid }}</title>

        <style>
            body {
                font-family: sans-serif;
                font-size: 8pt;
                color: #000;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            td, th {
                padding: 2px 3px;
                vertical-align: top;
            }

            .center { text-align: center; }
            .right { text-align: right; }
            .bold { font-weight: bold; }

            .line {
                border-top: 1px dashed #000;
                margin: 4px 0;
            }

            .title {
                font-size: 18pt;
                font-weight: bold;
                text-align: center;
                letter-spacing: 2px;
            }

            .small {
                font-size: 7pt;
            }

            .table-detail {
                width: 100%;
                border-collapse: collapse;
            }

            .table-detail th,
            .table-detail td {
                border: 1px solid #000;
                padding: 4px;
                vertical-align: middle;
            }

            .table-detail th {
                text-align: center;
                font-weight: bold;
            }

            .text-right{
                text-align:right;
            }

            .text-center{
                text-align:center;
            }

            .footer-line {
                border-top: 1px dashed #000;
                margin-top: 10px;
                padding-top: 5px;
            }
        </style>
    </head>
    <body>

        <table class="no-border">
            <tr>
                <td style="width:70%">
                    <img width="20%" src="{{ URL::asset('img/logomugi.png'); }}" alt="logo"><br>
                    Jl.M.T.HARYONO KAV.10, TEBET, TEBET BARAT, TEBET, <br>
                    KOTA ADM.JAKARTA SELATAN, DKI JAKARTA, 12810<br>
                    Phone : (62)21-8308415  Fax : (62)21-8308422 <br>
                    NPWP : 0013 0857 0906 2000
                </td>
            </tr>
            <br>
            <tr>
                <td class="center" style="width:30%">
                    <h1>DELIVERY NOTE</h1>
                </td>
            </tr>
        </table>

        <br>

        <table>
            <tr>
                <td style="width:32%;">
                    PELANGGAN : <br>
                    {{ $dn->mcusmas->cusna ?? '-' }}<br><br>
                    ALAMAT PENGIRIMAN :<br>
                    {{ $dn->mcusmas->address ?? '' }}<br>
                    {{ $dn->mcusmas->opost ?? '' }}<br><br>
                    ATTN. {{ $dn->mcusmas->ofcon ?? '-' }}
                </td>

                <td style="width:33%;">
                    ALAMAT PENAGIHAN :<br>
                    {{ $shipto->deliveryaddress ?? '-' }}<br><br>
                    ATTN. {{ $shipto->contp ?? $shipto->shpnm ?? '-' }}<br>
                    NPWP : {{ $shipto->nitku ?? '-' }}
                </td>

                <td style="width:18%;">
                    NO. D/N<br>
                    TANGGAL<br>
                    WORK ORDER<br>
                    QUOTATION<br>
                    CUST. PO<br>
                    DEPO<br>
                    MATA UANG
                </td>

                <td style="width:2%;">
                    :<br>:<br>:<br>:<br>:<br>:<br>:
                </td>

                <td style="width:15%;">
                    DN {{ $dn->dnnum }}<br>
                    {{ \Carbon\Carbon::parse($dn->dndat)->format('d-m-Y') }}<br>
                    {{ $dn->wonum ?? '-' }}<br>
                    {{ $dn->quote ?? '-' }}<br>
                    {{ $dn->cuspo ?? '-' }}<br>
                    {{ $dn->depo ?? '-' }}<br>
                    {{ $dn->curco ?? '-' }}
                </td>
            </tr>
        </table>

        <br>

        <table class="table-detail">
            <thead>
                <tr>
                    <th style="width:5%;">NO.</th>
                    <th style="width:60%;">NAMA BARANG / JASA KENA PAJAK</th>
                    <th style="width:12%;">QTY</th>
                    <th style="width:23%;">JUMLAH HARGA</th>
                </tr>
            </thead>

            <tbody>
                @php $no = 1; @endphp

                @foreach($services as $service)
                    <tr>
                        <td class="center">{{ $no++ }}</td>

                        <td>
                            <b>SERVICE : {{ $service->prona ?? $service->opron }}</b><br>
                            S/N : {{ $service->lotno ?? '-' }}

                            @foreach(($serviceFees[$service->dnlin] ?? []) as $fee)
                                <br>
                                &nbsp;&nbsp;- {{ $fee->descr ?? $fee->tofee }}
                                <span style="float:right;">
                                    @ {{ formatNumberOnly($fee->gramt ?? 0, $dn->curco) }}
                                </span>
                            @endforeach
                        </td>

                        <td class="center">
                            {{ number_format($service->trqty ?? 0, 0, ',', '.') }}
                            {{ $service->stdqu ?? '' }}
                        </td>

                        <td class="right">
                            {{ formatNumberOnly($service->gramt ?? 0, $dn->curco) }}
                        </td>
                    </tr>
                @endforeach

                @if($spareparts->count() > 0)
                    <tr>
                        <td class="center">{{ $no++ }}</td>

                        <td>
                            <b>SPAREPARTS:</b>

                            @foreach($spareparts as $sp)
                                <br>
                                &nbsp;&nbsp;- {{ $sp->opron }}
                                {{ $sp->prona ?? '' }}

                                <span style="float:right;">
                                    @ {{ formatNumberOnly($sp->gramt ?? 0, $dn->curco) }}
                                </span>
                            @endforeach
                        </td>

                        <td class="center">
                            <br>
                            @foreach($spareparts as $sp)
                                <div>
                                    {{ number_format($sp->trqty ?? 0, 0, ',', '.') }}
                                    {{ $sp->qunit ?? '' }}
                                </div>
                            @endforeach
                        </td>

                        <td class="right">
                            {{ formatNumberOnly($spareparts->sum('gramt'), $dn->curco) }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <htmlpagefooter name="myFooter">
            <div class="footer-summary">
                <div style="border-top:1px dashed #00000049; margin-bottom:5px; padding-top: 5px;"></div>
                <table class="no-border" style="margin-top:5px; width:100%;">
                    <tr>
                        <td style="width:60%; vertical-align:top; font-size:11px;">
                            <div>
                                <b>TERBILANG :</b> 
                                # {{ strtoupper(terbilang($dn->blamt)) }} RUPIAH #
                            </div>
                        </td>
                
                        <td style="width:40%; vertical-align:top;">
                            <table class="no-border" style="width:100%; font-size:11px;">
                                <tr>
                                    <td style="padding:1;">HARGA JUAL</td>
                                    <td class="right" style="padding:1;">
                                        {{ formatNumberOnly($dn->gramt, $dn->curco) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:1;">DIKURANGI POTONGAN HARGA</td>
                                    <td class="right" style="padding:1;">
                                        {{ formatNumberOnly($dn->odisa ?? 0, $dn->curco) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:1;">DIKURANGI UANG MUKA</td>
                                    <td class="right" style="padding:1;">
                                        {{ formatNumberOnly($dn->dpamt ?? 0, $dn->curco) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:1;">TOTAL HARGA JUAL</td>
                                    <td class="right" style="padding:1;">
                                        {{ formatNumberOnly($dn->ntamt, $dn->curco) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:1;">PAJAK PERTAMBAHAN NILAI (PPN)</td>
                                    <td class="right" style="padding:1;">
                                        {{ formatNumberOnly($dn->txamt, $dn->curco) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:1;"><b>JUMLAH YANG HARUS DIBAYAR</b></td>
                                    <td class="right" style="padding:1;">
                                        {{ formatNumberOnly($dn->blamt, $dn->curco) }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table class="no-border" style="width:100%; margin-top:40px; margin-left: 520px">
                    <tr>
                        @if(!empty($dn->mformcode?->pos4) || !empty($dn->mformcode?->name4))
                            <td class="center">{{ $dn->mformcode->pos4 }}</td>
                        @endif
                    </tr>
            
                    <tr style="height:120px;">
                        <td class="center" style="padding-top: 40px">&nbsp;</td>
                        @if(!empty($dn->mformcode?->pos4) || !empty($dn->mformcode?->name4)) <td class="center"></td> @endif
                    </tr>
            
                    <tr>
                        @if(!empty($dn->mformcode?->pos4))
                            <td class="center">( {{ trim($dn->mformcode?->name4 ?? '') !== '' ? $dn->mformcode->name4 : '....................' }} )</td>
                        @endif
                    </tr>
                </table>
            </div>
            <hr>

            <div style="font-size: 10px">{{ $dn->mformcode->docd1 ?? '' }}</div>
            <div style="font-size: 10px">{{ $dn->mformcode->docd2 ?? '' }}</div>
            <div style="font-size: 10px">{{ $dn->created_by }} / {{ date('d-m-Y') }} / {{ date('H:i:s') }} / {{ $dn->prctr }}</div>
            <div style="text-align: right; font-size: 9pt;">
                {PAGENO}/{nbpg}
            </div>
        </htmlpagefooter>

        <sethtmlpagefooter name="myFooter" value="on" />

    </body>
</html>