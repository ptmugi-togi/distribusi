<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Purchase Order {{$tpohdr->pono}}</title>
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

    .summary-signature {
        page-break-inside: avoid;
        page-break-before: auto;
        page-break-after: auto;
    }
</style>
</head>
<body>

<!-- Header Perusahaan -->
<table class="no-border">
    <tr>
        <td style="width:70%">
            <img width="20%" src="{{ URL::asset('img/logomugi.png'); }}" alt="logo"><br>
            Jl.M.T.HARYONO KAV.10, TEBET, TEBET BARAT, TEBET, KOTA ADM.JAKARTA SELATAN, DKI JAKARTA, 12810<br>
            Phone: (62)21-8308415 / Fax: (62)21-8308422 <br>
            NPWP: 0013 0857 0906 2000 
        </td>
    </tr>
    <br>
    <br>
    <tr>
        <td class="center" style="width:30%">
            <h1><u>PURCHASE ORDER</u></h1>
            <h2>{{ $tpohdr->pono }}</h2>
        </td>
    </tr>
</table>

<!-- Info Supplier & Penerima -->
<table class="no-border" style="margin-top:10px;">
    <tr valign="top">
        <td class="left" style="width:50%">
            <b>Supplier:</b><br>
            {{ $tpohdr->supno }} - {{ $tpohdr->vendor->supna ?? '' }}<br>
            {{ $tpohdr->vendor->address ?? '' }}<br>
            {{ $tpohdr->vendor->city ?? '' }}<br>
            Tel: {{ $tpohdr->vendor->phone ?? '' }}<br>
            Fax: {{ $tpohdr->vendor->fax ?? '' }}<br>
            Email: {{ $tpohdr->vendor->email ?? '' }}<br>
        </td>
        <td class="left" style="width:50%">
            <b>Dikirim Ke:</b><br>
            {{ $tpohdr->branches->conam }}<br>
            {{ $tpohdr->branches->address }}<br>
            Tel: {{ $tpohdr->branches->phone }}<br>
            Fax: {{ $tpohdr->branches->faxno }}<br>
            Email: {{ $tpohdr->branches->email }}<br>
            ATTN: {{ $tpohdr->branches->contactp }}
        </td>
    </tr>
</table>

<!-- Info Nomor PO -->
<table class="no-border" style="margin-top:10px;">
    <tr>
        <td class="left" style="width:33%">Tanggal PO: {{ date('d-m-Y', strtotime($tpohdr->podat)) }}</td>
        <td class="center" style="width:33%">TOP: {{ $tpohdr->topay }} {{ $tpohdr->tdesc }}</td>
        <td class="right" style="width:33%">Currency: {{ $tpohdr->curco }}</td>
    </tr>
</table>

