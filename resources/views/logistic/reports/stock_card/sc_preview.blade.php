<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>STOCK-CARD-{{ $opron }}</title>

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
        }

        .no-border td, .no-border th {
            border: none !important;
        }

        .right { text-align: right; }
        .center { text-align: center; }

        thead { display: table-header-group; }
    </style>
</head>
<body>

<htmlpageheader name="docHeader">
    <table class="no-border" style="margin-bottom:5px;">
        <tr>
            <td style="width:5%">
                PT. MUGI <br>
                BRANCH <br>
                PRODUCT <br>
            </td>
            <td style="width:2%">&nbsp;<br>:<br>:<br></td>
            <td style="width:25%">
                &nbsp;<br>
                {{ $braco }} <br>
                {{ $opron }} {{ $prona }} <br>
            </td>

            <td style="width:40%" class="center">
                <b>STOCK CARD</b><br>
                --------------------------------<br>
                Periode :
                {{ \Carbon\Carbon::parse($start)->format('d-m-Y') }} s/d 
                {{ \Carbon\Carbon::parse($end)->format('d-m-Y') }}
            </td>
            
            <td style="width: 10%"></td>

            <td style="width:5%">
                DATE <br>
                TIME <br>
                PAGE
            </td>
            <td style="width:2%">:<br>:<br>:</td>
            <td style="width:11%">
                {{ date('d-m-Y') }} <br>
                {{ date('H:i:s') }} <br>
                {PAGENO}
            </td>
        </tr>
    </table>
</htmlpageheader>

<sethtmlpageheader name="docHeader" value="on" show-this-page="1" />

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Document #</th>
            <th>Description</th>
            <th>Awal</th>
            <th>In</th>
            <th>Out</th>
            <th>Stock</th>
            <th>Serial #</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $r)
        <tr>
            <td>{{ \Carbon\Carbon::parse($r->date)->format('d-m-Y') }}</td>
            <td class="center">{{ $r->document }}</td>
            <td>{{ $r->description }}</td>
            <td></td>
            <td class="right">
                {{ $r->qty_in ? number_format($r->qty_in,0) : '' }}
            </td>
            <td class="right">
                {{ $r->qty_out ? number_format($r->qty_out,0) : '' }}
            </td>
            <td></td>
            <td class="left">
                {{ $r->lotno ?? '' }}
            </td>
        </tr>
        @endforeach

        <tr>
            <td colspan="3" class="right"><b>Total</b></td>
            <td class="right"><b>{{ number_format($stockAwal ,0) }}</b></td>
            <td class="right"><b>{{ number_format($totalIn ,0) }}</b></td>
            <td class="right"><b>{{ number_format($totalOut ,0) }}</b></td>
            <td class="right"><b>{{ number_format($stockAkhir ,0) }}</b></td>
            <td class="right"></td>
        </tr>
    </tbody>
</table>

</body>
</html>