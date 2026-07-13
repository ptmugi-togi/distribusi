<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>{{ $retailinvrelhdr->invid }}</title>
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
            <td class="left" style="width:40%; vertical-align:top">
                <b>KEPADA YTH.</b><br>
                <table class="no-border" style="margin-left: 5px; overflow: wrap;">
                    <tr>
                        @if($retailinvrelhdr->delto == '0')
                            <td>
                                {{ $retailinvrelhdr->mcusmas->cusna }}<br>
                                {{ $retailinvrelhdr->mcusmas->address }}<br>
                                {{ $retailinvrelhdr->mcusmas->opost }}<br>
                                Telp : {{ $retailinvrelhdr->mcusmas->offph }}<br>
                                Fax : {{ $retailinvrelhdr->mcusmas->offax }}<br>
                                ATTN. {{ $retailinvrelhdr->mcusmas->ofcon }}
                            </td>
                        @else
                            <td>
                                {{ $retailinvrelhdr->mstmas->shpnm }}<br>
                                {{ $retailinvrelhdr->mstmas->deliveryaddress }}<br>
                                Telp : {{ $retailinvrelhdr->mstmas->phone }}<br>
                                Fax : {{ $retailinvrelhdr->mstmas->fax }}<br>
                                ATTN. {{ $retailinvrelhdr->mstmas->contp }}
                            </td>
                        @endif
                    </tr>
                </table>
            </td>

            <td class="left" style="width: 25%; vertical-align:top">
                DELIVERY ORDER : {{ $retailinvrelhdr->formc }} {{ $retailinvrelhdr->invno }}<br><br>
                TELP: {{ $retailinvrelhdr->mcusmas_do?->offph }}
                ATTN. {{ $retailinvrelhdr->mcusmas_do?->ofcon }}
            </td>

            <td class="left" style="width:21%; vertical-align:top">
                <b>NO. FAKTUR</b><br>
                <b>TGL. FAKTUR</b> <br>
                <b>SYARAT PEMBAYARAN</b> <br>
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
                <b>:</b> <br>
            </td>
    
            <td class="left" style="width:12%; vertical-align:top">
                {{ $retailinvrelhdr->formc }} {{ $retailinvrelhdr->sorno }}/{{ $retailinvrelhdr->braco }}<br>
                {{ \Carbon\Carbon::parse($retailinvrelhdr->invdt)->format('d-m-Y') }}<br>
                {{ $retailinvrelhdr->topay }}<br>
                {{ \Carbon\Carbon::parse($retailinvrelhdr->duedt)->format('d-m-Y') }}<br>
                {{ $retailinvrelhdr->sreno }}<br>
                {{ $retailinvrelhdr->sorfc }}-{{ $retailinvrelhdr->sorno }}<br>
                {{ $retailinvrelhdr->curco }}
            </td>
        </tr>
    </table>
    <table class="no-border" style="margin-top:5px;">
        <tr>
            <td class="left" style="width:50%; vertical-align:top">
                <b>NPWP/NITKU : {{ $retailinvrelhdr->mcusmas->taxrn }} /
                    @if ($retailinvrelhdr->delto == '0' && $retailinvrelhdr->mcusmas->nitku != null)
                        {{ $retailinvrelhdr->mcusmas->nitku }}</b>
                    @elseif ($retailinvrelhdr->delto != '0' && $retailinvrelhdr->mstmas->nitku != null)
                        {{ $retailinvrelhdr->mstmas->nitku }}</b>
                    @else
                        -
                    @endif
                </b>
            </td>
    
            <td class="left" style="width:50%; vertical-align:top">
                <b>PO PELANGGAN : {{ $retailinvrelhdr->cuspo }}</b>
            </td>
        </tr>
    </table>
    
    <!-- Detail Barang -->
    <table style="margin-top:5px; overflow: wrap; flex:1">
        <thead>
            <tr>
                <th style="width: 5%">NO.</th>
                <th style="width: 15%">KODE BARANG</th>
                <th style="width: 10%">BRAND</th>
                <th style="width: 36%">NAMA BARANG</th>
                <th style="width: 10%">QTY</th>
                <th style="width: 12%">HARGA SATUAN</th>
                <th style="width: 12%">JUMLAH HARGA</th>
            </tr>
        </thead>
    
        <tbody>
             @php
                $grouped = $retailinvrelhdr->retailinvreldtls->groupBy('opron');
            @endphp
            @foreach ($grouped as $opron => $items)
                @php
                    $first = $items->first();

                    $qty = $items->sum('qtyin');
                    $total = $items->sum('gramt');
                @endphp

                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td class="center">{{ $opron }}</td>
                    <td class="center">{{ $first->mpromas->brand_name ?? '-' }}</td>
                    <td>{{ $first->prona ?? '-' }}</td>
                    <td class="center">{{ $qty }} {{ $first->stdqu ?? '-' }}</td>
                    <td class="right">{{ formatNumberOnly($first->price, $retailinvrelhdr->curco) }}</td>
                    <td class="right">{{ formatNumberOnly($total, $retailinvrelhdr->curco) }}</td>
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
                        # {{ strtoupper(terbilang($retailinvrelhdr->blamt)) }} RUPIAH #
                    </div>
        
                    <br>
        
                    <div>
                        <p># PEMBAYARAN MELALUI TRANSFER BANK DILAKUKAN DENGAN PENUH ATAU FULL AMOUNT</p>
                        <p>(BIAYA BANK ATAU BIAYA TRANSFER MENJADI TANGGUNGAN PEMBELI)</p>
                        <p># PEMBAYARAN DIANGGAP LUNAS SETELAH MASUK KE DALAM REKENING BANK KAMI.</p>
                        <p># PEMBAYARAN IDR -> {{ $retailinvrelhdr->mbranch->bank_acc }}</p>
                        <p>{{ $retailinvrelhdr->mbranch->bank_address }}</p><br>
                        <p># Untuk korespondensi ke email: {{ $retailinvrelhdr->mbranch->email }}</p>
                    </div>
        
                </td>
        
                <td style="width:40%; vertical-align:top;">
                    <table class="no-border" style="width:100%; font-size:11px;">
                        <tr>
                            <td style="padding:1;">HARGA JUAL</td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($retailinvrelhdr->gramt, $retailinvrelhdr->curco) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:1;">DIKURANGI POTONGAN HARGA</td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($retailinvrelhdr->odisa ?? 0, $retailinvrelhdr->curco) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:1;">DIKURANGI UANG MUKA</td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($retailinvrelhdr->dpamt ?? 0, $retailinvrelhdr->curco) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:1;">TOTAL HARGA JUAL</td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($retailinvrelhdr->ntamt, $retailinvrelhdr->curco) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:1;">PAJAK PERTAMBAHAN NILAI (PPN)</td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($retailinvrelhdr->txamt, $retailinvrelhdr->curco) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:1;"><b>JUMLAH YANG HARUS DIBAYAR</b></td>
                            <td class="right" style="padding:1;">
                                {{ formatNumberOnly($retailinvrelhdr->blamt, $retailinvrelhdr->curco) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table class="no-border" style="width:100%; margin-top:40px; margin-left: 520px">
            <tr>
                @if(!empty($retailinvrelhdr->mformcode?->pos4) || !empty($retailinvrelhdr->mformcode?->name4))
                    <td class="center">{{ $retailinvrelhdr->mformcode->pos4 }}</td>
                @endif
            </tr>
    
            <tr style="height:120px;">
                <td class="center" style="padding-top: 40px">&nbsp;</td>
                @if(!empty($retailinvrelhdr->mformcode?->pos4) || !empty($retailinvrelhdr->mformcode?->name4)) <td class="center"></td> @endif
            </tr>
    
            <tr>
                @if(!empty($retailinvrelhdr->mformcode?->pos4))
                    <td class="center">( {{ trim($retailinvrelhdr->mformcode?->name4 ?? '') !== '' ? $retailinvrelhdr->mformcode->name4 : '....................' }} )</td>
                @endif
            </tr>
        </table>
    </div>
    <hr>

    <div style="font-size: 10px">{{ $retailinvrelhdr->mformcode->docd1 ?? '' }}</div>
    <div style="font-size: 10px">{{ $retailinvrelhdr->mformcode->docd2 ?? '' }}</div>
    <div style="font-size: 10px">{{ $retailinvrelhdr->created_by }} / {{ date('d-m-Y') }} / {{ date('H:i:s') }} / {{ $retailinvrelhdr->prctr }}</div>
    <div style="text-align: right; font-size: 9pt;">
        {PAGENO}/{nbpg}
    </div>
</htmlpagefooter>

</body>
</html>