<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>{{ $projectinvrelhdr->invid }}</title>
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
    <!-- Header Perusahaan -->
    <table class="no-border">
        <tr>
            <td style="width:70%">
                <img width="20%" src="{{ URL::asset('img/logomugi.png'); }}" alt="logo"><br>
                Jl.M.T.HARYONO KAV.10, TEBET, TEBET BARAT, TEBET, KOTA ADM.JAKARTA SELATAN, DKI JAKARTA, 12810<br>
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
    
    <!-- Info Pelanggan, dikirim ke, dokumen -->
    <table class="no-border" style="margin-top:3px;">
        <tr>
            <td class="left" style="width:53%; vertical-align:top">
                <b>KEPADA YTH.</b><br>
                <table class="no-border" style="margin-left: 5px; overflow: wrap;">
                    <tr>
                        @if($projectinvrelhdr->delto == '0')
                            <td>
                                {{ $projectinvrelhdr->mcusmas->cusna }}<br>
                                {{ $projectinvrelhdr->mcusmas->offad }}
                                {{ $projectinvrelhdr->mcusmas->offad2 }}<br>
                                {{ $projectinvrelhdr->mcusmas->offad3 }}
                                {{ $projectinvrelhdr->mcusmas->offad4 }}<br>
                                {{ $projectinvrelhdr->mcusmas->offcy }}<br>
                                Telp : {{ $projectinvrelhdr->mcusmas->offph }}<br>
                                Fax : {{ $projectinvrelhdr->mcusmas->offax }}<br>
                                ATTN. {{ $projectinvrelhdr->mcusmas->ofcon }}
                            </td>
                        @else
                            <td>
                                {{ $projectinvrelhdr->mstmas->shpnm }}<br>
                                {{ $projectinvrelhdr->mstmas->deliveryaddress }}<br>
                                Telp : {{ $projectinvrelhdr->mstmas->phone }}<br>
                                Fax : {{ $projectinvrelhdr->mstmas->fax }}<br>
                                ATTN. {{ $projectinvrelhdr->mstmas->contp }}
                            </td>
                        @endif
                    </tr>
                </table>
            </td>

            <td style="width: 10%"></td>

            <td class="left" style="width:15%; vertical-align:top">
                <b>NO. FAKTUR</b><br>
                <b>TGL. FAKTUR</b> <br>
                <b>JATUH TEMPO</b> <br>
                <b>SALES REP.</b> <br>
                <b>ORDER CONF.</b> <br>
                <b>MATA UANG</b>
            </td>
    
            <td class="left" style="width:1%; vertical-align:top">
                <b>:</b> <br>
                <b>:</b> <br>
                <b>:</b> <br>
                <b>:</b> <br>
                <b>:</b> <br>
                <b>:</b> <br>
            </td>
    
            <td class="left" style="width:20%; vertical-align:top">
                {{ $projectinvrelhdr->formc }} {{ $projectinvrelhdr->sorno }}/{{ $projectinvrelhdr->braco }}<br>
                {{ \Carbon\Carbon::parse($projectinvrelhdr->invdt)->format('d-m-Y') }}<br>
                {{ \Carbon\Carbon::parse($projectinvrelhdr->duedt)->format('d-m-Y') }}<br>
                {{ $projectinvrelhdr->sreno }}<br>
                {{ $projectinvrelhdr->sorfc }}-{{ $projectinvrelhdr->sorno }}<br>
                {{ $projectinvrelhdr->curco }}
            </td>
        </tr>
    </table>
    <table class="no-border" style="margin-top:5px;">
        <tr>
            <td class="left" style="width:50%; vertical-align:top">
                <b>NPWP/NITKU : {{ $projectinvrelhdr->mcusmas->taxrn }}/
                    @if ($projectinvrelhdr->delto == '0' && $projectinvrelhdr->mcusmas->nitku != null)
                        {{ $projectinvrelhdr->mcusmas->nitku }}</b>
                    @elseif ($projectinvrelhdr->delto != '0' && $projectinvrelhdr->mstmas->nitku != null)
                        {{ $projectinvrelhdr->mstmas->nitku }}</b>
                    @else
                        -
                    @endif
            </td>
            
    
            <td class="right" style="width:50%; vertical-align:top">
                <b>PO PELANGGAN : {{ $projectinvrelhdr->cuspo }}</b>
            </td>
        </tr>
    </table>
    
    <!-- Detail Barang -->
    <table style="margin-top:5px; overflow: wrap; flex:1">
        <thead>
            <tr>
                <th style="width: 5%">NO.</th>
                <th style="width: 70%">NAMA BARANG / JASA KENA PAJAK</th>
                <th style="width: 25%">JUMLAH HARGA</th>
            </tr>
        </thead>
    
        <tbody>
            @php
                $text = e($projectinvrelhdr->itext);

                $text = str_replace('    ', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $text);

                $text = nl2br($text);
            @endphp
            <tr>
                <td class="center"></td>
                <td>{!! $text !!}</td>
                <td class="right">{{ formatNumberOnly($projectinvrelhdr->gramt, $projectinvrelhdr->curco) }}</td>
            </tr>
        </tbody>
    </table>
