<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Stock Receipt Note {{ $bbmhdr->braco }}-{{$bbmhdr->formc}}{{$bbmhdr->trano}}</title>
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
                {{ $bbmhdr->mbranches->address }}<br>
                Phone : {{ $bbmhdr->mbranches->phone }} Hunting Fax. : {{ $bbmhdr->mbranches->faxno }}
            </td>
        </tr>
        <br>
        <br>
        <tr>
            <td class="center" style="width:30%">
                <h1>STOCK RECEIPT NOTE</h1>
            </td>
        </tr>
    </table>


    <!-- Info BBM -->
    <table class="no-border" style="margin-top:10px;">
        <tr>
            <td class="left td-top" style="width:33%">
                RECEIVED FROM :<br>
                <table class="no-border" style="margin-left: 5px; overflow: wrap;">
                    <tr>
                        <td>
                            {{ $bbmhdr->mcusmas->cusna }},<br>
                            {{ $bbmhdr->mcusmas->address }}<br>
                            {{ $bbmhdr->mcusmas->opost }}<br>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="left td-top" style="width:10%">
                SRN TYPE
            </td>
            <td class="left td-top" style="width:1%">
                :
            </td>
            <td class="left td-top" style="width:20%, whitespace:normal, word-wrap:break-word">
                {{ $bbmhdr->mformcode->descr }}
            </td>
            <td class="left td-top" style="width:2%"></td>
            <td class="left td-top" style="width:14%">
                BRANCH <br>
                WAREHOUSE <br>
                NO. <br>
                DATE <br>
                REFERENCE
            </td>
            <td class="center td-top" style="width:1%">
                : <br>
                : <br>
                : <br>
                : <br>
                :
            </td>
            <td class="left td-top" style="width:12%">
                {{ $bbmhdr->braco }}<br>
                {{ $bbmhdr->warco }}<br>
                {{ $bbmhdr->formc }} {{ $bbmhdr->trano }}<br>
                {{ \Carbon\Carbon::parse($bbmhdr->tradt)->format('d-m-Y') }}<br>
                {{ $bbmhdr->warco }}-{{ $bbmhdr->reffc }}-{{ $bbmhdr->refno }}<br>
            </td>
        </tr>
    </table>

    <!-- Detail Invoice -->
    <table style="margin-top:15px; overflow: wrap; flex:1">
        <thead>
            <tr>
                <th style="width: 6%">NO.</th>
                <th style="width: 42%">PRODUCT DESCRIPTION</th>
                <th style="width: 11%">QUANTITY</th>
                <th style="width: 15%">SERIAL NO.</th>
                <th style="width: 10%">LOCATION</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bbmdtls as $i)
                <tr>
                    <td class="center">{{ $loop->index + 1 }}.</td>
                    <td class="">{{ $i->opron ?? '-' }} - {{ $i->mpromas->prona ?? '-' }}</td>
                    <td class="center">{{ $i->trqty }} {{ $i->qunit }}</td>
                    <td class="center">{{ $i->lotno ?? '-' }}</td>
                    <td class="center">{{ $i->locco }}</td>
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
                {{ $bbmhdr->noteh }}
            </div>

            <table class="no-border" style="width:100%; margin-top:40px;">
                <tr>
                    @if (!empty($bbmhdr->mformcode?->pos1) || !empty($bbmhdr->mformcode?->name1))
                        <td class="center">{{ $bbmhdr->mformcode?->pos1 ?? '' }}</td>
                    @endif

                    @if(!empty($bbmhdr->mformcode?->pos2) || !empty($bbmhdr->mformcode?->name2))
                        <td class="center">{{ $bbmhdr->mformcode->pos2 }}</td>
                    @endif

                    @if(!empty($bbmhdr->mformcode?->pos3) || !empty($bbmhdr->mformcode?->name3))
                        <td class="center">{{ $bbmhdr->mformcode->pos3 }}</td>
                    @endif

                    @if(!empty($bbmhdr->mformcode?->pos4) || !empty($bbmhdr->mformcode?->name4))
                        <td class="center">{{ $bbmhdr->mformcode->pos4 }}</td>
                    @endif
                </tr>

                <tr style="height:80px;">
                    <td class="center" style="padding-top: 40px">&nbsp;</td>
                    @if(!empty($bbmhdr->mformcode?->pos1) || !empty($bbmhdr->mformcode?->name1)) <td class="center"></td> @endif
                    @if(!empty($bbmhdr->mformcode?->pos2) || !empty($bbmhdr->mformcode?->name2)) <td class="center"></td> @endif
                    @if(!empty($bbmhdr->mformcode?->pos3) || !empty($bbmhdr->mformcode?->name3)) <td class="center"></td> @endif
                    @if(!empty($bbmhdr->mformcode?->pos4) || !empty($bbmhdr->mformcode?->name4)) <td class="center"></td> @endif
                </tr>

                <tr>
                    @if(!empty($bbmhdr->mformcode?->pos1))
                        <td class="center">( {{ trim($bbmhdr->mformcode?->name1 ?? '') !== '' ? $bbmhdr->mformcode->name1 : '....................' }} )</td>
                    @endif

                    @if(!empty($bbmhdr->mformcode?->pos2))
                        <td class="center">( {{ trim($bbmhdr->mformcode?->name2 ?? '') !== '' ? $bbmhdr->mformcode->name2 : '....................' }} )</td>
                    @endif

                    @if(!empty($bbmhdr->mformcode?->pos3))
                        <td class="center">( {{ trim($bbmhdr->mformcode?->name3 ?? '') !== '' ? $bbmhdr->mformcode->name3 : '....................' }} )</td>
                    @endif

                    @if(!empty($bbmhdr->mformcode?->pos4))
                        <td class="center">( {{ trim($bbmhdr->mformcode?->name4 ?? '') !== '' ? $bbmhdr->mformcode->name4 : '....................' }} )</td>
                    @endif
                </tr>
            </table>

            <br>

            <div style="font-size: 10px">{{ $bbmhdr->mformcode->docd1 }}</div>
            <div style="font-size: 10px">{{ $bbmhdr->mformcode->docd2 }}</div>
            <div style="font-size: 10px">{{ $bbmhdr->created_by }} / {{ date('d-m-Y') }} / {{ date('H:i:s') }} / {{ $bbmhdr->prctr }}</div>
            <div style="text-align: right; font-size: 9pt;">
                {PAGENO}/{nbpg}
            </div>
        </div>
    </htmlpagefooter>

    </body>
</html>
