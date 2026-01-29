<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Stock Receipt Note {{ $bpbhdr->braco }}-{{$bpbhdr->formc}}{{$bpbhdr->trano}}</title>
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

    /* tanda tangan */
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

    .td-top {
        vertical-align: top;
        white-space: normal;
        word-wrap: break-word;
    }
</style>
</head>
    <body style="min-height:100vh; display:flex; flex-direction:column;">

    <table class="no-border">
        <tr>
            <td style="width:70%">
                <img width="20%" src="{{ URL::asset('img/logomugi.png'); }}" alt="logo"><br>
                MUGI GRIYA BUILDING 8th Floor, Jl. MT. Haryono Kav. 10<br>
                JAKARTA 12810<br>
                Phone : (62)21-8308415 Hunting Fax. : (62)21-8308422
            </td>
        </tr>
        <br>
        <br>
        <tr>
            <td class="center" style="width:30%">
                <h1>BON PERMINTAAN BARANG</h1>
            </td>
        </tr>
    </table>


    <!-- Info BPB -->
    <table class="no-border" style="margin-top:10px; width:100%; border-collapse: collapse;">
        <tr>
            <td style="width:7%; vertical-align:top;">
                KEPADA
            </td>
            <td style="width:2%; vertical-align:top;">
                :
            </td>
            <td style="width:25%; vertical-align:top;">
                {{ $bpbhdr->mbranch->conam }} <br>
                {{ $bpbhdr->mbranch->address }}
            </td>
            <td style="width:5%;"></td>
            <td style="width:33%; vertical-align:top;">
                DIKIRIM KE : <br>
            {{ $bpbhdr->mwarco->warna }}<br>
            {{ $bpbhdr->mwarco->address }}
            </td>
            <td style="width:3%;"></td>
            <td style="width:5%; vertical-align:top;">
                CABANG <br>
                NO. BPB <br>
                TGL BPB <br>
            </td>
            <td style="width:2%; vertical-align:top;">:<br>:<br>:</td>
            <td style="width:13%; vertical-align:top;">
                {{ $bpbhdr->braco }}<br>
                RA{{ $bpbhdr->sorno }}<br>
                {{ \Carbon\Carbon::parse($bpbhdr->reqdt)->format('d-m-Y') }}<br>
            </td>
        </tr>
    </table>
    <table class="no-border" style="width:100%; border-collapse: collapse;">
        <tr>
            <td style="width:7%; vertical-align:top;">
                UP
            </td>
            <td style="width:2%; vertical-align:top;">
                :
            </td>
            <td style="width:25%; vertical-align:top;">
                {{ $bpbhdr->reqtn }}
            </td>
            <td style="width:5%; vertical-align:top;"></td>
            <td style="width:8%; vertical-align:top;">
                KONTAK
            </td>
            <td style="width:2%; vertical-align:top;">
                :
            </td>
            <td style="width:16%; vertical-align:top;">
                {{ $bpbhdr->contp }}
            </td>
            <td></td>
        </tr>
    </table>
    <table class="no-border" style="margin-top:10px; width:100%; border-collapse: collapse;">
        <tr>
            <td style="width:33%; vertical-align:top;">
                DIMINTA UNTUK : {{ $bpbhdr->rqfor }}
            </td>
            <td style="width:25%"></td>
            <td style="width:33%" class="right">
                KIRIM VIA : DARAT / LAUT / UDARA
            </td>
        </tr>
    </table>
    <!-- Detail Invoice -->
    <table style="margin-top:5px; overflow: wrap; flex:1">
        <thead>
            <tr>
                <th style="width: 6%">NO.</th>
                <th style="width: 15%">KODE BARANG</th>
                <th style="width: 47%">NAMA BARANG</th>
                <th style="width: 14%">JUMLAH</th>
                <th style="width: 17%">PERKIRAAN DATANG</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bpbdtls as $i)
                <tr>
                    <td class="center">{{ $loop->index + 1 }}</td>
                    <td class="center">{{ $i->opron ?? '-' }}</td>
                    <td>
                        {{ $i->mpromas->prona ?? '-' }}
                        <br>
                        <br>
                        @if(!empty($i->aloka))
                        <table class="no-border" style="margin-left: 5px; overflow: wrap;">
                            <tr><td>{{ $i->aloka }}</td></tr>
                        </table>
                        @endif
                        <br>
                        @if(!empty($i->noted))
                        <table class="no-border" style="margin-left: 5px; overflow: wrap;">
                            <tr><td>{{ $i->noted }}</td></tr>
                        </table>
                        @endif
                    </td>
                    <td class="center">{{ $i->rqqty }} {{ $i->mpromas->stdqu }}</td>
                    <td class="center">{{ \Carbon\Carbon::parse($i->eariv)->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <htmlpagefooter name="myFooter">
        <!-- Tanda Tangan (tanpa outline) -->
        <div class="footer-summary">
            <div style="border-top:1px dashed #00000049; margin-bottom:10px; padding-top: 5px;"></div>

            <div>
                CATATAN :<br>
                {{ $bpbhdr->noteh }}
            </div>

            <table class="no-border" style="width:100%; margin-top:40px;">
                <tr>
                    @if (!empty($bpbhdr->mformcode?->pos1) || !empty($bpbhdr->mformcode?->name1))
                        <td class="center">{{ $bpbhdr->mformcode?->pos1 ?? '' }}</td>
                    @endif

                    @if(!empty($bpbhdr->mformcode?->pos2) || !empty($bpbhdr->mformcode?->name2))
                        <td class="center">{{ $bpbhdr->mformcode->pos2 }}</td>
                    @endif

                    @if(!empty($bpbhdr->mformcode?->pos3) || !empty($bpbhdr->mformcode?->name3))
                        <td class="center">{{ $bpbhdr->mformcode->pos3 }}</td>
                    @endif

                    @if(!empty($bpbhdr->mformcode?->pos4) || !empty($bpbhdr->mformcode?->name4))
                        <td class="center">{{ $bpbhdr->mformcode->pos4 }}</td>
                    @endif
                </tr>

                <tr style="height:80px;">
                    <td class="center" style="padding-top: 40px">&nbsp;</td>
                    @if(!empty($bpbhdr->mformcode?->pos1) || !empty($bpbhdr->mformcode?->name1)) <td class="center"></td> @endif
                    @if(!empty($bpbhdr->mformcode?->pos2) || !empty($bpbhdr->mformcode?->name2)) <td class="center"></td> @endif
                    @if(!empty($bpbhdr->mformcode?->pos3) || !empty($bpbhdr->mformcode?->name3)) <td class="center"></td> @endif
                    @if(!empty($bpbhdr->mformcode?->pos4) || !empty($bpbhdr->mformcode?->name4)) <td class="center"></td> @endif
                </tr>

                <tr>
                    @if(!empty($bpbhdr->mformcode?->pos1))
                        <td class="center">( {{ trim($bpbhdr->mformcode?->name1 ?? '') !== '' ? $bpbhdr->mformcode->name1 : '....................' }} )</td>
                    @endif

                    @if(!empty($bpbhdr->mformcode?->pos2))
                        <td class="center">( {{ trim($bpbhdr->mformcode?->name2 ?? '') !== '' ? $bpbhdr->mformcode->name2 : '....................' }} )</td>
                    @endif

                    @if(!empty($bpbhdr->mformcode?->pos3))
                        <td class="center">( {{ trim($bpbhdr->mformcode?->name3 ?? '') !== '' ? $bpbhdr->mformcode->name3 : '....................' }} )</td>
                    @endif

                    @if(!empty($bpbhdr->mformcode?->pos4))
                        <td class="center">( {{ trim($bpbhdr->mformcode?->name4 ?? '') !== '' ? $bpbhdr->mformcode->name4 : '....................' }} )</td>
                    @endif
                </tr>
            </table>

            <br>

            <div style="font-size: 10px">{{ $bpbhdr->mformcode->docd1 }}</div>
            <div style="font-size: 10px">{{ $bpbhdr->mformcode->docd2 }}</div>
            <div style="font-size: 10px">{{ $bpbhdr->created_by }} / {{ date('d-m-Y') }} / {{ date('H:i:s') }} / {{ $bpbhdr->prctr }}</div>
            <div style="text-align: right; font-size: 9pt;">
                {PAGENO}/{nbpg}
            </div>
        </div>
    </htmlpagefooter>

    </body>
</html>
