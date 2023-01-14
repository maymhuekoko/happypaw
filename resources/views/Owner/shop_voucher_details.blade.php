@extends('master')

@section('title', 'Voucher Details')

@section('place')

@endsection

@section('content')

    <style>
        td {

            text-align: left;
            font-size: 20px;
            font-weight: bold;
            overflow: hidden;
            white-space: nowrap;
        }

        th {
            text-align: left;
            font-size: 15px;
        }

        h6 {
            font-size: 15px;
            font-weight: 600;
        }

        .btn {
            width: 130px;
            overflow: hidden;
            white-space: nowrap;
        }

    </style>



@php
    $from_id = session()->get('from');
@endphp

<input type="hidden" id="from_id" value="{{$from_id}}">
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-7">
                    <ul class="nav nav-pills m-t-30 m-b-30">
                        <li class="nav-item">
                            <a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">
                                SLIP
                            </a>
                        </li>
                        <li class=" nav-item">
                            <a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false">
                                A5
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-content br-n pn">
        <div id="navpills-1" class="tab-pane active">
            <div class="row justify-content-center">
                <div class="col-md-8 printableArea" style="width:45%;">
                    <div class="card card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="text-center">
                                    <address>
                                        <address>
                                            <h5> &nbsp;<b
                                                    class="text-center text-black">HAPPY PAWS</b>
                                            </h5>
                                            <h6 class="text-black">(Pet Accesories)</h6>
                                            <h6 class="text-black">R668+P89, Nawarat Pat St, Thaketa Township, Yangon.
                                            </h6>
                                            <h6 class="text-black"><i
                                                    class="fas fa-mobile-alt"></i> 09-420022490 ,
                                                    09-444345502</h6>
                                            <h6 class="text-black">No.27(E), Padauk street , Amhudan Quarter, Thanlyn Township,
                                            </h6>
                                            <h6 class="text-black"><i
                                                    class="fas fa-mobile-alt"></i>09-791 164892 , 09-955132320</h6>
                                        </address>
                                    </address>
                                </div>
                                <div class="pull-right text-left">
                                    <h6 class="text-black">Date : <i class="fa fa-calendar"></i> </h6>
                                    <h6 class="text-black">Voucher Number : {{ $unit->voucher_code }} </h6>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="table-responsive text-black" style="clear: both;">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr class="text-black">
                                                <th>No.</th>
                                                <th>Name</th>
                                                <th>Qty*Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-black" id="slip_live">
                                            @php
                                                $j=1;
                                            @endphp
                                            @foreach ($unit->shop_counting_unit as  $key => $countingunit)
                                            @php
                                                if($countingunit->pivot->discount){
                                                    $price_wif_discount = $countingunit->pivot->price - (int)$countingunit->pivot->discount;
                                                }else{
                                                    $price_wif_discount = $countingunit->pivot->price;

                                                }
                                            @endphp
                                            <tr>
                                                <td style="font-size:15px;">{{$j++}}</td>
                                                <td style="font-size:15px;">{{ $countingunit->unit_name }}</td>
                                                <td style="font-size:15px;">{{ $countingunit->pivot->quantity }} * {{ $price_wif_discount }}</td>
                                                <td style="font-size:15px;" id="subtotal">{{ $countingunit->pivot->quantity *  $price_wif_discount}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="text-black">
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right" style="font-size:18px;">Total</td>
                                                <td id="total_charges" class="font-weight-bold" style="font-size:18px;">
                                                    <span id="slip_total"></span>{{$unit->total_price}}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right" style="font-size:18px;">Pay</td>
                                                <td id="pay" style="font-size:18px;">{{$unit->pay}}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right" style="font-size:18px;">Change</td>
                                                <td id="changes" style="font-size:18px;">{{$unit->change}}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <h6 class="text-center font-weight-bold text-black">**ကျေးဇူးတင်ပါသည်***</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="navpills-2" class="tab-pane ">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <?php

                    $countitem = count($unit->shop_counting_unit);

                    if ($countitem <= 12) {
                        $z = 1;
                    }
                    if ($countitem > 12 && $countitem < 31) {
                        $z = 2;
                    }
                    if ($countitem > 30 && $countitem < 49) {
                        $z = 3;
                    }
                    if ($countitem > 48) {
                        $z = 4;
                    }

                    $i = 1;
                    ?>
                    @for ($j = 1; $j <= $z; $j++)
                        <?php
                                if ($j == 1) {
                                    if ($countitem < 13) {
                                ?>
                        <div class="card card-body printableArea">
                            <div style="display:flex;justify-content:space-around">
                                <div class="ml-5">
                                    <img src="{{ asset('assets/img/happypaw.jpg') }}" width="150" height="180">
                                </div>

                                <div>
                                    <h3 class="mt-1 text-center"> &nbsp;<b
                                        style="font-size: 28px;">HAPPY PAWS</b><span>(Pet Accessories)</span>
                                </h3>

                                <p class="mt-2 ml-3 text-center" style="font-size: 15px;">R668+P89, Nawarat Pat St,Thaketa Township၊ Yangon.
                                    <br /><i class="fas fa-mobile-alt" style="margin-left: 50px;"></i>09-420022490 ,
                                        09-444345502
                                    <br />
                                    &nbsp;No.27(E), Padauk street , Amhudan Quarter, Thanlyn Township,
                                    <br /><i class="fas fa-mobile-alt" style="margin-left: 50px;"></i> 09-791 164892 , 09-955132320
                                </p>
                                </div>

                                <div></div>
                            </div>
                            <div class="row">

                                <div class="col-md-8">

                                    <h3 class=" mt-3" style="font-size : 20px;"><b>Invoice Number :
                                        </b> {{ $unit->voucher_code }} </h3>

                                    <h3 class=" mt-2" style="font-size : 20px;"><b>Invoice Date :</b> {{ date('d-m-Y', strtotime($unit->voucher_date)) }}
                                    </h3>
                                </div>
                                <div class="col-md-4">
                                    <h3 class=" mt-2" style="font-size : 20px;"><b>Phone :</b> {{$unit->sale_customer->phone }}
                                    </h3>
                                    <h3 class=" mt-3" style="font-size : 20px;"><b>Customer Name :
                                        </b> {{ $unit->sales_customer_name }} </h3>

                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-12">
                                    <table style="width: 100%; ">
                                        <thead class="text-center">
                                            <tr>
                                                <th
                                                    style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                                    Number</th>
                                                <th
                                                    style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                                    Item</th>
                                                <th
                                                    style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                                    Order Voucher Quantity</th>
                                                <th
                                                    style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                                    Price</th>
                                                <th
                                                    style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                                    Sub Total</th>

                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($unit->shop_counting_unit as $key => $countingunit)
                                                <tr>
                                                    <td
                                                        style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                        {{ $i++ }}</td>
                                                    <td
                                                        style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                        {{ $countingunit->unit_name }}
                                                    </td>
                                                    <td
                                                        style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                        {{ $countingunit->pivot->quantity }}</td>
                                                    <td
                                                        style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                        {{ $countingunit->pivot->price }}</td>
                                                    <td
                                                        style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                        {{ $countingunit->pivot->price * $countingunit->pivot->quantity }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="3"></td>
                                                <td style="font-size:20px;height: 8px; border: 2px solid black;">Total</td>
                                                <td style="font-size:20px;height: 8px; border: 2px solid black;">
                                                    <span id="total_charges_a5"></span>{{$unit->total_price}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <td style="font-size:20px;height: 8px; border: 2px solid black;">Pay</td>
                                                <td style="font-size:20px;height: 8px; border: 2px solid black;">
                                                    <span id="pay_1"></span>{{$unit->pay}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <td style="font-size:20px;height: 8px; border: 2px solid black;">Change</td>
                                                <td style="font-size:20px;height: 8px; border: 2px solid black;">
                                                    <span id="changes_1"></span>{{$unit->change}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- <div class="row mt-2 justify-content-around">

                                <div class="col-md-6">
                                    <h3 class=" font-weight-bold" style="font-size:20px;">
                                        Name - <span id="cus_name"> {{ $unit->sales_customer_name }} </span>
                                    </h3>
                                </div>
                                <div class="col-md-6">
                                    <h3 class=" font-weight-bold" style="font-size:20px;">
                                        Total - <span id="total_charges"> {{ $unit->total_price }} </span>
                                    </h3>
                                </div>
                            </div> --}}
                        </div>
                        <?php
                    }
                    else
                    {
                        $endpt = 14;
                    ?>
                        <div class="card card-body printableArea">
                            <div style="display:flex;justify-content:space-around">
                                <div>
                                    <img src="{{ asset('assets/img/happypaw.jpg') }}" width="100" height="150">
                                </div>
                                <div>
                                    <h3 class="mt-1 text-center"> &nbsp;<b
                                        style="font-size: 28px;">HAPPY PAWS</b><span>(Pet Accessories)</span>
                                </h3>

                                <p class="mt-2 ml-3 text-center" style="font-size: 15px;">R668+P89, Nawarat Pat St,Thaketa Township၊ Yangon.
                                    <br /><i class="fas fa-mobile-alt" style="margin-left: 50px;"></i>09-420022490 ,
                                        09-444345502
                                    <br />
                                    &nbsp;No.27(E), Padauk street , Amhudan Quarter, Thanlyn Township,
                                    <br /><i class="fas fa-mobile-alt" style="margin-left: 50px;"></i> 09-791 164892 , 09-955132320
                                </p>
                                </div>
                                <div></div>
                            </div>

                            <<div class="col-md-12">

                                <h3 class=" mt-3" style="font-size : 20px;"><b>@lang('lang.invoice')
                                        Number :</b> {{ $unit->voucher_code }} </h3>

                                <h3 class=" mt-2" style="font-size : 20px;"><b>@lang('lang.invoice')
                                        Date :</b> {{ date('d-m-Y', strtotime($unit->voucher_date)) }} </h3>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <table style="width: 100%; ">
                                    <thead class="text-center">
                                        <tr>
                                            <th
                                                style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                                Number</th>
                                            <th
                                                style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                                Item</th>
                                            <th
                                                style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                                Order Voucher Quantity</th>
                                            <th
                                                style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                                Price</th>
                                            <th
                                                style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                                Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <?php foreach ($unit->shop_counting_unit as  $key => $countingunit) {

                                        if ($key == $endpt) {
                                            break;
                                        }
                                            ?>
                                        <tr>
                                            <td
                                                style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                {{ $i++ }}</td>
                                            <td
                                                style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                {{ $countingunit->unit_name }}</td>
                                            <td
                                                style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                {{ $countingunit->pivot->quantity }}</td>
                                            <td
                                                style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                {{ $countingunit->pivot->price }}</td>
                                            <td
                                                style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                {{ $countingunit->pivot->price * $countingunit->pivot->quantity }}</td>
                                        </tr>
                                        <?php
                        }
                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </div>
                <?php
                        }
                    }
                    // middle loop
                    else if ($j > 1 && $j < $z) {
                        $startpt = $endpt;
                        $endpt = $startpt + 18;
                        if ($j == 3) {
                            $startpt = 32;
                            $endpt = 50;
                        }
                    ?>
                <div class="card card-body printableArea">
                    <div class="row">
                        <div class="col-md-12">
                            <table style="width: 100%; ">
                                <thead class="text-center">
                                    <tr>
                                        <th
                                            style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                            Number</th>
                                        <th
                                            style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                            Item</th>
                                        <th
                                            style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                            Order Voucher Quantity</th>
                                        <th
                                            style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                            Price</th>
                                        <th
                                            style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                            Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($unit->shop_counting_unit as $key => $countingunit)
                                        <?php
                                        if ($key >= $startpt && $key < $endpt) {
                                        ?>
                                        <tr>
                                            <td
                                                style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                {{ $i++ }}</td>
                                            <td
                                                style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                {{ $countingunit->unit_name }}</td>
                                            <td
                                                style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                {{ $countingunit->pivot->quantity }}</td>
                                            <td
                                                style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                {{ $countingunit->pivot->price }}</td>
                                            <td
                                                style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                {{ $countingunit->pivot->price * $countingunit->pivot->quantity }}</td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php
                            }
                                                        //last loop
                            else {
                            $startpt = $endpt;
                ?>
                <div class="card card-body printableArea">
                    <div class="row">
                        <div class="col-md-12">
                            <table style="width: 100%; ">
                                <thead class="text-center">
                                    <tr>
                                        <th
                                            style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                            Number</th>
                                        <th
                                            style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                            Item</th>
                                        <th
                                            style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                            Order Voucher Quantity</th>
                                        <th
                                            style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                            Price</th>
                                        <th
                                            style="font-size:23px; font-weight:bold; height: 15px; border: 2px solid black;">
                                            Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($unit->shop_counting_unit as $key => $countingunit)
                                        <?php
                                        if ($key >= $startpt)
                                        {
                                        ?>
                                        <tr>
                                            <td
                                                style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                {{ $i++ }}</td>
                                            <td
                                                style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                {{ $countingunit->unit_name }}</td>
                                            <td
                                                style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                {{ $countingunit->pivot->quantity }}</td>
                                            <td
                                                style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                {{ $countingunit->pivot->price }}</td>
                                            <td
                                                style="font-size:20px; font-weight:bold; height: 8px; border: 2px solid black;">
                                                {{ $countingunit->pivot->price * $countingunit->pivot->quantity }}</td>
                                        </tr>
                                        <?php
                                            }
                                        ?>
                                    @endforeach
                                    <tr>
                                        <td colspan="3"></td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">Total</td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">
                                            <span id="total_charges_a5"></span>{{$unit->total_price}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">Pay</td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">
                                            <span id="pay_1"></span>{{$unit->pay}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">Change</td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">
                                            <span id="changes_1"></span>{{$unit->change}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        {{-- <div class="row mt-2 justify-content-around">

                            <div class="col-md-6">
                                <h3 class=" font-weight-bold" style="font-size:20px;">
                                    Name - <span id="cus_name"> {{ $unit->sales_customer_name }} </span>
                                </h3>
                            </div>
                            <div class="col-md-6">
                                <h3 class=" font-weight-bold" style="font-size:20px;">
                                   Total - <span id="total_charges"> {{ $unit->total_price }} </span>
                                </h3>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <?php
                }
                ?>
                @endfor
            </div>
        </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12 mb-3 text-center">
            <button id="print" class="btn btn-success" type="button">
                <span><i class="fa fa-print"></i> Print</span>
            </button>
            {{-- <button id="edit" class="btn btn-warning" type="button">
                <span><i class="fa fa-edit"></i> Edit</span>
            </button> --}}
            <button id="delete" class="btn btn-danger" data-id="{{$unit->id}}" type="button">
                <span><i class="fa fa-trash"></i> Delete</span>
            </button>
        </div>
    </div>

@endsection

@section('js')

<script>
$("#print").click(function() {
    var mode = 'iframe'; //popup
    var close = mode == "popup";
    var options = {
        mode: mode,
        popClose: close
    };
    $(".tab-pane.active div.printableArea").printArea(options);

})
$('#delete').click(function(){

var voucher_id = $(this).data('id');

$.ajax({

type: 'POST',

url: '/voucher-delete',

data: {
    "_token": "{{ csrf_token() }}",
    "voucher_id": voucher_id,
},

success: function(data) {
    if(data==1){
        swal({
            title: "Success",
            text: "Voucher ဖျက်ပြီးပါပြီ!",
            icon: "info",
        });

        setTimeout(function() {
            window.location.href = "{{ route('shop_sale_history')}}";
            }, 600);
    }else{
        swal({
            title: "Failed!",
            text: "Voucher ဖျက်မရပါ !",
            icon: "error",
        });

    }
},


});
})
</script>

@endsection
