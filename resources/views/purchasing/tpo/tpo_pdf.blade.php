<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order {{$tpohdr->pono}}</title>
    <style>
        body {
            font-family: sans-serif; 
            font-size: 9pt;
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

        /* Bagian total + tanda tangan fixed di bawah */
        .footer-summary {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
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
<body>

    <!-- Header Perusahaan -->
    <table class="no-border">
        <tr>
            <td style="width:70%">
                <h2>PT. MUGI</h2>
                Jl. A. Yani Kav. 132-134, Tebet Barat, Tebet, Kota Adm. Jakarta Selatan, DKI Jakarta, 12810<br>
                Phone: (021) 8294315 / Fax: (021) 8294312 <br>
                NPWP: 0013 0857 0906 2000 
            </td>
        </tr>
        <br>
        <br>
        <tr>
            <td class="center" style="width:30%">
                <h1>PURCHASE ORDER</h1>
            </td>
        </tr>
    </table>

    <!-- Info Supplier & Penerima -->
    <table class="no-border" style="margin-top:10px;">
        <tr valign="top">
            <td class="left" style="width:25%">
                <b>Supplier:</b><br>
                {{ $tpohdr->supno }} - {{ $tpohdr->vendor->supna ?? '' }}<br>
                {{ $tpohdr->vendor->address ?? '' }}<br>
                {{ $tpohdr->vendor->city ?? '' }}
            </td>
            <td class="left" style="width:25%">
                <b>Supplier Contact:</b><br>
                Tel: {{ $tpohdr->vendor->phone ?? '' }}<br>
                Fax: {{ $tpohdr->vendor->fax ?? '' }}<br>
                Email: {{ $tpohdr->vendor->email ?? '' }}
            </td>
            <td class="left" style="width:25%">
                <b>Dikirim Ke:</b><br>
                {{ $tpohdr->delco }}
            </td>
            <td class="left" style="width: 25%">
                <b>Nama Penerima:</b><br>
                {{ $tpohdr->delnm }} <br>
                <b>Kontak Penerima:</b><br>
                {{ $tpohdr->dconp }}
            </td>
        </tr>
    </table>

    <!-- Info Nomor PO -->
    <table class="no-border" style="margin-top:10px;">
        <tr>
            <td class="left" style="width:25%">Nomor PO: {{ $tpohdr->pono }}</td>
            <td class="center" style="width:25%">Tanggal: {{ date('d-m-Y', strtotime($tpohdr->podat)) }}</td>
            <td class="center" style="width:25%">TOP: {{ $tpohdr->topay }} ({{ $tpohdr->tdesc }})</td>
            <td class="right" style="width:25%">Currency: {{ $tpohdr->curco }}</td>
        </tr>
    </table>

    <!-- Detail Barang (pakai garis) -->
    <table style="margin-top:15px; overflow: wrap;">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 10%">Quantity</th>
                <th style="width: 15%">Kode Barang</th>
                <th style="width: 35%">Nama Barang</th>
                <th style="width: 10%">Berat Barang</th>
                <th style="width: 10%">Harga</th>
                <th style="width: 15%">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($tpohdr->tpodtl as $i => $d)
                @php $subtotal = $d->poqty * $d->price; $total += $subtotal @endphp
                <tr>
                    <td class="center">{{ $i+1 }}</td>
                    <td class="center">{{ $d->poqty }} {{ $d->mpromas->stdqu}}</td>
                    <td>{{ $d->opron }}</td>
                    <td>
                        {{ $d->mpromas->prona ?? '-' }}
                        @if(!empty($d->noted))
                        <table class="no-border" style="margin-left: 5px; overflow: wrap;">
                            <tr><td>{{ $d->noted }}</td></tr>
                        </table>
                        @endif
                    </td>
                    <td class="center">{{ $d->berat ?? '-' }}</td>
                    <td class="right">{{ number_format($d->price, 2, ',', '.') }}</td>
                    <td class="right">{{ number_format($subtotal, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Ringkasan Total & Tanda Tangan (tanpa outline) -->
    <div class="footer-summary">
        <div style="border-top:1px dashed #00000049; margin-bottom:10px;"></div>
        <table class="no-border" style="margin-top:10px;">
            <tr>
                <td style="width:60%; vertical-align:top">
                    <b>Catatan:</b><br>
                    {{ $tpohdr->noteh }}
                </td>
                <td style="width:40%">
                    <table class="no-border">
                        <tr>
                            <td>Sub Total</td>
                            <td class="right">{{ number_format($total, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Diskon</td>
                            <td class="right">{{ $tpohdr->diper }} %</td>
                        </tr>
                        <tr>
                            <td>PPN</td>
                            <td class="right">{{ $tpohdr->vatax }} %</td>
                        </tr>
                        <tr>
                            <td>PPH</td>
                            <td class="right">{{ number_format(array_sum(array_column($tpohdr->tpodtl->toArray(), 'pphd')), 2, ',', '.') }} %</td>
                        </tr>
                        <tr>
                            <td><b>Grand Total</b></td>
                            <td class="right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="no-border" style="width:100%; margin-top:40px; text-align:center;">
            <tr>
                @if (!empty($tpohdr->formcode?->pos1) || !empty($tpohdr->formcode?->name1))
                    <td>{{ $tpohdr->formcode?->pos1 ?? '' }}</td>
                @endif

                @if(!empty($tpohdr->formcode?->pos2) || !empty($tpohdr->formcode?->name2))
                    <td>{{ $tpohdr->formcode->pos2 }}</td>
                @endif

                @if(!empty($tpohdr->formcode?->pos3) || !empty($tpohdr->formcode?->name3))
                    <td>{{ $tpohdr->formcode->pos3 }}</td>
                @endif

                @if(!empty($tpohdr->formcode?->pos4) || !empty($tpohdr->formcode?->name4))
                    <td>{{ $tpohdr->formcode->pos4 }}</td>
                @endif
            </tr>

            <tr style="height:80px;">
                <td style="padding-top: 40px">&nbsp;</td>
                @if(!empty($tpohdr->formcode?->pos1) || !empty($tpohdr->formcode?->name1)) <td></td> @endif
                @if(!empty($tpohdr->formcode?->pos2) || !empty($tpohdr->formcode?->name2)) <td></td> @endif
                @if(!empty($tpohdr->formcode?->pos3) || !empty($tpohdr->formcode?->name3)) <td></td> @endif
                @if(!empty($tpohdr->formcode?->pos4) || !empty($tpohdr->formcode?->name4)) <td></td> @endif
            </tr>

            <tr>
                @if(!empty($tpohdr->formcode?->pos1) || !empty($tpohdr->formcode?->name1))
                    <td>( {{ $tpohdr->formcode?->name1 ?? '....................' }} )</td>
                @endif

                @if(!empty($tpohdr->formcode?->pos2) || !empty($tpohdr->formcode?->name2))
                    <td>( {{ $tpohdr->formcode->name2 }} )</td>
                @endif

                @if(!empty($tpohdr->formcode?->pos3) || !empty($tpohdr->formcode?->name3))
                    <td>( {{ $tpohdr->formcode->name3 }} )</td>
                @endif

                @if(!empty($tpohdr->formcode?->pos4) || !empty($tpohdr->formcode?->name4))
                    <td>( {{ $tpohdr->formcode->name4 }} )</td>
                @endif
            </tr>
        </table>

        <hr>

        <div style="font-size: 10px">{{ $tpohdr->formcode?->docd ?? '' }}</div>
    </div>

</body>
</html>
