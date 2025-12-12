<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Stock Transfer Note {{ $tahdr->braco }}-{{$tahdr->formc}}{{$tahdr->trano}}</title>
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
        padding: 1px; 
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
                <h1>STOCK TRANSFER NOTE</h1>
            </td>
        </tr>
    </table>


    <!-- Info TA -->
    <table class="no-border" style="margin-top:10px; width:100%; border-collapse: collapse;">
        <tr>
            <td style="width:34%; vertical-align:top;">
                TO : <br>
                <table class="no-border" style="margin-left: 5px; overflow: wrap;">
                    <tr>
                        <td>
                            {{ $tahdr->mbranch->conam }} <br>
                            {{ $tahdr->mbranch->address }} <br>
                            UP. {{ $tahdr->mbranch->contactp }}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:40%; vertical-align:top;"></td>
            <td style="width:10%; vertical-align:top;">
                WAREHOUSE <br>
                TN No. <br>
                TN DATE <br>
                <br>
                REQUEST NO. <br>
                EXPEDITER
            </td>
            <td style="width:1%; vertical-align:top;">
                :<br>:<br>:<br><br>:<br>:
            </td>
            <td style="width:13%; vertical-align:top;">
                {{ $tahdr->warco }}<br>
                TA{{ $tahdr->trano }}<br>
                {{ \Carbon\Carbon::parse($tahdr->reqdt)->format('d-m-Y') }}<br><br>
                {{ $tahdr->rfc01 }} {{ $tahdr->ref01 }}<br>
                {{ $tahdr->exped }}
            </td>
        </tr>
    </table>
    <!-- Detail Invoice -->
    <table style="margin-top:5px; overflow: wrap; flex:1">
        <thead>
            <tr>
                <th style="width: 6%">NO.</th>
                <th style="width: 15%">PRODUCT NO.</th>
                <th style="width: 47%">PRODUCT NAME</th>
                <th style="width: 14%">QUANTITY</th>
                <th style="width: 17%">LOCATION</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tadtls as $i)
                <tr>
                    <td class="center">{{ $loop->index + 1 }}</td>
                    <td class="center">{{ $i->opron ?? '-' }}</td>
                    <td>
                        {{ $i->mpromas->prona ?? '-' }}
                        <br>
                        @if(!empty($i->lotno))
                            S/N : {{ $i->lotno }}
                        @endif
                        <br>
                        @if(!empty($i->noted))
                        <table class="no-border" style="margin-left: 5px; overflow: wrap;">
                            <tr><td>{{ $i->noted }}</td></tr>
                        </table>
                        @endif
                    </td>
                    <td class="center">{{ $i->trqty }} {{ $i->mpromas->stdqu }}</td>
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
                REMARK :<br>
                {{ $tahdr->noteh }}
            </div>

            <table class="no-border" style="width:100%; margin-top:40px;">
                <tr>
                    @if (!empty($tahdr->mformcode?->pos1) || !empty($tahdr->mformcode?->name1))
                        <td class="center">{{ $tahdr->mformcode?->pos1 ?? '' }}</td>
                    @endif

                    @if(!empty($tahdr->mformcode?->pos2) || !empty($tahdr->mformcode?->name2))
                        <td class="center">{{ $tahdr->mformcode->pos2 }}</td>
                    @endif

                    @if(!empty($tahdr->mformcode?->pos3) || !empty($tahdr->mformcode?->name3))
                        <td class="center">{{ $tahdr->mformcode->pos3 }}</td>
                    @endif

                    @if(!empty($tahdr->mformcode?->pos4) || !empty($tahdr->mformcode?->name4))
                        <td class="center">{{ $tahdr->mformcode->pos4 }}</td>
                    @endif
                </tr>

                <tr style="height:80px;">
                    <td class="center" style="padding-top: 40px">&nbsp;</td>
                    @if(!empty($tahdr->mformcode?->pos1) || !empty($tahdr->mformcode?->name1)) <td class="center"></td> @endif
                    @if(!empty($tahdr->mformcode?->pos2) || !empty($tahdr->mformcode?->name2)) <td class="center"></td> @endif
                    @if(!empty($tahdr->mformcode?->pos3) || !empty($tahdr->mformcode?->name3)) <td class="center"></td> @endif
                    @if(!empty($tahdr->mformcode?->pos4) || !empty($tahdr->mformcode?->name4)) <td class="center"></td> @endif
                </tr>

                <tr>
                    @if(!empty($tahdr->mformcode?->pos1))
                        <td class="center">( {{ trim($tahdr->mformcode?->name1 ?? '') !== '' ? $tahdr->mformcode->name1 : '....................' }} )</td>
                    @endif

                    @if(!empty($tahdr->mformcode?->pos2))
                        <td class="center">( {{ trim($tahdr->mformcode?->name2 ?? '') !== '' ? $tahdr->mformcode->name2 : '....................' }} )</td>
                    @endif

                    @if(!empty($tahdr->mformcode?->pos3))
                        <td class="center">( {{ trim($tahdr->mformcode?->name3 ?? '') !== '' ? $tahdr->mformcode->name3 : '....................' }} )</td>
                    @endif

                    @if(!empty($tahdr->mformcode?->pos4))
                        <td class="center">( {{ trim($tahdr->mformcode?->name4 ?? '') !== '' ? $tahdr->mformcode->name4 : '....................' }} )</td>
                    @endif
                </tr>
            </table>

            <br>

            <div style="font-size: 10px">{{ $tahdr->mformcode->docd }}</div>
            <div style="font-size: 10px">{{ $tahdr->created_by }} / {{ date('d-m-Y') }} / {{ date('H:i:s') }} / {{ $tahdr->prctr }}</div>
            <div style="text-align: right; font-size: 9pt;">
                {PAGENO}/{nbpg}
            </div>
        </div>
    </htmlpagefooter>

    </body>
</html>
