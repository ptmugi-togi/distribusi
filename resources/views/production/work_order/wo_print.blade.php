<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>WORK ORDER {{ $wohdr->braco }}-{{$wohdr->formc}}{{$wohdr->wonum}}</title>
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
                <h1>WORK ORDER</h1>
            </td>
        </tr>
    </table>


    <!-- Info WO -->
    <table class="no-border" style="margin-top:10px; width:100%; border-collapse: collapse;">
        <tr>
            <td style="width:34%; vertical-align:top;">
                KEPADA : <br>
                {{ $wohdr->mbranch->conam }} <br>
                
                UP : {{ $wohdr->mbranch->contactp }}<br>
                CC : {{ $wohdr->mbranch->contactp2 }}<br>

            </td>
            <td style="width:30%; vertical-align:top;"></td>
            <td style="width:16%; vertical-align:top;">
                WORK ORDER NO. <br>
                WORK ORDER DATE <br>
                OC NO / SRN <br>
                REQUEST BY <br>
                REQUEST DATE <br>
            </td>
            <td style="width:2%; vertical-align:top;">:<br>:<br>:<br>:<br>:</td>
            <td style="width:13%; vertical-align:top;">
                {{ $wohdr->formc }} {{ $wohdr->wonum }}<br>
                {{ $wohdr->wodat }}<br>
                {{ $wohdr->sorno }}<br>
                {{ $wohdr->reqby }}<br>
                {{ \Carbon\Carbon::parse($wohdr->reqdt)->format('d-m-Y') }}<br>
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
                <th style="width: 17%">TANGGAL PENYERAHAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wodtls as $i)
                <tr>
                    <td class="center">{{ $loop->index + 1 }}.</td>
                    <td class="center">{{ $i->outpr ?? '-' }}</td>
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
                    <td class="center">{{ $i->outqt }} {{ $i->mpromas->stdqu }}</td>
                    <td class="center">{{ \Carbon\Carbon::parse($i->fdate)->format('d-m-Y') }}</td>
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
                {{ $wohdr->noteh }}
            </div>

            <table class="no-border" style="width:100%; margin-top:40px;">
                <tr>
                    @if (!empty($wohdr->mformcode?->pos1) || !empty($wohdr->mformcode?->name1))
                        <td class="center">{{ $wohdr->mformcode?->pos1 ?? '' }}</td>
                    @endif

                    @if(!empty($wohdr->mformcode?->pos2) || !empty($wohdr->mformcode?->name2))
                        <td class="center">{{ $wohdr->mformcode->pos2 }}</td>
                    @endif

                    @if(!empty($wohdr->mformcode?->pos3) || !empty($wohdr->mformcode?->name3))
                        <td class="center">{{ $wohdr->mformcode->pos3 }}</td>
                    @endif

                    @if(!empty($wohdr->mformcode?->pos4) || !empty($wohdr->mformcode?->name4))
                        <td class="center">{{ $wohdr->mformcode->pos4 }}</td>
                    @endif
                </tr>

                <tr style="height:80px;">
                    <td class="center" style="padding-top: 40px">&nbsp;</td>
                    @if(!empty($wohdr->mformcode?->pos1) || !empty($wohdr->mformcode?->name1)) <td class="center"></td> @endif
                    @if(!empty($wohdr->mformcode?->pos2) || !empty($wohdr->mformcode?->name2)) <td class="center"></td> @endif
                    @if(!empty($wohdr->mformcode?->pos3) || !empty($wohdr->mformcode?->name3)) <td class="center"></td> @endif
                    @if(!empty($wohdr->mformcode?->pos4) || !empty($wohdr->mformcode?->name4)) <td class="center"></td> @endif
                </tr>

                <tr>
                    @if(!empty($wohdr->mformcode?->pos1))
                        <td class="center">( {{ trim($wohdr->mformcode?->name1 ?? '') !== '' ? $wohdr->mformcode->name1 : '....................' }} )</td>
                    @endif

                    @if(!empty($wohdr->mformcode?->pos2))
                        <td class="center">( {{ trim($wohdr->mformcode?->name2 ?? '') !== '' ? $wohdr->mformcode->name2 : '....................' }} )</td>
                    @endif

                    @if(!empty($wohdr->mformcode?->pos3))
                        <td class="center">( {{ trim($wohdr->mformcode?->name3 ?? '') !== '' ? $wohdr->mformcode->name3 : '....................' }} )</td>
                    @endif

                    @if(!empty($wohdr->mformcode?->pos4))
                        <td class="center">( {{ trim($wohdr->mformcode?->name4 ?? '') !== '' ? $wohdr->mformcode->name4 : '....................' }} )</td>
                    @endif
                </tr>
                <tr>
                    @if(!empty($wohdr->mformcode?->pos1))
                        <td>Tgl:</td>
                    @endif

                    @if(!empty($wohdr->mformcode?->pos2))
                        <td>Tgl:</td>
                    @endif

                    @if(!empty($wohdr->mformcode?->pos3))
                        <td>Tgl:</td>
                    @endif

                    @if(!empty($wohdr->mformcode?->pos4))
                        <td>Tgl:</td>
                    @endif
                </tr>
            </table>

            <br>

            <div style="font-size: 10px">{{ $wohdr->mformcode->docd }}</div>
            <div style="font-size: 10px">{{ $wohdr->created_by }} / {{ date('d-m-Y') }} / {{ date('H:i:s') }} / {{ $wohdr->prctr }}</div>
            <div style="text-align: right; font-size: 9pt;">
                {PAGENO}/{nbpg}
            </div>
        </div>
    </htmlpagefooter>

    </body>
</html>
