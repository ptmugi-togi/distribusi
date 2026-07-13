<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>{{ $tinmas->invid }}</title>

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
                    <h1>FAKTUR</h1>
                </td>
            </tr>
        </table>

        <br>

        <table>
            <tr>
              <td style="width:32%;">
                PELANGGAN : <br>
                {{ $customer->cusna ?? '-' }}
                <br><br>
                ALAMAT PENGIRIMAN :<br>
                @if($tinmas->delto == 0)
                    {{ $shipto->address ?? '' }}
                    <br>
                    {{ $shipto->opost ?? '' }}
                    <br><br>
                    ATTN.
                    {{ $shipto->ofcon ?? '-' }}
                @else
                    {{ $shipto->deliveryaddress ?? '' }}
                    <br><br>
                    ATTN.
                    {{ $shipto->shpnm ?? '-' }}
                @endif
            </td>

                <td style="width:33%;">
                    DIVISI : {{ $tinmas->divco ?? '-' }}<br>
                    WORK ORDER : -<br>
                    CUSTOMER PO : {{ $tinmas->cuspo ?? '-' }}<br>
                    {{-- QUOTATION : {{ $tinmas->quote ?? '-' }} --}}
                </td>

                <td style="width:18%;">
                    NO. FAKTUR<br>
                    TGL. FAKTUR<br>
                    TGL. JATUH TEMPO<br>
                    NO. DELIVERY NOTE<br>
                    TGL. DELIVERY NOTE<br>
                    MATA UANG
                </td>

                <td style="width:2%;">
                    :<br>:<br>:<br>:<br>:<br>:<br>
                </td>

                <td style="width:15%;">
                    {{ $tinmas->formc }} {{ $tinmas->invno }}/{{ $tinmas->braco }}<br>
                    {{ \Carbon\Carbon::parse($tinmas->invdt)->format('d-m-Y') }}<br>
                    {{ \Carbon\Carbon::parse($tinmas->duedt)->format('d-m-Y') }}<br>
                    {{ $tinmas->dorfc }} {{ $tinmas->donom ?? '-' }}/{{ $tinmas->braco }}<br>
                    {{ \Carbon\Carbon::parse($tinmas->dndat)->format('d-m-Y') }}<br>
                    {{ $tinmas->curco ?? '-' }}
                </td>
            </tr>
        </table>

        <br>

        <table class="table-detail">
            <thead>
                <tr>
                    <th style="width:5%;">NO.</th>
                    <th style="width:60%;">NAMA BARANG / JASA KENA PAJAK</th>
                    <th style="width:12%;">KWANTITAS</th>
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

                            @foreach(($serviceFees[$service->invln] ?? []) as $fee)
                                <br>
                                &nbsp;&nbsp;- {{ $fee->descr ?? $fee->tofee }}
                                <span style="float:right;">
                                    @ {{ formatNumberOnly($fee->gramt ?? 0, $tinmas->curco) }}
                                </span>
                            @endforeach
                        </td>

                        <td class="center">
                            {{ number_format($service->trqty ?? 0, 0, ',', '.') }}
                            {{ $service->stdqu ?? '' }}
                        </td>

                        <td class="right">
                            {{ formatNumberOnly($service->gramt ?? 0, $tinmas->curco) }}
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
                                    @ {{ formatNumberOnly($sp->gramt ?? 0, $tinmas->curco) }}
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
                            {{ formatNumberOnly($spareparts->sum('gramt'), $tinmas->curco) }}
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
                                # {{ strtoupper(terbilang($tinmas->blamt)) }} RUPIAH #
                            </div>

                            <br>
                
                            <div>
                                <p># PEMBAYARAN MELALUI TRANSFER BANK DILAKUKAN DENGAN PENUH ATAU FULL AMOUNT</p>
                                <p>(BIAYA BANK ATAU BIAYA TRANSFER MENJADI TANGGUNGAN PEMBELI)</p>
                                <p># PEMBAYARAN DIANGGAP LUNAS SETELAH MASUK KE DALAM REKENING BANK KAMI.</p>
                                <p># PEMBAYARAN IDR -> {{ $tinmas->bank_acc }}</p>
                                <p>{{ $tinmas->bank_address }}</p><br>
                                <p># Untuk korespondensi ke email: {{ $tinmas->email }}</p>
                            </div>
                        </td>
                
                        <td style="width:40%; vertical-align:top;">
                            <table class="no-border" style="width:100%; font-size:11px;">
                                <tr>
                                    <td style="padding:1;">HARGA JUAL</td>
                                    <td class="right" style="padding:1;">
                                        {{ formatNumberOnly($tinmas->gramt, $tinmas->curco) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:1;">DIKURANGI POTONGAN HARGA</td>
                                    <td class="right" style="padding:1;">
                                        {{ formatNumberOnly($tinmas->odisa ?? 0, $tinmas->curco) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:1;">DIKURANGI UANG MUKA</td>
                                    <td class="right" style="padding:1;">
                                        {{ formatNumberOnly($tinmas->dpamt ?? 0, $tinmas->curco) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:1;">TOTAL HARGA JUAL</td>
                                    <td class="right" style="padding:1;">
                                        {{ formatNumberOnly($tinmas->ntamt, $tinmas->curco) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:1;">PAJAK PERTAMBAHAN NILAI (PPN)</td>
                                    <td class="right" style="padding:1;">
                                        {{ formatNumberOnly($tinmas->txamt, $tinmas->curco) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:1;"><b>JUMLAH YANG HARUS DIBAYAR</b></td>
                                    <td class="right" style="padding:1;">
                                        {{ formatNumberOnly($tinmas->blamt, $tinmas->curco) }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table class="no-border" style="width:100%; margin-top:40px; margin-left: 520px">
                    <tr>
                        @if(!empty($tinmas->pos4) || !empty($tinmas->name4))
                            <td class="center">{{ $tinmas->pos4 }}</td>
                        @endif
                    </tr>
            
                    <tr style="height:120px;">
                        <td class="center" style="padding-top: 40px">&nbsp;</td>
                        @if(!empty($tinmas->pos4) || !empty($tinmas->name4)) <td class="center"></td> @endif
                    </tr>
            
                    <tr>
                        @if(!empty($tinmas->pos4))
                            <td class="center">( {{ trim($tinmas->name4 ?? '') !== '' ? $tinmas->name4 : '....................' }} )</td>
                        @endif
                    </tr>
                </table>
            </div>
            <hr>

            <div style="font-size: 10px">{{ $tinmas->docd1 ?? '' }}</div>
            <div style="font-size: 10px">{{ $tinmas->docd2 ?? '' }}</div>
            <div style="font-size: 10px">{{ $tinmas->created_by }} / {{ date('d-m-Y') }} / {{ date('H:i:s') }} / {{ $tinmas->prctr }}</div>
            <div style="text-align: right; font-size: 9pt;">
                {PAGENO}/{nbpg}
            </div>
        </htmlpagefooter>

        <sethtmlpagefooter name="myFooter" value="on" />

    </body>
</html>