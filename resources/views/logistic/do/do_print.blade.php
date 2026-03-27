<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Stock Transfer Note {{ $dohdr->braco }}-{{$dohdr->formc}}{{$dohdr->trano}}</title>
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

    <htmlpageheader name="docHeader">
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
                    <h1>Delivery Order</h1>
                </td>
            </tr>
        </table>


        <!-- Info TA -->
        <table class="no-border" style="margin-top:10px; width:100%; border-collapse: collapse;">
            <tr>
                <td style="width:34%; vertical-align:top;">
                    CUSTOMER : <br>
                    <table class="no-border" style="margin-left: 5px; overflow: wrap;">
                        <tr>
                            <td>
                                {{ $dohdr->mcusmas->title }} {{ $dohdr->mcusmas->cusna }} <br>
                                {{ $dohdr->mcusmas->offad }} <br>
                                {{ $dohdr->mcusmas->offad2 }}
                                {{ $dohdr->mcusmas->offad3 }}
                                {{ $dohdr->mcusmas->offad4 }}<br>
                                Attn. {{ $dohdr->mcusmas->ofcon }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:35%; vertical-align:top;">
                    DELIVERY ADDRESS :
                    <table class="no-border" style="margin-left: 5px; overflow: wrap;">
                        <tr>
                            <td>
                                {{ $dohdr->shipto->shpnm ?? '' }} <br>
                                {{ $dohdr->shipto->deliveryaddress ?? '' }} <br>
                                Attn. {{ $dohdr->shipto->contp ?? '' }}<br>
                                Phone. {{ $dohdr->shipto->phone ?? '' }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:15%; vertical-align:top;">
                    DO NO. <br>
                    DO DATE <br>
                    CUSTOMER PO. <br>
                    OC NO. <br>
                    EXPEDITER
                </td>
                <td style="width:1%; vertical-align:top;">
                    :<br>:<br>:<br>:<br>:
                </td>
                <td style="width:13%; vertical-align:top;">
                    {{ $dohdr->braco }}-DO{{ $dohdr->trano }}<br>
                    {{ \Carbon\Carbon::parse($dohdr->reqdt)->format('d-m-Y') }}<br>
                    {{ $dohdr->cuspo }}<br>
                    {{ $dohdr->braco }}-{{ $dohdr->rfc01 }} {{ $dohdr->ref01 }}<br>
                    {{ $dohdr->exped }}
                </td>
            </tr>
        </table>
    </htmlpageheader>

    <sethtmlpageheader name="docHeader" value="on" show-this-page="1" />

    <!-- Detail Invoice -->
    <table style="margin-top:5px; overflow: wrap; flex:1">
        <thead>
            <tr>
                <th style="width: 6%">NO.</th>
                <th style="width: 15%">PRODUCT NO.</th>
                <th style="width: 10%">BRAND</th>
                <th style="width: 47%">PRODUCT NAME</th>
                <th style="width: 11%">QUANTITY</th>
                <th style="width: 10%">UNIT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dodtls as $i)
                <tr>
                    <td class="center">{{ $loop->index + 1 }}</td>
                    <td class="center">{{ $i->opron ?? '-' }}</td>
                    <td class="center">{{ $i->mpromas->brand_name ?? '-' }}</td>
                    <td>
                        {{ $i->mpromas->prona ?? '-' }}
                        <br>
                        @if(!empty($i->noted))
                           C/W :{{ $i->noted }}
                           <br>
                        @endif
                        @if(!empty($i->lotno_merged))
                            S/N : {{ $i->lotno_merged }}
                        @endif
                    </td>
                    <td class="center">{{ $i->trqty }}</td>
                    <td class="center">{{ $i->mpromas->stdqu }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <htmlpagefooter name="myFooter">
        <!-- Tanda Tangan (tanpa outline) -->
        <div class="footer-summary">
            <div style="border-top:1px dashed #00000049; margin-bottom:10px; padding-top: 5px;"></div>

            <div>
                NOTES :<br>
                {{ $dohdr->noteh }}
            </div>

            <table class="no-border" style="width:100%; margin-top:40px;">
                <tr>
                    @if (!empty($dohdr->mformcode?->pos1) || !empty($dohdr->mformcode?->name1))
                        <td class="center">{{ $dohdr->mformcode?->pos1 ?? '' }}</td>
                    @endif

                    @if(!empty($dohdr->mformcode?->pos2) || !empty($dohdr->mformcode?->name2))
                        <td class="center">{{ $dohdr->mformcode->pos2 }}</td>
                    @endif

                    @if(!empty($dohdr->mformcode?->pos3) || !empty($dohdr->mformcode?->name3))
                        <td class="center">{{ $dohdr->mformcode->pos3 }}</td>
                    @endif

                    @if(!empty($dohdr->mformcode?->pos4) || !empty($dohdr->mformcode?->name4))
                        <td class="center">{{ $dohdr->mformcode->pos4 }}</td>
                    @endif
                </tr>

                <tr style="height:80px;">
                    <td class="center" style="padding-top: 40px">&nbsp;</td>
                    @if(!empty($dohdr->mformcode?->pos1) || !empty($dohdr->mformcode?->name1)) <td class="center"></td> @endif
                    @if(!empty($dohdr->mformcode?->pos2) || !empty($dohdr->mformcode?->name2)) <td class="center"></td> @endif
                    @if(!empty($dohdr->mformcode?->pos3) || !empty($dohdr->mformcode?->name3)) <td class="center"></td> @endif
                    @if(!empty($dohdr->mformcode?->pos4) || !empty($dohdr->mformcode?->name4)) <td class="center"></td> @endif
                </tr>

                <tr>
                    @if(!empty($dohdr->mformcode?->pos1))
                        <td class="center">( {{ trim($dohdr->mformcode?->name1 ?? '') !== '' ? $dohdr->mformcode->name1 : '....................' }} )</td>
                    @endif

                    @if(!empty($dohdr->mformcode?->pos2))
                        <td class="center">( {{ trim($dohdr->mformcode?->name2 ?? '') !== '' ? $dohdr->mformcode->name2 : '....................' }} )</td>
                    @endif

                    @if(!empty($dohdr->mformcode?->pos3))
                        <td class="center">( {{ trim($dohdr->mformcode?->name3 ?? '') !== '' ? $dohdr->mformcode->name3 : '....................' }} )</td>
                    @endif

                    @if(!empty($dohdr->mformcode?->pos4))
                        <td class="center">( {{ trim($dohdr->mformcode?->name4 ?? '') !== '' ? $dohdr->mformcode->name4 : '....................' }} )</td>
                    @endif
                </tr>
            </table>

            <br>

            <div style="font-size: 10px">{{ $dohdr->mformcode->docd1 }}</div>
            <div style="font-size: 10px">{{ $dohdr->mformcode->docd2 }}</div>
            <div style="font-size: 10px">{{ $dohdr->created_by }} / {{ date('d-m-Y') }} / {{ date('H:i:s') }} / {{ $dohdr->prctr }}</div>
            <div style="text-align: right; font-size: 9pt;">
                {PAGENO}/{nbpg}
            </div>
        </div>
    </htmlpagefooter>

    </body>
</html>
