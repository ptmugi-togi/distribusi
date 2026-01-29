<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>{{ $ochdr->ocid }}</title>
<style>
    body {
        font-family: sans-serif;
        font-size: 8pt;
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

    .no-border td, .no-border th {
        border: none !important;
    }

    .right {
        text-align: right;
    }

    .center {
        text-align: center;
    }

    /* Bagian total + tanda tangan */
    .footer-summary {
        position: relative;
        bottom: 0;
        left: 0;
        right: 0;
        margin-top: 20px;
    }

    .footer-summary table {
        width: 100%;
        border: none;
    }

    .footer-summary td {
        border: none;
        padding: 5px;
        font-size: 10pt;
    }
</style>
</head>

<body style="min-height:100vh; display:flex; flex-direction:column;">

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
<table class="no-border" style="margin-top:10px;">
    <tr>
        <td class="left" style="width:33%; vertical-align:top">
            <b>PELANGGAN :</b><br>
            <table class="no-border" style="margin-left: 5px; overflow: wrap;">
                <tr>
                    <td>
                        {{ $ochdr->mcusmas->title }} {{ $ochdr->mcusmas->cusna }} <br>
                        {{ $ochdr->mcusmas->offad }} <br>
                        @if (!empty($ochdr->mcusmas->offad2))
                            {{ $ochdr->mcusmas->offad2 }} <br>
                        @endif
                        @if (!empty($ochdr->mcusmas->offad3))
                            {{ $ochdr->mcusmas->offcy }} <br>
                        @endif
                        @if (!empty($ochdr->mcusmas->offad3))
                            {{ $ochdr->mcusmas->offad3 }} <br>
                        @endif
                        @if (!empty($ochdr->mcusmas->opost))
                            {{ $ochdr->mcusmas->opost }} <br>
                        @endif
                        <br>
                        Telp: {{ $ochdr->mcusmas->offph }} <br>
                        Attn. {{ $ochdr->mcusmas->ofcon }}
                    </td>
                </tr>
            </table>
        </td>

        <td class="left" style="width:26%; vertical-align:top">
            <b>DIKIRIM KE :</b><br>
            <table class="no-border" style="margin-left: 5px; overflow: wrap;">
                <tr>
                    <td>
                        {{ $delto->shpnm }} <br>
                        {{ $delto->deliveryaddress }} <br>
                        <br>
                        Telp: {{ $delto->phone }} <br>
                        Attn. {{ $delto->contp }}
                    </td>
                </tr>
            </table>
        </td>

        <td style="width:5%"></td>

        <td class="left" style="width:13%; vertical-align:top">
            <b>NOMOR OC</b><br>
            <br>
            <b>TANGGAL OC</b> <br>
            <b>SALES REP</b> <br>
            <b>INDUSTRY</b> <br>
            <b>T O P</b> <br>
            <b>DISPOSISI EB</b> <br>
        </td>

        <td class="left" style="width:1%; vertical-align:top">
            <b>:</b> <br>
            <br>
            <b>:</b> <br>
            <b>:</b> <br>
            <b>:</b> <br>
            <b>:</b> <br>
            <b>:</b> <br>
        </td>

        <td class="left" style="width:20%; vertical-align:top">
            {{ $ochdr->braco }}-{{ $ochdr->formc }} {{ $ochdr->sorno }}<br>
            <br>
            {{ \Carbon\Carbon::parse($ochdr->sordt)->format('d-m-Y') }}<br>
            {{ $ochdr->msreno->srena }}<br>
            {{ $mcindu?->descr_cindu ?? '-' }}<br>
            {{ $ochdr->topay }} days<br>
            {{ $ochdr->nodeb }}<br>
        </td>
    </tr>
</table>

<table class="no-border" style="margin-top:10px;">
    <tr>
        <td class="left" style="width:33%">NPWP: {{ $ochdr->taxrn }}</td>
        <td class="center" style="width:33%">PO CUST: {{ $ochdr->cuspo }}</td>
        <td class="right" style="width:33%">Currency: {{ $ochdr->curco }}</td>
    </tr>
</table>

<!-- Detail Barang -->
<table style="margin-top:15px; overflow: wrap; flex:1">
    <thead>
        <tr>
            <th style="width: 5%">NO.</th>
            <th style="width: 13%">KODE PRODUK</th>
            <th style="width: 8%">BRAND</th>
            <th style="width: 28%">NAMA PRODUK</th>
            <th style="width: 11%">JUMLAH</th>
            <th style="width: 11%">HARGA SATUAN</th>
            <th style="width: 10%">DISCOUNT</th>
            <th style="width: 14%">GROSS AMOUNT</th>
        </tr>
    </thead>

    <tbody>
        @php
            $totalGross = 0;
            $totalDisc  = 0;
        @endphp

        @foreach($ochdr->ocdtls as $i => $d)
            @php
                $qty   = (float) $d->qtyor;
                $price = (float) $d->price;
                $odisp = (float) $d->odisp;

                $grossRow = $price * $qty;
                $discRow  = $odisp * $qty;
                $netRow   = ($price - $odisp) * $qty;

                $totalGross += $grossRow;
                $totalDisc  += $discRow;
            @endphp

            <tr>
                <td class="center">{{ $i + 1 }}.</td>
                <td class="center">{{ $d->opron }}</td>
                <td class="center">{{ $d->mpromas->brand_name }}</td>

                <td>
                    {{ $d->mpromas->prona ?? '-' }}

                    <table class="no-border" style="margin-left: 5px; overflow: wrap;">
                        @if ($d->srcog == 1)
                            <tr><td>Source of goods : Branch's Stock</td></tr>
                        @elseif ($d->srcog == 2)
                            <tr><td>Source of goods : Request to Head Office</td></tr>
                        @endif

                        <tr>
                            <td>
                                PL : {{ $ochdr->curco }}
                                {{ formatNumberOnly($d->plist, $ochdr->curco) }}
                                X {{ $d->qtyor }}
                                = {{ formatNumberOnly($d->plist * $d->qtyor, $ochdr->curco) }}
                            </td>
                        </tr>

                        @if(!empty($d->noted))
                            <tr><td>{{ $d->noted }}</td></tr>
                        @endif
                    </table>
                </td>

                <td class="center">{{ $d->qtyor }} {{ $d->mpromas->stdqu}}</td>
                <td class="right">{{ formatNumberOnly($price, $ochdr->curco) }}</td>

                {{-- DISCOUNT (nominal per unit sesuai data) --}}
                <td class="right">{{ formatNumberOnly($odisp, $ochdr->curco) }}</td>

                {{-- GROSS AMOUNT kolom kamu isinya NET per row (setelah diskon) --}}
                <td class="right">{{ formatNumberOnly($netRow, $ochdr->curco) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<htmlpagefooter name="myFooter">
    <div class="footer-summary">
        <div style="border-top:1px dashed #00000049; margin-bottom:10px; padding-top: 5px;"></div>

        @php
            $PPN_RATE  = $ochdr->mtaxes->taxes;
            $dpp = round($totalGross - $totalDisc, 2);
            $ppn       = round($dpp * ($PPN_RATE / 100), 2);
            $billing   = round($dpp + $ppn, 2);
            $dpAmount  = round($billing * ((float)$ochdr->dpper / 100), 2);
        @endphp

        <table class="no-border" style="margin-top:10px;">
            <tr>
                <td style="width:60%; vertical-align:top">
                    @if (!empty($ochdr->sqper))
                        <b>Split Quota : {{ $ochdr->sqper }}%, Branch : {{ $ochdr->sqtbr }}, Sales rep. : {{ $ochdr->sqtsr }}</b><br>
                    @endif

                    <b>Catatan:</b><br>
                    {{ $ochdr->noteh }}
                </td>

                <td style="width:40%">
                    <table class="no-border">
                        <tr>
                            <td>TOTAL GROSS</td>
                            <td class="right">{{ formatNumberOnly($totalGross, $ochdr->curco) }}</td>
                        </tr>
                        <tr>
                            <td>TOTAL DISCOUNT</td>
                            <td class="right">{{ $totalDisc != 0 ? '- ' . formatNumberOnly($totalDisc, $ochdr->curco) : '0' }}</td>
                        </tr>
                        <tr>
                            <td>DPP</td>
                            <td class="right">{{ formatNumberOnly($dpp, $ochdr->curco) }}</td>
                        </tr>
                        <tr>
                            <td>PPN {{ $PPN_RATE }}%</td>
                            <td class="right">{{ $ppn != 0 ? formatNumberOnly($ppn, $ochdr->curco) : '0' }}</td>
                        </tr>
                        <tr>
                            <td><b>BILLING AMOUNT</b></td>
                            <td class="right"><b>{{ formatNumberOnly($billing, $ochdr->curco) }}</b></td>
                        </tr>
                        <tr>
                            <td>DP {{ $ochdr->dpper }}%</td>
                            <td class="right">{{ formatNumberOnly($dpAmount, $ochdr->curco) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="no-border" style="width:100%; margin-top:40px;">
            <tr>
                @if (!empty($ochdr->mformcode?->pos1) || !empty($ochdr->mformcode?->name1))
                    <td class="center">{{ $ochdr->mformcode?->pos1 ?? '' }}</td>
                @endif

                @if(!empty($ochdr->mformcode?->pos2) || !empty($ochdr->mformcode?->name2))
                    <td class="center">{{ $ochdr->mformcode->pos2 }}</td>
                @endif

                @if(!empty($ochdr->mformcode?->pos3) || !empty($ochdr->mformcode?->name3))
                    <td class="center">{{ $ochdr->mformcode->pos3 }}</td>
                @endif

                @if(!empty($ochdr->mformcode?->pos4) || !empty($ochdr->mformcode?->name4))
                    <td class="center">{{ $ochdr->mformcode->pos4 }}</td>
                @endif
            </tr>

            <tr style="height:80px;">
                <td class="center" style="padding-top: 40px">&nbsp;</td>
                @if(!empty($ochdr->mformcode?->pos1) || !empty($ochdr->mformcode?->name1)) <td class="center"></td> @endif
                @if(!empty($ochdr->mformcode?->pos2) || !empty($ochdr->mformcode?->name2)) <td class="center"></td> @endif
                @if(!empty($ochdr->mformcode?->pos3) || !empty($ochdr->mformcode?->name3)) <td class="center"></td> @endif
                @if(!empty($ochdr->mformcode?->pos4) || !empty($ochdr->mformcode?->name4)) <td class="center"></td> @endif
            </tr>

            <tr>
                @if(!empty($ochdr->mformcode?->pos1))
                    <td class="center">( {{ trim($ochdr->mformcode?->name1 ?? '') !== '' ? $ochdr->mformcode->name1 : '....................' }} )</td>
                @endif

                @if(!empty($ochdr->mformcode?->pos2))
                    <td class="center">( {{ trim($ochdr->mformcode?->name2 ?? '') !== '' ? $ochdr->mformcode->name2 : '....................' }} )</td>
                @endif

                @if(!empty($ochdr->mformcode?->pos3))
                    <td class="center">( {{ trim($ochdr->mformcode?->name3 ?? '') !== '' ? $ochdr->mformcode->name3 : '....................' }} )</td>
                @endif

                @if(!empty($ochdr->mformcode?->pos4))
                    <td class="center">( {{ trim($ochdr->mformcode?->name4 ?? '') !== '' ? $ochdr->mformcode->name4 : '....................' }} )</td>
                @endif
            </tr>
        </table>

        <hr>

        <div style="font-size: 10px">{{ $ochdr->mformcode->docd1 }}</div>
        <div style="font-size: 10px">{{ $ochdr->mformcode->docd2 }}</div>
        <div style="font-size: 10px">{{ $ochdr->created_by }} / {{ date('d-m-Y') }} / {{ date('H:i:s') }} / {{ $ochdr->prctr }}</div>
        <div style="text-align: right; font-size: 9pt;">
            {PAGENO}/{nbpg}
        </div>
    </div>
</htmlpagefooter>

</body>
</html>