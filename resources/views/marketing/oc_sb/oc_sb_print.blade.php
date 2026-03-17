<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>{{ $ocsbhdr->ocid }}</title>
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
        <br>
        <tr>
            <td class="center" style="width:30%">
                <h1>ORDER CONFIRMATION</h1>
            </td>
        </tr>
    </table>
    
    <!-- Info Pelanggan, dikirim ke, dokumen -->
    <table class="no-border" style="margin-top:5px;">
        <tr>
            <td class="left" style="width:13%; vertical-align:top">
                <b>PELANGGAN :</b><br>
            </td>
            <td class="left" style="width:40%; vertical-align:top">
                {{ $ocsbhdr->mcusmas->title }} {{ $ocsbhdr->mcusmas->cusna }} <br>
                {{ $ocsbhdr->mcusmas->offad }} <br>
                @if (!empty($ocsbhdr->mcusmas->offad2))
                    {{ $ocsbhdr->mcusmas->offad2 }} <br>
                @endif
                @if (!empty($ocsbhdr->mcusmas->offad3))
                    {{ $ocsbhdr->mcusmas->offcy }} <br>
                @endif
                @if (!empty($ocsbhdr->mcusmas->offad3))
                    {{ $ocsbhdr->mcusmas->offad3 }} <br>
                @endif
                @if (!empty($ocsbhdr->mcusmas->offad4))
                    {{ $ocsbhdr->mcusmas->offad4 }} <br>
                @endif
                @if (!empty($ocsbhdr->mcusmas->opost))
                    {{ $ocsbhdr->mcusmas->opost }} <br>
                @endif
                <br>
                <b>Telp:</b> {{ $ocsbhdr->mcusmas->offph }} <br>
                <b>Attn.</b> {{ $ocsbhdr->mcusmas->ofcon }} <br>
                <b>NPWP.</b> {{ $ocsbhdr->mcusmas->taxrn }} <br>
                <b>PO PELANGGAN :</b> {{ $ocsbhdr->cuspo }}
            </td>

            <td class="left" style="width:19%; vertical-align:top">
                <b>NOMOR OC</b><br>
                @if ($ocsbhdr->depo != NULL)
                    <b>CABANG / DEPO</b> <br>
                @endif
                <b>TANGGAL OC</b> <br>
                <b>SALES REP</b> <br>
                <b>INDUSTRY</b> <br>
                <b>RENCANA CUT-OFF</b> <br>
                <b>DISPOSISI EB</b> <br>
                <b>MATA UANG</b>
            </td>
    
            <td class="left" style="width:1%; vertical-align:top">
                <b>:</b> <br>
                @if ($ocsbhdr->depo != NULL)
                    <b>:</b><br>
                @endif
                <b>:</b> <br>
                <b>:</b> <br>
                <b>:</b> <br>
                <b>:</b> <br>
                <b>:</b> <br>
                <b>:</b> <br>
            </td>
    
            <td class="left" style="width:25%; vertical-align:top">
                {{ $ocsbhdr->braco }}-{{ $ocsbhdr->formc }} {{ $ocsbhdr->sorno }}<br>
                @if ($ocsbhdr->depo != NULL)
                   {{ $ocsbhdr->mbranch->brana }} / {{ $ocsbhdr->mdepo->name ?? '-' }}<br>
                @endif
                {{ \Carbon\Carbon::parse($ocsbhdr->sordt)->format('d-m-Y') }}<br>
                {{ $ocsbhdr->msreno->srena }}<br>
                {{ $mcindu?->descr_cindu ?? '-' }}<br>
                {{ \Carbon\Carbon::parse($ocsbhdr->pcuto)->format('d-m-Y') }}<br>
                {{ $ocsbhdr->nodeb }}<br>
                {{ $ocsbhdr->curco }}
            </td>
        </tr>
    </table>
    
    <!-- Detail Barang -->
    <table style="margin-top:5px; overflow: wrap; flex:1">
        <thead>
            <tr>
                <th style="width: 5%">NO.</th>
                <th style="width: 49%">PRODUK</th>
                <th style="width: 11%">JUMLAH</th>
                <th style="width: 11%">HARGA SATUAN</th>
                <th style="width: 10%">DISCOUNT</th>
                <th style="width: 14%">TOTAL</th>
            </tr>
        </thead>
    
        <tbody>
            @php
                $totalGross = 0;
                $totalDisc  = 0;
            @endphp
    
            @foreach($ocsbhdr->ocsbdtls as $i => $d)
                @php
                    $qty   = (float) $d->qtyor;
                    $price = (float) $d->price;
                    $odisa = (float) $d->odisa;
    
                    $grossRow = $price * $qty;
                    $discRow  = $odisa * $qty;
                    $netRow   = ($price - $odisa) * $qty;
    
                    $totalGross += $grossRow;
                    $totalDisc  += $discRow;
                @endphp
    
                <tr>
                    <td class="center">{{ $i + 1 }}.</td>
                    <td>
                        <b>{{ $d->mpromas->opron ?? '-' }} </b>
                        <b>{{ $d->mpromas->prona ?? '-' }}</b>
    
                        <table class="no-border" style="margin-left: 5px; overflow: wrap;">
                            <tr><td style="padding:0;">INSTALLATION BY: {{ $d->insby ?? '-' }}</td></tr>
                            <tr><td style="padding:0;">Planned Ins Date: {{ $d->insdt ?? '-' }}</td></tr>
                            @if ($d->qtyor > 1)
                                <tr>
                                    <td style="padding:0;">
                                        PL : {{ $ocsbhdr->curco }}
                                        {{ formatNumberOnly($d->plist, $ocsbhdr->curco) }}
                                        X {{ $d->qtyor }}
                                        = {{ formatNumberOnly($d->plist * $d->qtyor, $ocsbhdr->curco) }}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td style="padding:0;">
                                        PL : {{ $ocsbhdr->curco }}
                                        {{ formatNumberOnly($d->plist, $ocsbhdr->curco) }}
                                    </td>
                                </tr>
                            @endif

                            @if (!empty($d->teknik))
                                <tr>
                                    <td style="padding:0;">
                                        Teknik : 
                                        {{ $ocsbhdr->curco }}
                                        {{ formatNumberOnly($d->teknik, $ocsbhdr->curco) }}
                                    </td>
                                </tr>
                            @endif

                            @if(isset($bomList[$d->opron]) && $bomList[$d->opron]->count())
                                <tr>
                                    <td style="padding:0; padding-top:5px;">
                                        <b>CONSIST OF GOODS :</b>
                                        <table style="width:100%; border-collapse:collapse; font-size:9px;">
                                        @foreach($bomList[$d->opron] as $bom)
                                        <tr>
                                            <td style="width:80%; padding:0;">
                                                - {{ $bom->opron }} {{ $bom->prona }}
                                            </td>
                                            <td style="width:20%; text-align:right; padding:0;">
                                                {{ $bom->trqty }} {{ $bom->stdqu }}
                                            </td>
                                        </tr>
                                        @endforeach
                                        </table>
                                    </td>
                                </tr>
                            @endif
    
                            @if(!empty($d->noted))
                                <tr><td style="padding:0; padding-top:5px;">{{ $d->noted }}</td></tr>
                            @endif
                        </table>
                    </td>
    
                    <td class="center">{{ $d->qtyor }} {{ $d->mpromas->stdqu}}</td>
                    <td class="right">{{ formatNumberOnly($price, $ocsbhdr->curco) }}</td>
    
                    <td class="right">{{ formatNumberOnly($odisa, $ocsbhdr->curco) }}</td>
    
                    <td class="right">{{ formatNumberOnly($netRow, $ocsbhdr->curco) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="no-border" width="100%" style="margin-top:5px; font-size:10px;">
        <tr>
            <td width="3%"></td>
            <td width="36%"><b>RENCANA FAKTUR :</b></td>
            <td width="14%"></td>
            <td width="47%"><b>SPLIT QUOTA :</b></td>
        </tr>

        @foreach ($ocsbhdr->invoices as $i => $inv)
        <tr>
            <td>
                {{ $inv->phase }}. 
            </td>
            <td>
                {{ \Carbon\Carbon::parse($ocsbhdr->sordt)->format('d-m-Y') }}
                {{ $inv->descr }}
            </td>

            <td>
                {{ formatNumberOnly($inv->blamt, $ocsbhdr->curco) }}
            </td>

            <td style="white-space:nowrap;">
                @for ($q = 1; $q <= 5; $q++)
                    @php
                        $p = "smqp".$q;
                        $tb = "smqtb".$q;
                        $ts = "smqts".$q;
                    @endphp

                    @if ($inv->$p)
                        {{ $inv->$p }}%/{{ $inv->$tb }}/{{ $inv->$ts }}&nbsp;
                    @endif
                @endfor
            </td>
        </tr>
        @endforeach
    </table>
</div>

<div class="">
    <div style="border-top:1px dashed #00000049; margin-bottom:5px; padding-top: 5px;"></div>
    <table class="no-border" style="margin-top:5px;">
        <tr>
            <td style="width:60%;"></td>
            <td style="width:40%; vertical-align:top">
                <table class="no-border">
                    <tr>
                        <td>TOTAL GROSS</td>
                        <td class="right">{{ formatNumberOnly($ocsbhdr->gross, $ocsbhdr->curco) }}</td>
                    </tr>
                    <tr>
                        <td>DISCOUNT</td>
                        <td class="right">{{ $ocsbhdr->odisa != 0 ? '- ' . formatNumberOnly($ocsbhdr->odisa, $ocsbhdr->curco) : '0' }}</td>
                    </tr>
                    <tr>
                        <td>INSTALLATION</td>
                        <td class="right">{{ formatNumberOnly($ocsbhdr->insfe, $ocsbhdr->curco) ?? '0' }}</td>
                    </tr>
                    <tr>
                        <td>PPN {{ $ocsbhdr->vatax }}%</td>
                        <td class="right">{{ formatNumberOnly($ocsbhdr->vtamt, $ocsbhdr->curco) }}</td>
                    </tr>
                    <tr>
                        <td><b>BILLING AMOUNT</b></td>
                        <td class="right">{{ formatNumberOnly($ocsbhdr->billv, $ocsbhdr->curco) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<div class="footer-summary">
    <table class="no-border" style="margin-top:5px;">
        <tr>
            <td style="width:60%; vertical-align:top">
                <b>REMARK:</b><br>
                {!! nl2br(e($ocsbhdr->noteh)) !!}
            </td>

            <td style="width:40%; vertical-align:top">
            </td>
        </tr>
    </table>

    <table class="no-border" style="width:100%; margin-top:40px;">
        <tr>
            @if (!empty($ocsbhdr->mformcode?->pos1) || !empty($ocsbhdr->mformcode?->name1))
                <td class="center">{{ $ocsbhdr->mformcode?->pos1 ?? '' }}</td>
            @endif

            @if(!empty($ocsbhdr->mformcode?->pos2) || !empty($ocsbhdr->mformcode?->name2))
                <td class="center">{{ $ocsbhdr->mformcode->pos2 }}</td>
            @endif

            @if(!empty($ocsbhdr->mformcode?->pos3) || !empty($ocsbhdr->mformcode?->name3))
                <td class="center">{{ $ocsbhdr->mformcode->pos3 }}</td>
            @endif

            @if(!empty($ocsbhdr->mformcode?->pos4) || !empty($ocsbhdr->mformcode?->name4))
                <td class="center">{{ $ocsbhdr->mformcode->pos4 }}</td>
            @endif
        </tr>

        <tr style="height:80px;">
            <td class="center" style="padding-top: 40px">&nbsp;</td>
            @if(!empty($ocsbhdr->mformcode?->pos1) || !empty($ocsbhdr->mformcode?->name1)) <td class="center"></td> @endif
            @if(!empty($ocsbhdr->mformcode?->pos2) || !empty($ocsbhdr->mformcode?->name2)) <td class="center"></td> @endif
            @if(!empty($ocsbhdr->mformcode?->pos3) || !empty($ocsbhdr->mformcode?->name3)) <td class="center"></td> @endif
            @if(!empty($ocsbhdr->mformcode?->pos4) || !empty($ocsbhdr->mformcode?->name4)) <td class="center"></td> @endif
        </tr>

        <tr>
            @if(!empty($ocsbhdr->mformcode?->pos1))
                <td class="center">( {{ trim($ocsbhdr->mformcode?->name1 ?? '') !== '' ? $ocsbhdr->mformcode->name1 : '....................' }} )</td>
            @endif

            @if(!empty($ocsbhdr->mformcode?->pos2))
                <td class="center">( {{ trim($ocsbhdr->mformcode?->name2 ?? '') !== '' ? $ocsbhdr->mformcode->name2 : '....................' }} )</td>
            @endif

            @if(!empty($ocsbhdr->mformcode?->pos3))
                <td class="center">( {{ trim($ocsbhdr->mformcode?->name3 ?? '') !== '' ? $ocsbhdr->mformcode->name3 : '....................' }} )</td>
            @endif

            @if(!empty($ocsbhdr->mformcode?->pos4))
                <td class="center">( {{ trim($ocsbhdr->mformcode?->name4 ?? '') !== '' ? $ocsbhdr->mformcode->name4 : '....................' }} )</td>
            @endif
        </tr>
    </table>
</div>

<htmlpagefooter name="myFooter">
    <hr>

    <div style="font-size: 10px">{{ $ocsbhdr->mformcode->docd1 }}</div>
    <div style="font-size: 10px">{{ $ocsbhdr->mformcode->docd2 }}</div>
    <div style="font-size: 10px">{{ $ocsbhdr->created_by }} / {{ date('d-m-Y') }} / {{ date('H:i:s') }} / {{ $ocsbhdr->prctr }}</div>
    <div style="text-align: right; font-size: 9pt;">
        {PAGENO}/{nbpg}
    </div>
</htmlpagefooter>

</body>
</html>