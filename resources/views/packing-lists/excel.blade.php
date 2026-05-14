<table>

    <!-- COMPANY HEADER -->
    <tr>
        <td colspan="9"
            style="text-align:center;font-size:20px;font-weight:bold;border:1px solid black;">
            TOP COOPERATION TRADING CO., LIMITED
        </td>
    </tr>

    <tr>
        <td colspan="9"
            style="text-align:center;font-size:13px;font-weight:bold;border:1px solid black;">
            Room 1235,12F Wealth Mansion Building A, Yiwu, Zhejiang, China
        </td>
    </tr>

    <tr>
        <td colspan="9"
            style="text-align:center;font-size:13px;font-weight:bold;border:1px solid black;">
            TEL: 86-0579-85539970
            FAX: 86-0579-85539970
        </td>
    </tr>

    <tr>
        <td colspan="9"
            style="text-align:center;font-size:16px;font-weight:bold;border:1px solid black;">
            PACKING LIST
        </td>
    </tr>


    <!-- CLIENT + DATE -->

    <tr>

        <td colspan="6"
            rowspan="3"
            style="border:1px solid black;vertical-align:top;">

            <strong>TO:</strong>
            {{ $packingList->client->name }}

            <br><br>

            <strong>ADD:</strong>

            {{ $packingList->client->address }}

            <br><br>

            <strong>TEL:</strong>

            {{ $packingList->client->phone }}

        </td>


        <td colspan="3"
            rowspan="3"
            style="border:1px solid black;vertical-align:top;">

            <strong>Date:</strong>

            {{ $packingList->date }}

            <br><br>

            <strong>Container No:</strong>

            {{ $packingList->container_no }}

        </td>

    </tr>

    <tr></tr>
    <tr></tr>


    <!-- PORTS -->

    <tr>

        <td colspan="6"
            style="border:1px solid black">

            <strong>PORT OF LOADING:</strong>

            {{ $packingList->port_of_loading }}

        </td>

        <td colspan="3"
            style="border:1px solid black">

            <strong>Seal No:</strong>

            {{ $packingList->seal_no }}

        </td>

    </tr>

    <tr>

        <td colspan="6"
            style="border:1px solid black">

            <strong>PORT OF DISCHARGE:</strong>

            {{ $packingList->port_of_discharge }}

        </td>

        <td colspan="3"
            style="border:1px solid black">
        </td>

    </tr>


    <!-- TABLE HEADER -->

    <tr style="font-weight:bold;text-align:center;">

        <td style="border:1px solid black;width:15px">
            ITEM NO
        </td>

        <td style="border:1px solid black;width:25px">
            PICTURE
        </td>

        <td style="border:1px solid black;width:40px">
            DESCRIPTION
        </td>

        <td style="border:1px solid black">
            CTN
        </td>

        <td style="border:1px solid black">
            PCS/CTS
        </td>

        <td style="border:1px solid black">
            QTY
        </td>

        <td style="border:1px solid black">
            TOTAL CBM
        </td>

        <td style="border:1px solid black">
            TOTAL G.W
        </td>

        <td style="border:1px solid black">
            TOTAL N.W
        </td>

    </tr>



    @php

        $totalCtn = 0;
        $totalCbm = 0;
        $totalGw = 0;
        $totalNw = 0;

    @endphp



    @foreach($packingList->products as $product)

        @php

            $qty = $product->pivot->ctn * $product->pcs_cts;

            $rowCbm = $product->pivot->ctn * $product->unit_cbm;

            $rowGw = $product->pivot->ctn * $product->unit_gw;

            $rowNw = $product->pivot->ctn * $product->unit_nw;

            $totalCtn += $product->pivot->ctn;

            $totalCbm += $rowCbm;

            $totalGw += $rowGw;

            $totalNw += $rowNw;

        @endphp


        <tr style="height:80px;text-align:center;vertical-align:middle;">

            <td style="border:1px solid black">

                {{ $product->reference }}

            </td>


            <td style="border:1px solid black">

                @if($product->image)

                    <img
                        src="{{ public_path('storage/'.$product->image) }}"
                        width="60"
                        height="60">

                @endif

            </td>


            <td style="border:1px solid black">

                {{ strtoupper($product->description) }}

            </td>


            <td style="border:1px solid black">

                {{ $product->pivot->ctn }}

            </td>

            <td style="border:1px solid black">

                {{ $product->pcs_cts }}

            </td>

            <td style="border:1px solid black">

                =D{{ $loop->iteration + 10 }}*E{{ $loop->iteration + 10 }}

            </td>

            <td style="border:1px solid black">

                {{ number_format($rowCbm,3) }}

            </td>

            <td style="border:1px solid black">

                {{ number_format($rowGw,2) }}

            </td>

            <td style="border:1px solid black">

                {{ number_format($rowNw,2) }}

            </td>

        </tr>

    @endforeach

    <!-- TOTAL -->

    @php
        $startRow = 11;
        $endRow = 10 + count($packingList->products);
    @endphp

    <tr style="font-weight:bold;text-align:center;">

        <td colspan="3"
            style="border:1px solid black">

            TOTAL

        </td>

        <!-- CTN column D -->
        <td style="border:1px solid black">

            =SUM(D{{ $startRow }}:D{{ $endRow }})

        </td>

        <td style="border:1px solid black"></td>

        <td style="border:1px solid black"></td>

        <!-- TOTAL CBM column G -->
        <td style="border:1px solid black">

            =SUM(G{{ $startRow }}:G{{ $endRow }})

        </td>

        <!-- TOTAL G.W column H -->
        <td style="border:1px solid black">

            =SUM(H{{ $startRow }}:H{{ $endRow }})

        </td>

        <!-- TOTAL N.W column I -->
        <td style="border:1px solid black">

            =SUM(I{{ $startRow }}:I{{ $endRow }})

        </td>

    </tr>

    <!-- BOTTOM SUMMARY -->

    @php
        $startRow = 11;
        $endRow = 10 + count($packingList->products);
    @endphp


    <tr>

        <td colspan="2"
            style="border:1px solid black">
            Total Carton:
        </td>

        <td style="border:1px solid black">
            =SUM(D{{ $startRow }}:D{{ $endRow }})
        </td>

        <td colspan="6"></td>

    </tr>


    <tr>

        <td colspan="2"
            style="border:1px solid black">
            Gross Weight:
        </td>

        <td style="border:1px solid black">
            =SUM(H{{ $startRow }}:H{{ $endRow }})
        </td>

        <td colspan="6"></td>

    </tr>


    <tr>

        <td colspan="2"
            style="border:1px solid black">
            Net Weight:
        </td>

        <td style="border:1px solid black">
            =SUM(I{{ $startRow }}:I{{ $endRow }})
        </td>

        <td colspan="6"></td>

    </tr>


    <tr>

        <td colspan="2"
            style="border:1px solid black">
            CBM:
        </td>

        <td style="border:1px solid black">
            =SUM(G{{ $startRow }}:G{{ $endRow }})
        </td>

        <td colspan="6"></td>

    </tr>

</table>