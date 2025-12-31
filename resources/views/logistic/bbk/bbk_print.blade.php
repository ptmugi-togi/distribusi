<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Stock Issue Note {{$bbkhdr->formc}}{{$bbkhdr->trano}}</title>
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
                {{ $bbkhdr->mbranch->address }}<br>
                Phone : {{ $bbkhdr->mbranch->phone }} Hunting Fax. : {{ $bbkhdr->mbranch->faxno }}
            </td>
        </tr>
        <br>
        <br>
        <tr>
            <td class="center" style="width:33%">
                <h1>STOCK ISSUE NOTE</h1>
            </td>
        </tr>
    </table>


    <!-- Info BBK -->
    <table class="no-border" style="margin-top:10px; width:100%; border-collapse: collapse;">
        <tr>
            <td style="width:10%; vertical-align:top;">
                ISSUE TO  <br>
                @if ($bbkhdr->formc == 'OB' || $bbkhdr->formc == 'OG')
                    REFERENCE <br>
                    KODE PROD
                @endif
                @if ($bbkhdr->formc == 'OE')
                    ADDRESS
                @endif
            </td>
            <td style="width:1%; vertical-align:top;">
                :<br>
                @if ($bbkhdr->formc == 'OB' || $bbkhdr->formc == 'OG')
                    :<br>
                    :</td>
                @endif
                @if ($bbkhdr->formc == 'OE')
                    :
                @endif
            <td style="width:23%; vertical-align:top;">
                {{ $bbkhdr->isutn }} <br>
                @if ($bbkhdr->formc == 'OB' || $bbkhdr->formc == 'OG')
                    {{ $bbkhdr->rfc01 }} {{ $bbkhdr->ref01 }} <br>
                    {{ $bbkhdr->kdprod }}
                @endif
                @if ($bbkhdr->formc == 'OE')
                    {{ $bbkhdr->isua1 }}
                @endif
            </td>
            <td class="center" style="width:30%; vertical-align:top;">
                SIN TYPE : {{ $bbkhdr->mformcode->desc_c }} <br>
                @if ($bbkhdr->formc == 'OE')
                    WC# : {{ $bbkhdr->ref01 }}    
                @endif
            </td>
            <td style="width: 10%"></td>
            <td style="width:10%; vertical-align:top;">
                BRANCH <br>
                WAREHOUSE <br>
                No. <br>
                @if ($bbkhdr->formc == 'OB' || $bbkhdr->formc == 'OA' || $bbkhdr->formc == 'OE')
                    DATE
                @else
                    TN DATE <br>
                @endif
            </td>
            <td style="width:1%; vertical-align:top;">
                :<br>:<br>:<br>:<br>
            </td>
            <td style="width:13%; vertical-align:top;">
                {{ $bbkhdr->braco }}<br>
                {{ $bbkhdr->warco }}<br>
                {{ $bbkhdr->formc }} {{ $bbkhdr->trano }}<br>
                {{ \Carbon\Carbon::parse($bbkhdr->reqdt)->format('d-m-Y') }}<br><br>
            </td>
        </tr>
    </table>
    <!-- Detail Invoice -->
    <table style="margin-top:5px; overflow: wrap; flex:1">
        <thead>
            <tr>
                <th style="width: 6%">NO.</th>
                <th style="width: 15%">PRODUCT NO.</th>
                @if ($bbkhdr->formc == 'OB')
                    <th style="width: 47%">PRODUCT NAME</th>
                @else
                    <th style="width: 47%">PRODUCT DESCRIPTION</th>
                @endif
                <th style="width: 14%">QUANTITY</th>
                @if ($bbkhdr->formc == 'OB')
                    <th style="width: 17%">NOTES</th>
                @else
                    <th style="width: 17%">LOCATION</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($bbkdtls as $i)
                <tr>
                    <td class="center">{{ $loop->index + 1 }}.</td>
                    <td class="center">{{ $i->opron ?? '-' }}</td>
                    <td>
                        {{ $i->mpromas->prona ?? '-' }}
                        <br>
                        @if ($bbkhdr->formc != 'OB')
                            @if(!empty($i->lotno))
                                S/N : {{ $i->lotno_merged }}
                            @endif
                            <br>
                            @if(!empty($i->noted))
                            <table class="no-border" style="margin-left: 5px; overflow: wrap;">
                                <tr><td>{{ $i->noted }}</td></tr>
                            </table>
                            @endif
                        @endif
                    </td>
                    <td class="center">{{ $i->trqty }} {{ $i->mpromas->stdqu }}</td>
                    @if ($bbkhdr->formc == 'OB')
                        <td class="center">{{ $i->noted }}</td>
                    @else
                        <td class="center">{{ $i->locco }}/{{ $i->mlocco->descr }}</td>
                    @endif
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
                {{ $bbkhdr->noteh }}
            </div>

            <table class="no-border" style="width:100%; margin-top:40px;">
                <tr>
                    @if (!empty($bbkhdr->mformcode?->pos1) || !empty($bbkhdr->mformcode?->name1))
                        <td class="center">{{ $bbkhdr->mformcode?->pos1 ?? '' }}</td>
                    @endif

                    @if(!empty($bbkhdr->mformcode?->pos2) || !empty($bbkhdr->mformcode?->name2))
                        <td class="center">{{ $bbkhdr->mformcode->pos2 }}</td>
                    @endif

                    @if(!empty($bbkhdr->mformcode?->pos3) || !empty($bbkhdr->mformcode?->name3))
                        <td class="center">{{ $bbkhdr->mformcode->pos3 }}</td>
                    @endif

                    @if(!empty($bbkhdr->mformcode?->pos4) || !empty($bbkhdr->mformcode?->name4))
                        <td class="center">{{ $bbkhdr->mformcode->pos4 }}</td>
                    @endif
                </tr>

                <tr style="height:80px;">
                    <td class="center" style="padding-top: 40px">&nbsp;</td>
                    @if(!empty($bbkhdr->mformcode?->pos1) || !empty($bbkhdr->mformcode?->name1)) <td class="center"></td> @endif
                    @if(!empty($bbkhdr->mformcode?->pos2) || !empty($bbkhdr->mformcode?->name2)) <td class="center"></td> @endif
                    @if(!empty($bbkhdr->mformcode?->pos3) || !empty($bbkhdr->mformcode?->name3)) <td class="center"></td> @endif
                    @if(!empty($bbkhdr->mformcode?->pos4) || !empty($bbkhdr->mformcode?->name4)) <td class="center"></td> @endif
                </tr>

                <tr>
                    @if(!empty($bbkhdr->mformcode?->pos1))
                        <td class="center">( {{ trim($bbkhdr->mformcode?->name1 ?? '') !== '' ? $bbkhdr->mformcode->name1 : '....................' }} )</td>
                    @endif

                    @if(!empty($bbkhdr->mformcode?->pos2))
                        <td class="center">( {{ trim($bbkhdr->mformcode?->name2 ?? '') !== '' ? $bbkhdr->mformcode->name2 : '....................' }} )</td>
                    @endif

                    @if(!empty($bbkhdr->mformcode?->pos3))
                        <td class="center">( {{ trim($bbkhdr->mformcode?->name3 ?? '') !== '' ? $bbkhdr->mformcode->name3 : '....................' }} )</td>
                    @endif

                    @if(!empty($bbkhdr->mformcode?->pos4))
                        <td class="center">( {{ trim($bbkhdr->mformcode?->name4 ?? '') !== '' ? $bbkhdr->mformcode->name4 : '....................' }} )</td>
                    @endif
                </tr>
            </table>

            <br>

            <div style="font-size: 10px">{{ $bbkhdr->mformcode->docd }}</div>
            <div style="font-size: 10px">{{ $bbkhdr->created_by }} / {{ date('d-m-Y') }} / {{ date('H:i:s') }} / {{ $bbkhdr->prctr }}</div>
            <div style="text-align: right; font-size: 9pt;">
                {PAGENO}/{nbpg}
            </div>
        </div>
    </htmlpagefooter>

    </body>
</html>