<!-- Detail Barang (pakai garis) -->
<table style="margin-top:15px; overflow: wrap;">
    <thead>
        <tr>
            <th style="width: 5%">No</th>
            <th style="width: 9%">Quantity</th>
            <th style="width: 40%">Nama Barang</th>
            <th style="width: 7%">Berat/Vol</th>
            <th style="width: 12%">Harga Satuan</th>
            <th style="width: 11%">Diskon Satuan</th>
            <th style="width: 15%">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @php $subtotal = 0; @endphp
        @php $total = 0; @endphp
        @php $pph = 0; @endphp
        @php $ppn = 0; @endphp
        @php $totalpph = 0; @endphp
        @foreach($tpohdr->tpodtl as $i => $d)
            @php
                if ($d->berat == 0) {
                    $jumlah = ($d->price - ($d->price * ($d->odisp / 100))) * $d->poqty;
                } else {
                    $jumlah = ($d->price - ($d->price * ($d->odisp / 100))) * $d->berat * $d->poqty;
                }
                $subtotal += $jumlah;
            @endphp
            @php $pph = $jumlah * ($d->pphd / 100); $totalpph += $pph @endphp
            <tr>
                <td class="center">{{ $i+1 }}</td>
                <td class="center">{{ $d->poqty }} {{ $d->mpromas->stdqu}}</td>
                <td>
                    {{ $d->mpromas->prona ?? '-' }}
                    @if(!empty($d->noted))
                    <table class="no-border" style="margin-left: 5px; overflow: wrap;">
                        <tr><td>{{ $d->noted }}</td></tr>
                    </table>
                    @endif
                </td>
                <td class="center">{{ $d->berat ?? '-' }}</td>
                <td class="right">{{ formatNumberOnly($d->price, $tpohdr->curco) }}</td>
                <td class="right">{{ formatNumberOnly($d->price * ($d->odisp / 100), $tpohdr->curco) }}</td>
                <td class="right">{{ formatNumberOnly($jumlah, $tpohdr->curco) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Ringkasan Total & Tanda Tangan (tanpa outline) -->
<div class="footer-summary summary-signature">
    <div style="border-top:1px dashed #00000049; margin-bottom:10px;"></div>
    <table class="no-border" style="margin-top:10px;">
        <tr>
            {{-- itungan harga --}}
            @php $diskon     = round($subtotal * ($tpohdr->diper / 100), 2); @endphp
            @php $ppn        = round(($subtotal - $diskon) * ($tpohdr->vatax / 100), 2); @endphp
            @php $netamount  = round($subtotal - $diskon, 2); @endphp
            @php $grandtotal = round($subtotal - $diskon + $ppn - $totalpph, 2); @endphp

            <td style="width:60%; vertical-align:top">
                <b>Catatan:</b><br>
                {{ $tpohdr->noteh }}
            </td>
            <td style="width:40%">
                <table class="no-border">
                    <tr>
                        <td>Sub Total</td>
                        <td class="right">{{ formatNumberOnly($subtotal, $tpohdr->curco) }}</td>
                    </tr>
                    <tr>
                        <td>Diskon</td>
                        <td class="right">{{ $diskon != 0 ? '- ' . formatNumberOnly($diskon, $tpohdr->curco) : '0' }}</td>
                    </tr>
                    <tr>
                        <td>Net Amount</td>
                        <td class="right">{{ formatNumberOnly($netamount, $tpohdr->curco) ?? '0' }}</td>
                    </tr>
                    <tr>
                        <td>PPN</td>
                        <td class="right">{{ $ppn != 0 ? '' . formatNumberOnly($ppn, $tpohdr->curco) : '0' }}</td>
                    </tr>
                    <tr>
                        <td>PPH</td>
                        <td class="right">{{ $totalpph != 0 ? '- ' . formatNumberOnly($totalpph, $tpohdr->curco) : '0' }}</td>
                    </tr>
                    <tr>
                        <td><b>Grand Total</b></td>
                        <td class="right"><b>{{ formatNumberOnly($grandtotal, $tpohdr->curco) }}</b></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="no-border" style="width:100%; margin-top:40px;">
        <tr>
            @if (!empty($tpohdr->formcode?->pos1) || !empty($tpohdr->formcode?->name1))
                <td class="center">{{ $tpohdr->formcode?->pos1 ?? '' }}</td>
            @endif

            @if(!empty($tpohdr->formcode?->pos2) || !empty($tpohdr->formcode?->name2))
                <td class="center">{{ $tpohdr->formcode->pos2 }}</td>
            @endif

            @if(!empty($tpohdr->formcode?->pos3) || !empty($tpohdr->formcode?->name3))
                <td class="center">{{ $tpohdr->formcode->pos3 }}</td>
            @endif

            @if(!empty($tpohdr->formcode?->pos4) || !empty($tpohdr->formcode?->name4))
                <td class="center">{{ $tpohdr->formcode->pos4 }}</td>
            @endif
        </tr>

        <tr style="height:80px;">
            <td class="center" style="padding-top: 40px">&nbsp;</td>
            @if(!empty($tpohdr->formcode?->pos1) || !empty($tpohdr->formcode?->name1)) <td class="center"></td> @endif
            @if(!empty($tpohdr->formcode?->pos2) || !empty($tpohdr->formcode?->name2)) <td class="center"></td> @endif
            @if(!empty($tpohdr->formcode?->pos3) || !empty($tpohdr->formcode?->name3)) <td class="center"></td> @endif
            @if(!empty($tpohdr->formcode?->pos4) || !empty($tpohdr->formcode?->name4)) <td class="center"></td> @endif
        </tr>

        <tr>
            @if(!empty($tpohdr->formcode?->pos1) || !empty($tpohdr->formcode?->name1))
                <td class="center">( {{ $tpohdr->formcode?->name1 ?? '....................' }} )</td>
            @endif

            @if(!empty($tpohdr->formcode?->pos2) || !empty($tpohdr->formcode?->name2))
                <td class="center">( {{ $tpohdr->formcode->name2 }} )</td>
            @endif

            @if(!empty($tpohdr->formcode?->pos3) || !empty($tpohdr->formcode?->name3))
                <td class="center">( {{ $tpohdr->formcode->name3 }} )</td>
            @endif

            @if(!empty($tpohdr->formcode?->pos4) || !empty($tpohdr->formcode?->name4))
                <td class="center">( {{ $tpohdr->formcode->name4 }} )</td>
            @endif
        </tr>
        <tr>
            @if(!empty($tpohdr->formcode?->pos1) || !empty($tpohdr->formcode?->name1))
                <td class="left">TGL:</td>
            @endif

            @if(!empty($tpohdr->formcode?->pos2) || !empty($tpohdr->formcode?->name2))
                <td class="left">TGL:</td>
            @endif

            @if(!empty($tpohdr->formcode?->pos3) || !empty($tpohdr->formcode?->name3))
                <td class="left">TGL:</td>
            @endif

            @if(!empty($tpohdr->formcode?->pos4) || !empty($tpohdr->formcode?->name4))
                <td class="left">TGL:</td>
            @endif
        </tr>
    </table>

    <hr>

    <div style="font-size: 10px">{{ $tpohdr->formcode?->docd ?? '' }}</div>
</div>

</body>
</html>
