<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>{{ $dpinvrelhdr->invid }}</title>
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
                        @if($dpinvrelhdr->delto == '0')
                            <td>
                                {{ $dpinvrelhdr->mcusmas->cusna }}<br>
                                {{ $dpinvrelhdr->mcusmas->offad }}
                                {{ $dpinvrelhdr->mcusmas->offad2 }}<br>
                                {{ $dpinvrelhdr->mcusmas->offad3 }}
                                {{ $dpinvrelhdr->mcusmas->offad4 }}<br>
                                {{ $dpinvrelhdr->mcusmas->offcy }}<br>
                                Telp : {{ $dpinvrelhdr->mcusmas->offph }}<br>
                                Fax : {{ $dpinvrelhdr->mcusmas->offax }}<br>
                                ATTN. {{ $dpinvrelhdr->mcusmas->ofcon }}
                            </td>
                        @else
                            <td>
                                {{ $dpinvrelhdr->mstmas->shpnm }}<br>
                                {{ $dpinvrelhdr->mstmas->deliveryaddress }}<br>
                                Telp : {{ $dpinvrelhdr->mstmas->phone }}<br>
                                Fax : {{ $dpinvrelhdr->mstmas->fax }}<br>
                                ATTN. {{ $dpinvrelhdr->mstmas->contp }}
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
                {{ $dpinvrelhdr->formc }} {{ $dpinvrelhdr->sorno }}/{{ $dpinvrelhdr->braco }}<br>
                {{ \Carbon\Carbon::parse($dpinvrelhdr->invdt)->format('d-m-Y') }}<br>
                {{ \Carbon\Carbon::parse($dpinvrelhdr->duedt)->format('d-m-Y') }}<br>
                {{ $dpinvrelhdr->sreno }}<br>
                {{ $dpinvrelhdr->sorfc }}-{{ $dpinvrelhdr->sorno }}<br>
                {{ $dpinvrelhdr->curco }}
            </td>
        </tr>
    </table>
    <table class="no-border" style="margin-top:5px;">
        <tr>
            <td class="left" style="width:33%; vertical-align:top">
                <b>NPWP/NITKU : {{ $dpinvrelhdr->mcusmas->npwp }}/{{ $dpinvrelhdr->mcusmas->nitku }}</b>
            </td>
    
            <td class="left" style="width:33%; vertical-align:top">
                <b>PO PELANGGAN : {{ $dpinvrelhdr->cuspo }}</b>
            </td>
    
            <td class="left" style="width:33%; vertical-align:top">
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
            @foreach($dpinvrelhdr->dpinvreldtls as $i => $d)
                <tr>
                    <td class="center">{{ $i + 1 }}.</td>
                    <td>{!! nl2br(e($dpinvrelhdr->itext)) !!}</td>
                    <td class="right">{{ formatNumberOnly($dpinvrelhdr->gramt, $dpinvrelhdr->curco) }}</td>
                </tr>
            @endforeach
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
                        # {{ strtoupper(terbilang($dpinvrelhdr->blamt)) }} RUPIAH #
                    </div>
        
                    <br>
        
                    <div>
                        <p># PEMBAYARAN MELALUI TRANSFER BANK DILAKUKAN DENGAN PENUH ATAU FULL AMOUNT</p>
                        <p>(BIAYA BANK ATAU BIAYA TRANSFER MENJADI TANGGUNGAN PEMBELI)</p>
                        <p># PEMBAYARAN DIANGGAP LUNAS SETELAH MASUK KE DALAM REKENING BANK KAMI.</p>
                        <p># PEMBAYARAN IDR -> {{ $dpinvrelhdr->mbranch->bank_acc }}</p>
                        <p>{{ $dpinvrelhdr->mbranch->bank_address }}</p><br>
                        <p># Untuk korespondensi ke email: {{ $dpinvrelhdr->mbranch->email }}</p>
                    </div>
        
                </td>
        
                <td style="width:40%; vertical-align:top;">
                    <table class="no-border" style="width:100%; font-size:11px;">
                        <tr>
                            <td style="padding:1;">HARGA JUAL</td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($dpinvrelhdr->gramt, $dpinvrelhdr->curco) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:1;">DIKURANGI POTONGAN HARGA</td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($dpinvrelhdr->odisa ?? 0, $dpinvrelhdr->curco) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:1;">DIKURANGI UANG MUKA</td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($dpinvrelhdr->dpamt ?? 0, $dpinvrelhdr->curco) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:1;">TOTAL HARGA JUAL</td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($dpinvrelhdr->ntamt, $dpinvrelhdr->curco) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:1;">PAJAK PERTAMBAHAN NILAI (PPN)</td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($dpinvrelhdr->txamt, $dpinvrelhdr->curco) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:1;"><b>JUMLAH YANG HARUS DIBAYAR</b></td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($dpinvrelhdr->blamt, $dpinvrelhdr->curco) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table class="no-border" style="width:100%; margin-top:40px; margin-left: 520px">
            <tr>
                @if(!empty($dpinvrelhdr->mformcode?->pos4) || !empty($dpinvrelhdr->mformcode?->name4))
                    <td class="center">{{ $dpinvrelhdr->mformcode->pos4 }}</td>
                @endif
            </tr>
    
            <tr style="height:120px;">
                <td class="center" style="padding-top: 40px">&nbsp;</td>
                @if(!empty($dpinvrelhdr->mformcode?->pos4) || !empty($dpinvrelhdr->mformcode?->name4)) <td class="center"></td> @endif
            </tr>
    
            <tr>
                @if(!empty($dpinvrelhdr->mformcode?->pos4))
                    <td class="center">( {{ trim($dpinvrelhdr->mformcode?->name4 ?? '') !== '' ? $dpinvrelhdr->mformcode->name4 : '....................' }} )</td>
                @endif
            </tr>
        </table>
    </div>
    <hr>

    <div style="font-size: 10px">{{ $dpinvrelhdr->mformcode->docd1 ?? '' }}</div>
    <div style="font-size: 10px">{{ $dpinvrelhdr->mformcode->docd2 ?? '' }}</div>
    <div style="font-size: 10px">{{ $dpinvrelhdr->created_by }} / {{ date('d-m-Y') }} / {{ date('H:i:s') }} / {{ $dpinvrelhdr->prctr }}</div>
    <div style="text-align: right; font-size: 9pt;">
        {PAGENO}/{nbpg}
    </div>
</htmlpagefooter>

</body>
</html>