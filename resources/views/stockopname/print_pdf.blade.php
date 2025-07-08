<!DOCTYPE html>
<html lang="en">
    <title>Asset Stock Opname & Adjustment Form</title>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .header, .content, .footer {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header .title {
            font-weight: bold;
            font-size: 12px;
        }
        .header table {
            width: 100%;
            margin-bottom: 10px;
        }
        .header table td {
            padding: 2px;
        }
        .content table, .footer table {
            width: 100%;
            border-collapse: collapse;
        }
        .content table th, .content table td, .footer table td {
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
        }
        .footer .signatures td {
            padding-top: 20px;
        }
        .signatures {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Agar kolom memiliki lebar tetap */
        }
        .signatures td {
            padding: 10px;
            vertical-align: top;
            text-align: center;
            font-size: 12px;
            border: 1px solid #ddd;
        }
        /* Baris footer tetap di dalam tabel yang sama, tanpa border */
        .footer-row td {
            padding: 5px;
            font-size: 12px;
            border: none; /* Menghilangkan border */
        }
        .header {
            margin: 20px;
        }

        .pt-left {
            text-align: left;
        }

        .doc-info {
            text-align: right;
        }

        .doc-info td {
            padding: 5px 0;
        }

        .asset-center {
            text-align: center;
            font-weight: bold;
            margin-top: 30px;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <!-- PT. Pesta Pora Abadi (Left Aligned) -->
        <div class="pt-left">
           <img src="{{ asset('assets\images\image-removebg-preview.png') }}" alt="logo_image" style="width:150px; height:80px; position:relative; right:3rem;">
        </div>
        <div class="pt-left">JL. S. Supriyadi No. 74, Kec. Sukun, Kota Malang</div>
        <div class="pt-left">Telp : (0341) 3018555</div>
    
        <!-- Document Info (Right Aligned) -->
        <div class="doc-info">Doc. Version - Ver. 01</div>
        <div class="doc-info">Doc. Date - 2024.09</div>
    
        <!-- Asset Movement Form (Centered) -->
        <div class="asset-center">Asset Stock Opname & Adjustment Form</div>
    </div>

    <!-- Content Section -->
    <div class="content">
    <table>
    <tr>
        <td style="background-color: grey; color:white">Origin Site</td>
        <td style="background-color: #B7B7B7">
        {{ $stockopnames->origin_site }}
        </td>
        <td style="background-color: grey; color:white">Purpose</td>
        <td style="background-color: #B7B7B7">STOCK OPNAME</td>
        <td style="background-color: grey; color:white">Request Date</td>
        <td style="background-color: #B7B7B7">{{ $stockopnames->create_date }}</td>
    </tr>
    <tr>
        <!-- <td style="background-color: grey; color:white">Destination Site</td>
        <td style="background-color: #B7B7B7">
        
        </td> -->
        <td style="background-color: grey; color:white">Approval</td>
        <td style="background-color: #B7B7B7">{{ $stockopnames->approval_name }}</td>
        <td colspan="2"></td>
        <td style="background-color: grey; color:white">Stock Opname Code</td>
        <td style="background-color: #B7B7B7">{{ $stockopnames->code }}</td>
    </tr>
    <tr>
        <td colspan="6" style="text-align: right; font-style: italic;">
             fill by Asset Management
        </td>
    </tr>
</table>


        <br>

        <table>
            <thead>
                <tr>
                    <th>Asset Tag</th>
                    <th>Asset Name</th>
                    <th>Description</th>
                    <th>Stock Opname Condition</th>
                </tr>
            </thead>
            @foreach ($tso_det as $item)
                <tbody>
                    <tr>
                        <td>{{ $item->asset_tag}}</td>
                        <td>{{ $item->asset_model}}</td>
                        <td>{{ $item->description}}</td>
                        <td>{{ $item->condition_name}}</td>
                        
                    </tr>
                </tbody>
            @endforeach
        </table>
    </div>

    <br>
    <br>
    <br>
    <!-- Footer Section -->
    <div class="footer mt-5">
        <table class="signatures">
            <tr>
                <td>Prepared by (Sender)
                    <br><br><br>
                    @if(!is_null($stockopnames->is_confirm))
                        Approve
                    @else
                        <!-- Not Yet -->
                    @endif
                    <br><br><br>Approve By System<br>Date<br>Store Manager
                </td>

                <td>Verified by
                    <br><br><br>
                    @if($stockopnames->is_confirm == 3)
                        Approve
                    @else
                        <!-- Not Yet -->
                    @endif
                    <br><br>
                    <br>Approve By System
                    <br>Date
                    <br>Asset Management
                </td>
            </tr>
            <tr class="footer-row" style="font-style: italic;">
                <td>Store Level</td>
                <td>Asset Division</td>
            </tr>
        </table>
    </div>
    <br>
</body>
</html>