</div>


<htmlpagefooter name="myFooter">
    <div class="footer-summary">
        <div style="border-top:1px dashed #00000049; margin-bottom:5px; padding-top: 5px;"></div>
        <table class="no-border" style="margin-top:5px; width:100%;">
            <tr>
                <td style="width:60%; vertical-align:top; font-size:11px;">
                    <div>
                        <b>TERBILANG :</b> 
                        # {{ strtoupper(terbilang($projectinvrelhdr->blamt)) }} RUPIAH #
                    </div>
        
                    <br>
        
                    <div>
                        <p># PEMBAYARAN MELALUI TRANSFER BANK DILAKUKAN DENGAN PENUH ATAU FULL AMOUNT</p>
                        <p>(BIAYA BANK ATAU BIAYA TRANSFER MENJADI TANGGUNGAN PEMBELI)</p>
                        <p># PEMBAYARAN DIANGGAP LUNAS SETELAH MASUK KE DALAM REKENING BANK KAMI.</p>
                        <p># PEMBAYARAN IDR -> {{ $projectinvrelhdr->mbranch->bank_acc }}</p>
                        <p>{{ $projectinvrelhdr->mbranch->bank_address }}</p><br>
                        <p># Untuk korespondensi ke email: {{ $projectinvrelhdr->mbranch->email }}</p>
                    </div>
        
                </td>
        
                <td style="width:40%; vertical-align:top;">
                    <table class="no-border" style="width:100%; font-size:11px;">
                        <tr>
                            <td style="padding:1;">HARGA JUAL</td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($projectinvrelhdr->gramt, $projectinvrelhdr->curco) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:1;">DIKURANGI POTONGAN HARGA</td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($projectinvrelhdr->odisa ?? 0, $projectinvrelhdr->curco) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:1;">DIKURANGI UANG MUKA</td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($projectinvrelhdr->dpamt ?? 0, $projectinvrelhdr->curco) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:1;">TOTAL HARGA JUAL</td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($projectinvrelhdr->ntamt, $projectinvrelhdr->curco) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:1;">PAJAK PERTAMBAHAN NILAI (PPN)</td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($projectinvrelhdr->txamt, $projectinvrelhdr->curco) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:1;"><b>JUMLAH YANG HARUS DIBAYAR</b></td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($projectinvrelhdr->blamt, $projectinvrelhdr->curco) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table class="no-border" style="width:100%; margin-top:40px; margin-left: 520px">
            <tr>
                @if(!empty($projectinvrelhdr->mformcode?->pos4) || !empty($projectinvrelhdr->mformcode?->name4))
                    <td class="center">{{ $projectinvrelhdr->mformcode->pos4 }}</td>
                @endif
            </tr>
    
            <tr style="height:120px;">
                <td class="center" style="padding-top: 40px">&nbsp;</td>
                @if(!empty($projectinvrelhdr->mformcode?->pos4) || !empty($projectinvrelhdr->mformcode?->name4)) <td class="center"></td> @endif
            </tr>
    
            <tr>
                @if(!empty($projectinvrelhdr->mformcode?->pos4))
                    <td class="center">( {{ trim($projectinvrelhdr->mformcode?->name4 ?? '') !== '' ? $projectinvrelhdr->mformcode->name4 : '....................' }} )</td>
                @endif
            </tr>
        </table>
    </div>
    <hr>

    <div style="font-size: 10px">{{ $projectinvrelhdr->mformcode->docd1 ?? '' }}</div>
    <div style="font-size: 10px">{{ $projectinvrelhdr->mformcode->docd2 ?? '' }}</div>
    <div style="font-size: 10px">{{ $projectinvrelhdr->created_by }} / {{ date('d-m-Y') }} / {{ date('H:i:s') }} / {{ $projectinvrelhdr->prctr }}</div>
    <div style="text-align: right; font-size: 9pt;">
        {PAGENO}/{nbpg}
    </div>
</htmlpagefooter>

</body>
</html>