@extends('master')

@section('title', 'Sale Page')

@section('place')
    <style>
        .editprice {
            cursor: pointer
        }

        .discount {
            cursor: pointer
        }

    </style>

@endsection

@section('content')
    @php
    $from_id = session()->get('from');
    @endphp

    <?php
    $itemss = '<span id="lenn"></span>';

    ?>


    <div class="row mb-3">
        <input type="hidden" id="fid" value="{{ $from_id }}">

        <div class="col-md-2">
            <form action="{{ route('get_voucher') }}" method="post" id="vourcher_page">
                @csrf
                <input type="hidden" id="item" name="item">

                <input type="hidden" id="grand" name="grand">

                <input type="hidden" name="right_now_customer" id="right_now_customer">

                <input type="hidden" id="discount" name="discount">

                <input type="hidden" id="foc_flag" name="foc_flag">

                <input type="hidden" id="has_dis" name="has_dis">
            </form>
        </div>
    </div>
    <!--Begin Sale Page -->
    <div class="row">
        <div class="col-md-7 pr-0">
            <div class="row mt-1 mb-2">
                <label class="col-md-2 pl-4">အမျိုးအမည်</label>
                <div class="col-md-8 col-sm-12 d-block" id="search_wif_typing">
                    <select class="p-4 select form-control text-black" name="item" id="counting_unit_select">
                        <option></option>
                        @foreach ($items as $item)
                            @if ($item->shop_counting_units)
                                @foreach ($item->shop_counting_units as $counting_unit)
                                    @foreach ($counting_unit->shop_stockcounts as $stock)
                                       @if ($stock->from_id == $from_id)
                                        @php
                                            if ($stock->from_id == $from_id) {
                                                $stockcountt = $stock->stock_qty;
                                            }
                                        @endphp

                                    <option class="text-black" data-unitname="{{ $counting_unit->unit_name }}"
                                        data-itemname="{{ $item->shop_item_name }}"
                                        data-brandname="{{ $item->shop_brand->name }}"
                                        data-normal="{{ $counting_unit->normal_sale_price }}"
                                        data-whole="{{ $counting_unit->whole_sale_price }}"
                                        data-currentqty="{{ $stockcountt }}" value="{{ $counting_unit->id }}">
                                        {{ $item->shop_item_name }}- {{ $item->shop_brand->name }}-
                                        {{ $counting_unit->unit_name }}&nbsp;&nbsp; {{ $stockcountt }}ခု&nbsp;&nbsp;
                                        {{ $counting_unit->normal_sale_price }}ကျပ်</option>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8 col-sm-12 d-none" id="search_wif_barcode">
                    <input class="form-control" type="text" onchange="QRcodeTest(this.value)" id="qr_code" autofocus>
                </div>
                <button onclick="qrSearch()"
                    class="col-md-1 btn-sm btn-success ml-4 pl-1 d-none d-sm-none d-md-block d-lg-block " style="padding:0">
                    <i class="fas fa-barcode p-0 text-white" style="font-size: 25px"></i>
                </button>
            </div>
        </div>
        <div class="col-md-5 mt-1 pl-0">
            <div class="col-md-10 mb-1">
                <select id="price_type" class="form-control" style="font-size: 14px">
                    <option value="1">Normal Sale Price</option>
                    <option value="2">Whole Sale Sale Price</option>
                </select>
            </div>
        </div>


        <div class="col-md-7 pr-0">

            {{-- refresh here --}}
            <div class="col-md-12 pr-0" style="">
                <div class="card" style="border-radius: 0px;min-height:100vh">
                    <div class="card-title">
                        <a href="" class="text-success px-2" onclick="deleteItems()"><i class="fas fa-sync"></i> Refresh
                            Here &nbsp</a>
                    </div>
                    <div class="card-body salepageheight">
                        <h5 class="now_customer text-warning">Customer <span id="now_customer_no"></span></h5>
                        <input type="hidden" name="now_customer" value="0" id="now_customer">

                        <div class="row justify-content-center">
                            <table class="table text-black table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-black">Item Name</th>
                                        <th class="text-black">Quantity</th>
                                        <th class="text-black">Price</th>
                                        <th class="text-black">Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody id="sale">
                                    <tr class="text-center">

                                    </tr>
                                </tbody>
                                <tfoot id="sale_foot">
                                    <tr class="text-center">
                                        <td class="text-black" colspan="4">Total Quantity
                                        </td>
                                        <td class="text-black" id="total_quantity">0</td>
                                    </tr>
                                    <tr class="text-center">
                                        <td class="text-black" colspan="4">Total</td>
                                        <td class="text-black" id="sub_total">0</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="row ml-2 justify-content-center">

                            <!-- <div class="col-md-8"> -->

                            <div class="modal fade" id="customer_order" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Add Customer Order</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Select Customer</label>
                                                <select class="form-control m-b-10" id="customer_id" style="width: 100%"
                                                    required onchange="getCustomerInfo(this.value)">
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}">
                                                            {{ $customer->user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label class="font-weight-bold">Phone</label>
                                                <input type="number" id="phone" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="font-weight-bold">Delivered Date</label>
                                                <input type="datetime-local" id="delivered_date" class="form-control"
                                                    required value="">

                                                <div class="form-group">
                                                    <label class="font-weight-bold">Order Date</label>
                                                    <input type="date" id="order_date" class="form-control" required
                                                        value="">
                                                </div>

                                                <div class="form-group">
                                                    <label class="font-weight-bold">Address</label>
                                                    <input type="text" id="address" class="form-control" required>

                                                </div>

                                                <div class="form-group">
                                                    <label class="font-weight-bold">Select Employee</label>
                                                    <select class="form-control m-b-10" id="employee" style="width: 100%"
                                                        required>
                                                        <option value="">Please Choose Employee</option>
                                                        {{-- @foreach ($employees as $emp)
                                                            @if ($emp->user->role == 'Delivery_Person')
                                                                <option value="{{ $emp->id }}">
                                                                    {{ $emp->user->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach --}}
                                                    </select>
                                                </div>

                                                <a href="#" class="btn btn-success" onclick="storeCustomerOrder()">
                                                    <i class="fas fa-calendar-check"></i> Store Order
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- </div> -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 offset-md-6">
                <div class="pending-voucher row">

                </div>
            </div>
            <div class="modal fade" id="editprice" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Item Price</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                id="#close_modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--End Sale Page -->
        <div class="col-md-5 mt-1 pl-0">
            <!-- Begin Sale Customer Info -->
            <div class="col-md-12 pl-0" style="margin-left:-1px">
                <div class="card pl-2 pr-4 py-3 mb-0" style="border-radius: 0px;margin-top:-4px">
                    <div class="">
                        <div class="row">
                            <label class="control-label text-black col-5">၀ယ်သူအမည်(ကြွေးကျန်)</label>
                            <input type="hidden" id="pending_cust">
                            <select class="form-control text-black d-none d-sm-none d-md-block d-lg-block col-md-7"
                                style="font-size: 14px" id="salescustomer_list" onchange="fillCustomer(this.value)">
                                <option value="" class="text-black" style="font-size: 14px">Select Customers</option>

                                @foreach ($salescustomers as $salescustomer)
                                    <option value="{{ $salescustomer->id }}">{{ $salescustomer->name }}</option>
                                @endforeach

                            </select>
                            <input type="text" class="form-control col-7 offset-md-5 font14 text-black" id="name"
                                value="customer">
                        </div>
                        <div class="row my-1">
                            <label class="control-label text-black col-5 font14">ဖုန်း</label>
                            <input type="number" class="form-control col-7 font14 text-black" id="custphone" value="09"
                                placeholder="09">
                        </div>
                    </div>
                    <div class="">
                        <div class="row">
                            <label class="control-label text-black col-5">အရင်ကြွေးကျန်</label>
                            <input type="number" class="form-control col-7 text-black" value="0" id="credit" readonly>
                            <input type="hidden" id="previous_credit" value="0">
                        </div>
                         {{-- <div class="col-md-12">
                            <label class="text-info">Repayment Date </label>
                            <input type="text" class="form-control" id="repaymentDate" name="request_date">
                        </div> --}}
                    </div><br>
                    <div class="row d-none d-sm-none d-md-block d-lg-block">
                        <div class="col-md-7 offset-md-5 pl-0">
                            <button id="save" class="btn btn-outline-secondary" type="button"><span><i
                                        class="fa fa-save mr-2"></i>Save</span></button>
                            <a href="#" class="btn btn-outline-danger mx-2" id="deletesaleuser"></i>
                                <i class="fas fa-trash-alt"></i></a>


                        </div>
                    </div>
                </div>
                <div class="card pl-2 pr-4 py-3" style="border-radius: 0px;margin-top:-9px">
                    <div class="row mb-2">
                        <label class="control-label  col-5 text-black">စုစုပေါင်း </label>
                        <input type="number" class="form-control col-7 h-75 text-black" id="gtot" value="0">
                    </div>
                    <div class="row mb-2">
                        <label class="control-label text-black col-5">Discount</label>
                        <input type="number" class="form-control col-4 h-75 text-black" id="discount_amount" readonly
                            value="0">
                        <div class="col-3">
                            <button id="voucher_discount" onclick="insert_total()" class="btn btn-secondary"
                                type="button"><span><i class="fa fa-save mr-3"></i>Discount</span></button>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="control-label text-black col-5">ကျသင့်ငွေ</label>
                        <input type="number" id="with_dis_total" class="form-control col-7 h-75 text-black">
                    </div>
                    <div class="row mb-2">
                        <label class="control-label text-black col-5">ပေးငွေ</label>
                        <input type="number" onkeyup="getCreditAmount(this.value)"
                            class="form-control col-7 h-75 text-black" id="payable">
                    </div>
                    <div class="row mb-2">
                        <label class="control-label  col-5 text-black">လက်ကျန်ငွေ</label>
                        <input type="number" class="form-control col-7 h-75 text-black" value="0" id="current_credit"
                            readonly>
                    </div>

                    <div class="row mb-2">
                        <label class="control-label  col-5 text-black">ပြန်အမ်းငွေ</label>
                        <input type="number" class="form-control col-7 h-75" value="0" id="current_change">
                    </div>
                    <div class="row">
                        <label class="control-label pt-2 col-5 text-black">Voucher အမျိုးအစား</label>
                        <div class="col-4 pl-0">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">
                                        SLIP
                                    </a>
                                </li>
                                <li class=" nav-item">
                                    <a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false"
                                        onclick="show_a5()">
                                        A5
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-2">
                            <button class="btn btn-secondary d-none d-sm-none d-md-block d-lg-block" type="button"
                                data-toggle="modal" data-target="#seevoucher">
                                <span><i class="fas fa-eye"></i> Voucher</span> </button>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="seevoucher" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Voucher</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    id="#close_modal">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="tab-content br-n pn">
                                    <div id="navpills-1" class="tab-pane active">
                                        <div class="row justify-content-center">
                                            <div class="col-md-8 printableArea" style="width:45%;">
                                                <div class="card card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="text-center">
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
                                                            </div>
                                                            <div class="pull-right text-left">
                                                                <h6 class="text-black">Date :<i
                                                                        class="fa fa-calendar"></i>  {{$today_date}}</h6>
                                                                <h6 class="text-black">Voucher Number : <span
                                                                        class="vou_code">{{$voucher_code}}</span>
                                                                </h6>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="table-responsive text-black" style="clear: both;">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                        <tr class="text-black">
                                                                            <th>Name</th>
                                                                            <th>Qty*Price</th>
                                                                            <th>Total</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody class="text-black" id="slip_live">

                                                                        <tr>
                                                                            <td style="font-size:15px;"></td>
                                                                            <td style="font-size:15px;"></td>
                                                                            <td style="font-size:15px;" id="subtotal"></td>
                                                                        </tr>

                                                                    </tbody>
                                                                    <tfoot class="text-black">
                                                                        <tr>
                                                                            <td></td>
                                                                            <td class="text-right"
                                                                                style="font-size:18px;">Total
                                                                            </td>
                                                                            <td id="total_charges" class="font-weight-bold"
                                                                                style="font-size:18px;"><span
                                                                                    id="slip_total"></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td></td>
                                                                            <td class="text-right"
                                                                                style="font-size:18px;">Pay</td>
                                                                            <td id="pay" style="font-size:18px;"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td></td>
                                                                            <td class="text-right"
                                                                                style="font-size:18px;">Change
                                                                            </td>
                                                                            <td id="changes" style="font-size:18px;"></td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                                <h6 class="text-center font-weight-bold text-black">
                                                                    **ကျေးဇူးတင်ပါသည်***</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="navpills-2" class="tab-pane ">
                                        <div class="row justify-content-center" id="a5_voucher">
                                            <div class="col-md-10">

                                                <div class="card card-body printableArea">
                                                    <div style="display:flex;justify-content:space-around">
                                                        <div class="mt-2">
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
                                                    <div class="row text-black">
                                                        <div class="col-md-7">
                                                            <h3 class=" mt-2 text-black" style="font-size : 18px; color:black">
                                                                Invoice No : <span
                                                                    class="vou_code">{{$voucher_code}}</span>
                                                            </h3>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <h3 class=" mt-2 text-black" style="font-size : 18px; color:black">ဖုန်း :
                                                                <span id="cus_phone"></span>
                                                            </h3>
                                                        </div>

                                                        <div class="col-md-7">
                                                            <h3 class=" mt-2 text-black"
                                                                style="font-size : 18px; color:black">Invoice Date
                                                                : {{$today_date}} </h3>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <h3 class=" mt-2 text-black" style="font-size : 18px; color:black">၀ယ်သူအမည်
                                                                : <span id="cus_name"></span>
                                                            </h3>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table style="width: 100%; ">
                                                                <thead class="text-center">
                                                                    <tr>
                                                                        <th
                                                                            style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;">
                                                                            Number</th>
                                                                        <th
                                                                            style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;">
                                                                            Item</th>
                                                                        <th
                                                                            style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;">
                                                                            အရေတွက်</th>
                                                                        <th
                                                                            style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;">
                                                                            Price</th>
                                                                        <th
                                                                            style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;">
                                                                            Total</th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody class="text-center" id="a5_body">
                                                                </tbody>
                                                            </table>
                                                        </div>




                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 d-none">
                        <a href="#" class="btn btn-info" data-toggle="modal" data-target="#customer_order">
                            <i class="fas fa-calendar-check"></i> Add Customer Order
                        </a>
                    </div>
                    <div class="col-md-4 col-4 d-none d-sm-none d-md-block d-lg-block ">
                        <i class="btn btn-success ml-3" onclick="storePendingVoucher()"><i
                                class="fas fa-arrow-alt-circle-down"></i> Pending Order </i>
                    </div>
                    {{-- <div class="col-md-4 col-4 d-none"> --}}
                    <!-- <i class="btn btn-success" onclick="showCheckOut()"><i class="fas fa-calendar-check"></i> @lang('lang.check_out') </i> -->
                    {{-- <a href="#show_vou" class="btn btn-success"><i class="fas fa-calendar-check"></i>
                            @lang('lang.check_out') </a>
                    </div> --}}
                    <div class="col-md-2">
                        <button id="print" class="ml-2 btn btn-success d-none d-sm-none d-md-block d-lg-block"
                            type="button">
                            <span><i class="fa fa-print"></i> Print</span> </button>
                    </div>
                    <div class="col-md-4 offset-4 d-block d-md-none d-lg-none store_voucher">
                        {{-- for mobile --}}
                        <button class="btn btn-danger " type="button"> <span><i class="fa fa-calendar-check"></i> Store
                                Voucher</span> </button>
                    </div>
                    <div class="col-md-4 d-none d-sm-none d-md-block d-lg-block ">
                        {{-- for web --}}
                        <button class="btn btn-danger store_voucher" type="button">
                            <span><i class="fa fa-calendar-check"></i> Store Voucher</span> </button>
                    </div>
                </div>
            </div>
            <br>

            <div class="modal fade" id="voudiscount" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-success">
                            <h4 class="modal-title text-white">Item Price</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                id="#close_modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body" id="checkout_modal_body">
                            <input type="hidden" id="vou_discount" name="vou_discount">
                            <div class="form-group">
                                <label class="form-control font-weight-bold">Voucher Total</label>
                                <input type="text" class="form-control" readonly id="voucher_total" value="">
                            </div>
                            <div class="form-check form-switch float-right">
                                <input class="form-check-input" name="voufoc" type="checkbox" id="voufoc" value="1">
                                <label class="form-check-label" for="voufoc">FOC</label>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Price<span id="vou_discount_amount"
                                        class="text-danger"></span>
                                    mmk<span>(Voucher Total)</span></label>
                                <input type="number" id="vou_price_change" class="form-control" required value="">
                                <input type="hidden" id="vou_or_price" value="0">
                            </div>
                            <div class="row">
                                <div class="col-6 form-check form-switch">
                                    <input class="form-check-input" name="vou_percent_for_price" type="checkbox" value="1"
                                        id="vou_percent_for_price">
                                    <label class="form-check-label" for="vou_percent_for_price">(%)</label>
                                </div>
                                <div class="form-group col-6">
                                    <input type="number" id="vou_percent_price" class="form-control" disabled>
                                </div>
                            </div>

                            <button type="button" class="btn btn-success" id="vou_price_change_btn" btn-lg
                                btn-block">Change Price</button>
                        </div>


                    </div>
                </div>
            </div>
            <input type="hidden" id="voucherCode" value="{{$voucher_code}}">
        </div><!-- All row end -->
    @endsection

    @section('js')

        <script type="text/javascript">
         $('#table_1').DataTable({

            "paging": false,
            "ordering": true,
            "info": false,
            scrollY: 700,

            });

            $('#table_2').DataTable({

            "paging": false,
            "ordering": true,
            "info": false,
            scrollY: 700,

            });

            $('#table_3').DataTable({

            "paging": false,
            "ordering": true,
            "info": false,
            scrollY: 700,

            });
            $('#table_4').DataTable({

            "paging": false,
            "ordering": true,
            "info": false,
            scrollY: 700,

            });

            $(".select").select2({
            placeholder: "ရှာရန်",
            });

            $(document).ready(function() {

            $('#a5_last').hide();
            $('#a5_middle').hide();

            var mycart = localStorage.getItem('mycart');
            var mycartobj = JSON.parse(mycart);
            var arr = [];




            $('.now_customer').hide();
            showmodal();
            local_customer_lists();

            });

        $('#search_wif_typing').on('change','#counting_unit_select',function(){
    var id = $('#counting_unit_select').val();
    var unitname = $(this).find(":selected").data('unitname');
    var itemname = $(this).find(":selected").data('itemname');
    var brandname = $(this).find(":selected").data('brandname');
    var normalprice = $(this).find(":selected").data('normal');
    var wholeprice = $(this).find(":selected").data('whole');
    var orderprice = $(this).find(":selected").data('order');
    var currentqty = $(this).find(":selected").data('currentqty');
    var price_type = $('#price_type').val();
    console.log(normalprice, wholeprice, orderprice);
    if (price_type == 1) {
        var saleprice = normalprice;
    } else{
        var saleprice = wholeprice;
    }

        var total_price = saleprice * 1;
        var eachsub = saleprice * 1;
        var item = {
            id: parseInt(id),
            item_name: itemname,
            brand_name: brandname,
            unit_name: unitname,
            current_qty: currentqty,
            order_qty: 1,
            selling_price: saleprice,
            each_sub: eachsub,
            discount: 0
        };

        var total_amount = {
            sub_total: total_price,
            total_qty: 1,
            vou_discount: 0
        };

        var mycart = localStorage.getItem('mycart');

        var grand_total = localStorage.getItem('grandTotal');

        if (mycart == null) {

            mycart = '[]';

            var mycartobj = JSON.parse(mycart);

            mycartobj.push(item);

            localStorage.setItem('mycart', JSON.stringify(mycartobj));

        } else {

            var mycartobj = JSON.parse(mycart);

            var hasid = false;

            $.each(mycartobj, function(i, v) {

                if (v.id == id) {

                    hasid = true;

                    v.order_qty = parseInt(1) + parseInt(v.order_qty);
                    v.each_sub = parseInt(v.selling_price) * parseInt(v.order_qty);
                    console.log(v.each_sub);
                }
            })

            if (!hasid) {

                mycartobj.push(item);
            }

            localStorage.setItem('mycart', JSON.stringify(mycartobj));
        }

        if (grand_total == null) {

            localStorage.setItem('grandTotal', JSON.stringify(total_amount));

        } else {

            var grand_total_obj = JSON.parse(grand_total);

            grand_total_obj.sub_total = total_price + grand_total_obj.sub_total;

            grand_total_obj.total_qty = parseInt(1) + parseInt(grand_total_obj.total_qty);

            localStorage.setItem('grandTotal', JSON.stringify(grand_total_obj));
        }

        $("#unit_table_modal").modal('hide');

        // for a5 voucher
        var mycart = localStorage.getItem('mycart');

        var arr = [];

        $('#lenn').html(mycart);

        var grand_total = localStorage.getItem('grandTotal');

        var grand_total_obj = JSON.parse(grand_total);
        if (grand_total_obj.vou_discount == 0) {
            var sub_total = grand_total_obj.sub_total;
        } else {
            var sub_total = grand_total_obj.vou_discount;
        }
        $('#voucher_total').val(sub_total);
        $('#gtot').val(sub_total);
        $('#with_dis_total').val(sub_total);

        showmodal();
    //}
})

function showmodal() {

var mycart = localStorage.getItem('mycart');

var grandTotal = localStorage.getItem('grandTotal');

var grandTotal_obj = JSON.parse(grandTotal);

if (mycart) {

    var mycartobj = JSON.parse(mycart);

    var html = '';

    if (mycartobj.length > 0) {

        $.each(mycartobj, function(i, v) {

            var id = v.id;

            var item = v.item_name;

            var brand = v.brand_name;

            var qty = v.order_qty;

            var count_name = v.unit_name;

            if (v.discount == 0) {
                var selling_price = v.selling_price;
            } else if (v.discount == 'foc') {
                var selling_price = 0;
            } else if (v.discount == null) {
                var selling_price = null;
            } else {
                var selling_price = v.discount;
            }

            var each_sub_total = v.order_qty * selling_price ?? 0;

            html += `<tr class="text-center">


            <td class="text-black">${item}-${brand}-${count_name}</td>



            <td class="text-black w-25 m-0 p-0" onkeyup="plus(${id})" id="${id}">
                <input type="number" class="form-control w-100 text-black text-center p-0 mt-1" name="" id="nowqty${id}" value="${qty}" style="border: none;border-color: transparent;">
            </td>

            <td class="text-black w-25 m-0 p-0" data-price="${selling_price}" >
                <input onkeyup="table_edit_price(${v.id},${selling_price})" type="number" class=" form-control w-100 text-black text-center p-0 mt-1" id="nowprice${id}" value="${selling_price}" style="border: none;border-color: transparent;">
            </td>


            <td class="text-black">${v.each_sub ?? 0}</td>
            <td><i class="fa fa-times" onclick="remove(${id},${qty})" id="${id}"></i> </td>
            </tr>`;

        });
    }

    var htmlslip = "";
    var id = $('#counting_unit_select').val();
    $.each(mycartobj, function(i, v) {
        if (parseInt(v.discount) == 0) {
            var selling_price = v.selling_price;
        } else {
            var selling_price = v.discount;
        }
        var totalslip = parseInt(selling_price) * parseInt(v.order_qty);
        htmlslip += `
         <tr>
            <td style="font-size:15px;">${v.item_name}-${v.brand_name}-${v.unit_name}</td>
            <td style="font-size:15px;">${v.order_qty} * ${selling_price}</td>
            <td style="font-size:15px;" id="subtotal">${totalslip}</td>
        </tr>
`;
    });

    if (grandTotal_obj.vou_discount == 0) {
        var sub_total = grandTotal_obj.sub_total;
        var total_wif_discount = grandTotal_obj.sub_total;
    } else if (grandTotal_obj.vou_discount == "foc") {
        var sub_total = grandTotal_obj.sub_total;
        var total_wif_discount = 0;

    } else {
        var sub_total = grandTotal_obj.sub_total;
        var total_wif_discount = grandTotal_obj.sub_total - grandTotal_obj.vou_discount;
    }

    $('#slip_live').html(htmlslip);
    $('#total_charges').text(total_wif_discount);
    var pay = $('#payable').val();


    $("#total_quantity").text(grandTotal_obj.total_qty);

    $("#sub_total").text(total_wif_discount);
    $('#gtot').val(sub_total);
    $('#with_dis_total').val(total_wif_discount)

    $("#sale").html(html);

}
show_a5();
}

function remove(id, qty) {
    count_change(id, 'remove', qty)
}

function count_change(id, action, qty) {

    var grand_total = localStorage.getItem('grandTotal');

    var mycart = localStorage.getItem('mycart');

    var mycartobj = JSON.parse(mycart);

    var grand_total_obj = JSON.parse(grand_total);

    var item = mycartobj.filter(item => item.id == id);

    if (action == 'plus') {

            item[0].order_qty = qty;
            if (parseInt(item[0].discount) == 0) {
                item[0].each_sub = parseInt(item[0].selling_price) * qty;
            } else {
                item[0].each_sub = parseInt(item[0].discount) * qty;
            }

            new_total = 0;
            new_total_qty = 0;
            $.each(mycartobj, function(i, value) {
                new_total += value.each_sub;
                new_total_qty += value.order_qty
            })

            grand_total_obj.sub_total = new_total;

            grand_total_obj.total_qty = new_total_qty;

            localStorage.setItem('mycart', JSON.stringify(mycartobj));

            localStorage.setItem('grandTotal', JSON.stringify(grand_total_obj));

            count_item();

            showmodal();

        //}
    } else if (action == 'minus') {

        if (item[0].order_qty <= qty) {

            //var ans=confirm('Are you sure');

            swal({
                title: "Are you sure?",
                text: "The item will be remove from cart list",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes',
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: false
            }).then(
                function(isConfirm) {
                    if (isConfirm) {

                        let item_cart = mycartobj.filter(item => item.id !== id);

                        grand_total_obj.sub_total -= parseInt(item[0].selling_price) * qty;

                        grand_total_obj.total_qty -= qty;

                        console.log("yes");
                        localStorage.setItem('mycart', JSON.stringify(item_cart));

                        localStorage.setItem('grandTotal', JSON.stringify(grand_total_obj));

                        count_item();

                        showmodal();

                    } else {

                        item[0].order_qty;
                        console.log("no");
                        localStorage.setItem('mycart', JSON.stringify(mycartobj));

                        localStorage.setItem('grandTotal', JSON.stringify(grand_total_obj));

                        count_item();

                        showmodal();
                    }
                });



        } else {
            console.log("hello");
            item[0].order_qty -= qty;

            grand_total_obj.sub_total -= parseInt(item[0].selling_price) * qty;
            item[0].each_sub -= parseInt(item[0].selling_price) * qty;
            grand_total_obj.total_qty -= qty;

            localStorage.setItem('mycart', JSON.stringify(mycartobj));

            localStorage.setItem('grandTotal', JSON.stringify(grand_total_obj));

            count_item();

            showmodal();
        }
    } else if (action == 'remove') {
        //var ans=confirm('Are you sure?');

        swal({
            title: "Are you sure?",
            text: "The item will be remove from cart list",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes',
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: false
        }).then(
            function(isConfirm) {

                if (isConfirm) {
                    let item_cart = mycartobj.filter(item => item.id !== id);
                    console.log(item_cart);
                    grand_total_obj.sub_total = grand_total_obj.sub_total - (parseInt(item[0].selling_price) *
                        qty);

                    grand_total_obj.total_qty = grand_total_obj.total_qty - qty;

                    localStorage.setItem('mycart', JSON.stringify(item_cart));

                    localStorage.setItem('grandTotal', JSON.stringify(grand_total_obj));

                    count_item();

                    showmodal();

                } else {
                    item[0].order_qty;

                    localStorage.setItem('mycart', JSON.stringify(mycartobj));

                    localStorage.setItem('grandTotal', JSON.stringify(grand_total_obj));

                    count_item();

                    showmodal();
                }
            });

    }

}

function count_item() {

var mycart = localStorage.getItem('mycart');

if (mycart) {

    var mycartobj = JSON.parse(mycart);

    var total_count = 0;

    $.each(mycartobj, function(i, v) {

        total_count += v.order_qty;

    })

    $(".item_count_text").html(total_count);

} else {

    $(".item_count_text").html(0);

}
}
function deleteItems() {
    // clearLocalstorage(0);
    localStorage.clear();
    }

function qrSearch() {
                if ($("#search_wif_typing").hasClass("d-block")) {
                    $("#search_wif_typing").removeClass("d-block");
                    $("#search_wif_typing").addClass("d-none");
                    $("#search_wif_barcode").removeClass("d-none");
                    $("#search_wif_barcode").addClass("d-block");
                } else {
                    $("#search_wif_typing").removeClass("d-none");
                    $("#search_wif_typing").addClass("d-block");
                    $("#search_wif_barcode").removeClass("d-block");
                    $("#search_wif_barcode").addClass("d-none");
                }

                document.getElementById("qr_code").focus();

            }
            function QRcodeTest(value) {

                let sale_type = $("#price_type").val();

                $.ajax({

                    type: 'POST',

                    url: '/getCountingUnitsByItemCode',

                    data: {
                        "_token": "{{ csrf_token() }}",
                        "unit_code": value,
                    },

                    success: function(data) {

                        var item_name = data.shop_item.shop_item_name;

                        var brand_name = data.shop_item.shop_brand.name;

                        var id = data.id;

                        var name = data.unit_name;

                        var qty = parseInt(data.current_quantity);

                        if (sale_type == 1) {

                            var price = data.normal_sale_price;

                        } else if (sale_type == 2) {

                            var price = data.normal_sale_price;

                        } else {

                            var price = data.order_price;

                        }
                        var value = 1;
                        if (qty == 0) {


                            swal({
                                title: "Can't Add",
                                text: "Your Input is higher than Current Quantity!",
                                icon: "info",
                            });

                        } else {

                            var total_price = price * value;

                            var item = {
                                id: id,
                                item_name: item_name,
                                brand_name: brand_name,
                                unit_name: name,
                                current_qty: qty,
                                order_qty: value,
                                selling_price: price
                            };

                            var total_amount = {
                                sub_total: total_price,
                                total_qty: value
                            };

                            var mycart = localStorage.getItem('mycart');

                            var grand_total = localStorage.getItem('grandTotal');

                            if (mycart == null) {

                                mycart = '[]';

                                var mycartobj = JSON.parse(mycart);

                                mycartobj.push(item);

                                localStorage.setItem('mycart', JSON.stringify(mycartobj));

                            } else {

                                var mycartobj = JSON.parse(mycart);

                                var hasid = false;

                                $.each(mycartobj, function(i, v) {

                                    if (v.id == id) {

                                        hasid = true;

                                        v.order_qty = parseInt(value) + parseInt(v.order_qty);
                                    }
                                })

                                if (!hasid) {

                                    mycartobj.push(item);
                                }

                                localStorage.setItem('mycart', JSON.stringify(mycartobj));
                            }

                            if (grand_total == null) {

                                localStorage.setItem('grandTotal', JSON.stringify(total_amount));

                            } else {

                                var grand_total_obj = JSON.parse(grand_total);

                                grand_total_obj.sub_total = total_price + grand_total_obj.sub_total;

                                grand_total_obj.total_qty = parseInt(value) + parseInt(grand_total_obj.total_qty);

                                localStorage.setItem('grandTotal', JSON.stringify(grand_total_obj));
                            }

                            showmodal();

                            $("#qr_code").val("");
                            $("#qr_code").focus();
                        }
                    }

                });
            }
            function insert_total() {
                var grand_total = localStorage.getItem('grandTotal');

                var grand_total_obj = JSON.parse(grand_total);
                $('#voucher_total').val(grand_total_obj.sub_total);
                $('#vou_price_change').val(0);
                $('#voudiscount').modal('show');
            }
            $('#voufoc').click(function() {
                // alert($("input:checkbox[name=foc]:checked").val());

                var price_change = $('#vou_price_change').val();
                var or_price = $('#vou_or_price').val();
                if ($("input:checkbox[name=voufoc]:checked").val() == 1) {
                    $('#vou_price_change').val(0);
                } else {
                    $('#vou_price_change').val(or_price);
                }
                //    var percent_for_price=$('#percent_for_price').val();
            })
            $('#vou_percent_for_price').click(function() {
                var idArray = [];
                $("input:checkbox[name=vou_percent_for_price]:checked").each(function() {
                    idArray.push(parseInt($(this).val()));
                });
                if (idArray.length > 0) {
                    $('#vou_percent_price').removeAttr('disabled');
                    $('#vou_percent_price').focus();
                } else {
                    $('#vou_percent_price').attr('disabled', 'disabled');
                }
                //    var percent_for_price=$('#percent_for_price').val();
            })
            $('#vou_percent_price').keyup(function() {
                var percent_price = $('#vou_percent_price').val();
                var or_price = $('#voucher_total').val();
                // alert(percent_price+"---"+or_price);
                var discount_amount = parseInt(or_price * (percent_price / 100));
                var change_percent_price = parseInt(or_price) - discount_amount;
                $('#vou_discount_amount').html(discount_amount);
                $('#vou_price_change').val(change_percent_price);
            })
            $('#vou_price_change_btn').click(function() {


            var grand_total = localStorage.getItem('grandTotal');

            var grand_total_obj = JSON.parse(grand_total);

            var price_change = $('#vou_price_change').val();

            if ($('#voufoc').is(':checked')) {

                var totaL = 0;
                var discount_amount_text = 'foc';
                var discount_amount = 0;

            } else {
                var totaL = $('#gtot').val();
                var discount_amount_text = totaL - price_change;
                var discount_amount = totaL - price_change;
            }

            $('#discount_amount').val(discount_amount);

            $('#with_dis_total').val(parseInt(price_change));

            $('#sub_total').empty();

            $('#sub_total').text(parseInt(price_change));

            $('#total_charges_a5').empty();
            $('#total_charges_a5').text(parseInt(price_change));
            $('#total_charges').empty();
            $('#total_charges').text(parseInt(price_change));

            grand_total_obj.vou_discount = discount_amount_text;

            localStorage.setItem('grandTotal', JSON.stringify(grand_total_obj));

            $('#voudiscount').modal('hide');

            })

            function getCreditAmount(pay_amount) {

            var total_charges = parseInt($('#with_dis_total').val());

            var has_credit = parseInt($('#credit').val());
            var previous_credit = $('#previous_credit').val();
            // alert(total_charges);alert
            if (pay_amount > total_charges) {
                var credit_amt = 0;
                $('#current_change').val(parseInt(pay_amount) - parseInt(total_charges));
            } else {
                var credit_amt = parseInt(total_charges) - parseInt(pay_amount);
                var hascre = parseInt(previous_credit) + parseInt(credit_amt);
            }
            $('#pay').text(pay_amount);
            $('#changes').text(pay_amount-total_charges);
            $("#credit").val(hascre);
            $('#current_credit').val(credit_amt);
            }

            function show_a5() {
                $("#a5_body").empty();

                var k = 1;
                var mycart = localStorage.getItem('mycart');
                var mycartobj = JSON.parse(mycart);

                var pay = $('#payable').val();
                var name= $('#name').val();
                var phone = $('#custphone').val();
                $('#cus_name').text(name);
                $('#cus_phone').text(phone);
                //Begin A5 Voucher

                var grandTotal = localStorage.getItem('grandTotal');
                var grandTotalobj = JSON.parse(grandTotal);

                var len = mycartobj.length;
                var htmlcountitem = "";
                var j = 1;

                var i = 0;
                var each_sub_total = 0;
                $.each(mycartobj, function(i, value) {

                    if (value.discount == 0) {
                                var selling_price = value.selling_price;
                            } else if (value.discount == 'foc') {
                                var selling_price = 0;
                            } else if (value.discount == null) {
                                var selling_price = null;
                            } else {
                                var selling_price = value.discount;
                            }

                            var each_sub_total = value.order_qty * selling_price ?? 0;

                    htmlcountitem += `
                <tr>
                <td style="font-size:20px;height: 8px; border: 2px solid black;">${++i }</td>
                <td style="font-size:20px;height: 8px; border: 2px solid black;">${value.item_name}-${value.brand_name}-${value.unit_name}</td>
                <td style="font-size:20px;height: 8px; border: 2px solid black;">${value.order_qty} </td>
                <td style="font-size:20px;height: 8px; border: 2px solid black;">${selling_price} </td>
                <td style="font-size:20px;height: 8px; border: 2px solid black;">${each_sub_total} </td>
            </tr>
                `;
                })

                htmlcountitem += `
                <tr>
                    <td colspan="3"></td>
                    <td style="font-size:20px;height: 8px; border: 2px solid black;">ကျသင့်ငွေ</td>
                    <td style="font-size:20px;height: 8px; border: 2px solid black;">
                        <span id="total_charges_a5">${grandTotalobj.sub_total}</span></td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td style="font-size:20px;height: 8px; border: 2px solid black;">ပေးငွေ</td>
                    <td style="font-size:20px;height: 8px; border: 2px solid black;">
                        <span id="pay_1">${pay}</span></td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td style="font-size:20px;height: 8px; border: 2px solid black;">အမ်းငွေ</td>
                    <td style="font-size:20px;height: 8px; border: 2px solid black;">
                        <span id="changes_1">${pay-grandTotalobj.sub_total}</span></td>
                </tr>
            `;

                $("#a5_body").html(htmlcountitem);

                //End A5 Voucher
            }
    function fillCustomer(value) {

        var customer_id = value;


        $.ajax({
            type: 'POST',
            url: '{{ route('AjaxGetCustomerwID') }}',
            data: {
                "_token": "{{ csrf_token() }}",
                "customer_id": customer_id,
            },
            success: function(data) {
                $("#name").val(data.sale_cust.name);
                $("#custphone").val(data.sale_cust.phone);
                $('#pending_cust').val(data.sale_cust.name);
                //  $("#credit").val(data.credit_amount);
            // if (data.sale_credit != null) {
                    $('#credit').val(data.sale_cust.credit_amount);
                    $('#previous_credit').val(data.sale_cust.credit_amount);
                //} else {
                //   $('#credit').val(0);
                //   $('#previous_credit').val(0);
                //}
            },
        });
        }
        var last_row_id = 0;
            $("#save").click(function() {
                var name = $('#name').val();
                var phone = $('#custphone').val();
                var credit_amount = $('#credit').val();
                // alert(name);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('AjaxStoreCustomer') }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "name": name,
                        "phone": phone,
                        "credit_amount": credit_amount,
                    },
                    success: function(data) {
                        console.log(data.last_row);
                        if (data.success == 1) {
                            last_row_id = data.last_row.id;
                            //$lastRecord = DB::table('sales_customers')->orderBy('id', 'DESC')->first();
                            swal({
                                    title: "Success!",
                                    text: "Successfully Saved!",
                                    icon: "success",
                                });
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                        }
                    }
                });

            });

            var salescustomer = null;

                $("#deletesaleuser").click(function() {
                    var salecustomer_id = $('#salescustomer_list').children("option:selected").val();
                    console.log(salecustomer_id);
                    swal({
                            title: "@lang('lang.confirm')",
                            icon: 'warning',
                            buttons: ["@lang('lang.no')", "@lang('lang.yes')"]
                        })
                        .then((isConfirm) => {
                            if (isConfirm) {
                                $.ajax({
                                    type: 'POST',
                                    dataType: 'json',
                                    url: '{{ route('saleCustomerDelete') }}',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        "salecustomer_id": salecustomer_id,

                                    },
                                    success: function() {

                                        swal({
                                            title: "Success!",
                                            text: "Successfully Deleted!",
                                            icon: "success",
                                        });
                                        setTimeout(function() {
                                            window.location.reload();
                                        }, 1000);

                                    },
                                });
                            }


                        });
                });
        $("#print").click(function() {
        var exitvoucher = localStorage.getItem('exitvoucher');
        if (exitvoucher == null) {
            voucher_id = 0;
        } else {
            voucher_id = exitvoucher;
        }
        var now_customer = $('#now_customer').val();

        var from_id = $('#fid').val();

        var mycart = localStorage.getItem('mycart');

        var grand_total = localStorage.getItem('grandTotal');
        var editvoucher = localStorage.getItem('editvoucher');

        var item = mycart;
        var grand = grand_total;
        var discount = discount;
        var voucher_code = $('#voucherCode').val();
        var right_now_customer = now_customer;
        var cus_pay = $('#payable').val();

        var pay = $('#payable').val();
        var name = $('#name').val();

        var phone = $('#phone').val();
        var repaymentDate = $("#repaymentDate").val();

        var credit = $('#current_credit').val();
        var total_charges = parseInt($('#total_charges').text());
        var changes = pay - total_charges;
        //isset
        var id = $('#salescustomer_list').children("option:selected").val()
        // ? $('#salescustomer_list').children("option:selected").val() : last_row_id;
        $("#changes").text(changes);

        $("#pay").text(pay);

        $("#changes_1").text(changes);

        $("#pay_1").text(pay);

        $('#total_charges_a5').text(total_charges);

        $("#cus_name").text(name);

        $("#cus_phone").text(phone);

        $("#credit_amount").text(credit);
        $("#credit").val(credit);
        if (!pay) {
            swal({
                icon: 'error',
                title: 'ပေးငွေ ထည့်ပါ ..',
                text: 'Customer Pay cannot be null!!!',
                footer: '<a href>Why do I have this issue?</a>'
            })
        }
        // else if(pay < total_charges){
        //     swal({
        //         icon: 'error',
        //         title: 'Check Customer Pay Again!',
        //         text: 'Customer Pay must be greater than or equal Total Amount!!!',
        //         footer: '<a href>Why do I have this issue?</a>'
        //     })
        // }
        else if (id) {
            $.ajax({
                type: 'POST',
                url: '/testVoucher',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "item": item,
                    "grand": grand,
                    "voucher_code": voucher_code,
                    "sales_customer_id": id,
                    "sales_customer_name": name,
                    "credit_amount": credit,
                    "repaymentDate": repaymentDate,
                    "is_print": 1,
                    "voucher_id": parseInt(voucher_id),
                    "cus_pay": pay,
                    "editvoucher": editvoucher ?? 0 ,
                },
                success: function(data) {
                    if (data.status == 0) {
                        swal({
                            icon: 'error',
                            title: 'မှားယွင်းနေပါသည်.',
                            text: 'ပြန်စစ်ပါ..',
                        })
                    } else {

                        $('#voucherCode').val(data.voucher_code);
                        $('.vou_code').empty();
                        $('.vou_code').text(data.voucher_code);
                        localStorage.setItem('exitvoucher', JSON.stringify(data.id));
                        // clearLocalstorage(right_now_customer);
                        formReset();

                        $('#counting_unit_select').empty();

                        var item_html = ``;

                        $.each(data.items, function(i, item) {

                            if (item.shop_counting_units) {
                                $.each(item.shop_counting_units, function(j, counting_unit) {

                                    $.each(counting_unit.shop_stockcounts, function(k,
                                        stock) {

                                        if (stock.from_id == from_id) {
                                            stockcountt = stock.stock_qty;
                                        }
                                    })
                                    item_html += `
                            <option class="text-black" data-unitname="${ counting_unit.unit_name }"
                                    data-itemname="${ item.shop_item_name}"
                                    data-brandname="${item.shop_brand.name}"
                                    data-normal="${ counting_unit.normal_sale_price}"
                                    data-whole="${ counting_unit.whole_sale_price}"
                                    data-currentqty="${ stockcountt}" value="${ counting_unit.id}">
                                    ${ item.shop_item_name}-
                                    ${ item.shop_brand.name}&nbsp;&nbsp; ${ stockcountt}ခု&nbsp;&nbsp;
                                    ${ counting_unit.normal_sale_price}ကျပ်</option>
                            `;
                                })
                            }

                        })
                        var main_html = `
                        <select class="p-4 select form-control text-black" name="item" id="counting_unit_select">
                        <option></option>` + item_html + `
                        </select>
                        `;
                        var  html_foot = `
                        <tr class="text-center">
                                    <td class="text-black" colspan="4">Total Quantity
                                    </td>
                                    <td class="text-black" id="total_quantity">0</td>
                                </tr>
                                <tr class="text-center">
                                    <td class="text-black" colspan="4">Total</td>
                                    <td class="text-black" id="sub_total">0</td>
                                </tr>`;

                        $('#search_wif_typing').html(main_html);
                        $('#sale_foot').html(html_foot);
                        $("#search_wif_typing .select").select2({
                            placeholder: "ရှာရန်",
                        });

                        var mode = 'iframe'; //popup
                        var close = mode == "popup";
                        var options = {
                            mode: mode,
                            popClose: close
                        };
                        $(".tab-pane.active div.printableArea").printArea(options);
                    }
                },
            });

        } else {

            //last_row_id
            {
                $.ajax({
                    type: 'POST',
                    url: '/testVoucher',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "item": item,
                        "grand": grand,
                        "voucher_code": voucher_code,
                        "sales_customer_id": last_row_id,
                        "sales_customer_name": name,
                        "credit_amount": credit,
                        "is_print": 1,
                        "voucher_id": parseInt(voucher_id),
                        "cus_pay": pay,
                        "editvoucher": editvoucher ?? 0 ,
                        "repaymentDate": repaymentDate,
                    },
                    success: function(data) {
                        if (data.status == 0) {
                            swal({
                                icon: 'error',
                                title: 'မှားယွင်းနေပါသည်.',
                                text: 'ပြန်စစ်ပါ..',
                            })
                        } else {
                            $('#voucherCode').val(data.voucher_code);
                            $('.vou_code').empty();
                            $('.vou_code').text(data.voucher_code);
                            clearLocalstorage();
                            formReset();
                            localStorage.setItem('exitvoucher', JSON.stringify(data.id));

                            $('#counting_unit_select').empty();

                            var item_html = ``;

                            $.each(data.items, function(i, item) {

                                if (item.shop_counting_units) {
                                    $.each(item.shop_counting_units, function(j, counting_unit) {

                                        $.each(counting_unit.shop_stockcounts, function(k,
                                            stock) {

                                            if (stock.from_id == from_id) {
                                                stockcountt = stock.stock_qty;
                                            }
                                        })
                                        item_html += `
                                        <option class="text-black" data-unitname="${ counting_unit.unit_name }"
                                    data-itemname="${ item.shop_item_name}"
                                    data-brandname="${item.shop_brand.name}"
                                    data-normal="${ counting_unit.normal_sale_price}"
                                    data-whole="${ counting_unit.whole_sale_price}"
                                    data-currentqty="${ stockcountt}" value="${ counting_unit.id}">
                                    ${ item.shop_item_name}-
                                    ${ item.shop_brand.name}&nbsp;&nbsp; ${ stockcountt}ခု&nbsp;&nbsp;
                                    ${ counting_unit.normal_sale_price}ကျပ်</option>
                                `;
                                    })
                                }

                            })
                            var main_html = `
                            <select class="p-4 select form-control text-black" name="item" id="counting_unit_select">
                            <option></option>` + item_html + `
                            </select>
                            `;

                            $('#search_wif_typing').html(main_html);

                            $("#search_wif_typing .select").select2({
                                placeholder: "ရှာရန်",
                            });

                            var mode = 'iframe'; //popup
                            var close = mode == "popup";
                            var options = {
                                mode: mode,
                                popClose: close
                            };

                            $(".tab-pane.active div.printableArea").printArea(options);
                        }

                    },
                });
            }
            //end last_row
        }
    });

    function formReset() {

    $('#sale').empty();
    $('#total_quantity').empty();
    $('#sub_total').empty();
    $('#credit').val("");
    $('#gtot').val(0);
    $('#with_dis_total').val(0);
    $('#payable').val("");
    $('#current_credit').val("");
    $('#current_change').val(0);
    $('#discount_amount').val(0);
    localStorage.clear();

    }

    function getCustomerInfo(value) {

    $.ajax({

        type: 'POST',

        url: '/getCustomerInfo',

        data: {
            "_token": "{{ csrf_token() }}",
            "customer_id": value,
        },

        success: function(data) {

            $("#phone").val(data.phone);

            $("#address").val(data.address);
        },


    });
}


$('.pending-voucher').on('click', '.buttonrelative .deletevoucher', function() {
                var now_customer = $('#now_customer').val();
                var pendingvoucherno = $(this).data('pendingvoucherno');
                var cartname = "mycart_" + pendingvoucherno;
                var grand_totalname = "grandTotal_" + pendingvoucherno;

                var local_customer = localStorage.getItem('local_customer_lists');
                var local_customer_array = JSON.parse(local_customer);
                $.each(local_customer_array, function(i, v) {
                    if (v == pendingvoucherno) {
                        local_customer_array.splice(i, 1);
                    }
                })
                localStorage.setItem('local_customer_lists', JSON.stringify(local_customer_array));
                localStorage.removeItem(cartname);
                localStorage.removeItem(grand_totalname);

                if (now_customer == pendingvoucherno) {
                    localStorage.removeItem('mycart');
                    localStorage.removeItem('grandTotal');
                    $('.now_customer').hide();
                    $('#now_customer').val(0);
                    $('#total_quantity').empty();
                    $('#sub_total').empty();
                    $('#sale').empty();
                }

                local_customer_lists();
                showmodal();


            });

            function local_customer_lists() {
                var cust_name = $('#pending_cust').val();
                if (cust_name) {
                    var cust = cust_name;

                    // alert("null");
                } else {
                    var cust = "Customer";
                    // alert("has");
                }
                // alert(cust);
                var local_customer_lists = localStorage.getItem('local_customer_lists');

                var local_customer_array = JSON.parse(local_customer_lists);

                $('.pending-voucher').empty();

                $.each(local_customer_array, function(i, v) {

                    var btnpending = `

            <div class="buttonrelative mb-2">
                <button class="btn btn-warning mx-2" data-pendingvoucherno="${v}"><i class="fas fa-arrow-alt-circle-up"></i> ${cust} ${v}</button>
            <p class="bg-danger text-white deletevoucher rounded" data-pendingvoucherno="${v}">x</p>
            </div>

            `;
                    $('.pending-voucher').append(btnpending);
                })
            }

            $('.pending-voucher').on('click', '.buttonrelative button', function() {

                var pendingvoucherno = $(this).data('pendingvoucherno');

                $('#now_customer').val(pendingvoucherno);
                $('#now_customer_no').text(pendingvoucherno);

                $('.now_customer').show();
                var cartname = "mycart_" + pendingvoucherno;
                var grand_totalname = "grandTotal_" + pendingvoucherno;


                var mycart_pending_vocher = localStorage.getItem(cartname);

                var grand_total_pending_voucher = localStorage.getItem(grand_totalname);

                localStorage.setItem('mycart', mycart_pending_vocher);

                localStorage.setItem("grandTotal", grand_total_pending_voucher);

                showmodal();

            })

            function storePendingVoucher() {
                var cust_name = $('#pending_cust').val();
                if (cust_name) {
                    var cust = cust_name;

                    // alert("null");
                } else {
                    var cust = "Customer";
                    // alert("has");
                }
                var mycart = localStorage.getItem('mycart');

                var grand_total = localStorage.getItem('grandTotal');

                var nextvoucherno = parseInt(pendingvoucherno) + 1;

                var now_customer = $('#now_customer').val();

                var local_customer_lists = localStorage.getItem('local_customer_lists');
                var local_last_customer_no = localStorage.getItem('last_customer_no');
                if (!mycart) {

                    swal({
                        title: "Please Check",
                        text: "Item Cannot be Empty to Store Voucher",
                        icon: "info",
                    });

                } else {

                    if (now_customer == 0) {
                        // 0 means new customer

                        var last_customer_no = JSON.parse(local_last_customer_no);
                        var local_customer_obj = JSON.parse(local_customer_lists);

                        if (local_customer_obj) {
                            // console.log("not null ="+local_customer_obj.length);
                            // console.log("not nullllllll");
                            if (!local_customer_obj.length == 0) {
                                console.log("!local_customer_obj.length==0");
                                var pendingvoucherno = last_customer_no + 1;
                            } else {
                                var pendingvoucherno = 1;
                            }
                        } else {
                            // console.log("in null"+local_customer_obj);
                            var pendingvoucherno = 1;
                            var local_customer_obj = [];
                        }

                        localStorage.setItem('last_customer_no', JSON.stringify(pendingvoucherno));
                        local_customer_obj.push(parseInt(pendingvoucherno));
                        localStorage.setItem('local_customer_lists', JSON.stringify(local_customer_obj));
                        var cartname = "mycart_" + pendingvoucherno;
                        var grand_totalname = "grandTotal_" + pendingvoucherno;

                        var btnpending = `
        <div class="buttonrelative mb-2">
            <button class="btn btn-warning mx-2" data-pendingvoucherno="${pendingvoucherno}"><i class="fas fa-arrow-alt-circle-up"></i> ${cust} ${pendingvoucherno}</button>
        <p class="bg-danger text-white deletevoucher rounded" data-pendingvoucherno="${pendingvoucherno}">x</p>
        </div>

        `;
                        $('.pending-voucher').append(btnpending);
                    } else {
                        var cartname = "mycart_" + now_customer;
                        var grand_totalname = "grandTotal_" + now_customer;

                    }
                    localStorage.setItem(cartname, mycart);
                    localStorage.setItem(grand_totalname, grand_total);

                    localStorage.removeItem('mycart');
                    localStorage.removeItem('grandTotal');
                    $('.now_customer').hide();
                    $('#now_customer').val(0);
                    $('#total_quantity').empty();
                    $('#sub_total').empty();
                    $('#sale').empty();
                    showmodal();

                }

                function local_customer_lists() {

                    var local_customer_lists = localStorage.getItem('local_customer_lists');

                    var local_customer_array = JSON.parse(local_customer_lists);

                    $('.pending-voucher').empty();

                    $.each(local_customer_array, function(i, v) {

                        var btnpending = `

            <div class="buttonrelative mb-2">
                <button class="btn btn-warning mx-2" data-pendingvoucherno="${v}"><i class="fas fa-arrow-alt-circle-up"></i> Customer${v}</button>
            <p class="bg-danger text-white deletevoucher rounded" data-pendingvoucherno="${v}">x</p>
            </div>

            `;
                        $('.pending-voucher').append(btnpending);
                    })
                }

            }



            $(".store_voucher").click(function() {
                // alert("hello");

                var custphone = $('#custphone').val();

                var from_id = $('#fid').val();

                var exitvoucher = localStorage.getItem('exitvoucher');
                if (exitvoucher == null) {
                    voucher_id = 0;
                } else {
                    voucher_id = exitvoucher;
                }


                var salecustomer_id = $('#salescustomer_list').children("option:selected").val();

                var now_customer = $('#now_customer').val();

                var mycart = localStorage.getItem('mycart');

                var grand_total = localStorage.getItem('grandTotal');
                var editvoucher = localStorage.getItem('editvoucher');

                var item = mycart;
                var grand = grand_total;
                var discount = discount;
                var voucher_code = $('#voucherCode').val();
                var right_now_customer = now_customer;
                var cus_pay = $('#payable').val();

                // alert(vou_Dis);

                var repaymentDate = $('#repaymentDate').val();

                var name = $('#name').val();

                var id = $('#salescustomer_list').children("option:selected").val();

                var credit = $('#current_credit').val();
                // alert(id);
                if (!cus_pay) {
                    swal({
                        icon: 'error',
                        title: 'ပေးငွေ ထည့်ပါ!',
                        text: 'Customer Pay cannot be null!!!',
                        footer: '<a href>Why do I have this issue?</a>'
                    })
                } else if (id) {
                    // alert("in");
                    $.ajax({
                        type: 'POST',
                        url: '/testVoucher',
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "item": item,
                            "grand": grand,
                            "voucher_code": voucher_code,
                            "sales_customer_id": id,
                            "sales_customer_name": name,
                            "credit_amount": credit,
                            "repaymentDate": repaymentDate,
                            "voucher_id": parseInt(voucher_id),
                            "cus_pay": cus_pay,
                            "editvoucher": editvoucher ?? 0 ,
                        },
                        success: function(data) {
                            if (data.status == 0) {
                                swal({
                                    icon: 'error',
                                    title: 'မှားယွင်းနေပါသည်.',
                                    text: 'ပြန်စစ်ပါ..',
                                })
                            } else {
                                $('#voucherCode').val(data.voucher_code);
                                $('.vou_code').empty();
                                $('.vou_code').text(data.voucher_code);
                                localStorage.setItem('exitvoucher', JSON.stringify(data.id));
                                clearLocalstorage(right_now_customer);
                                formReset();
                                $('#counting_unit_select').empty();

                                var item_html = ``;

                                $.each(data.items, function(i, item) {

                                    if (item.shop_counting_units) {
                                        $.each(item.shop_counting_units, function(j, counting_unit) {

                                            $.each(counting_unit.shop_stockcount, function(k,
                                                stock) {

                                                if (stock.from_id == from_id) {
                                                    stockcountt = stock.stock_qty;
                                                }
                                            })
                                            item_html += `
                                    <option class="text-black" data-unitname="${counting_unit.unit_name}"
                                        data-itemname="${item.shop_item_name }"
                                        data-normal="${counting_unit.normal_sale_price }"
                                        data-whole="${counting_unit.whole_sale_price }"
                                        data-order="${counting_unit.order_price }" data-currentqty="${ stockcountt }"
                                        value="${counting_unit.id }">${item.shop_item_name }-${item.shop_brand.name }-
                                        ${counting_unit.unit_name }&nbsp;&nbsp; ${stockcountt}ခု&nbsp;&nbsp;
                                        ${counting_unit.normal_sale_price}ကျပ်</option>
                                    `;
                                        })
                                    }

                                })
                                var main_html = `
                                <select class="p-4 select form-control text-black" name="item" id="counting_unit_select">
                                <option></option>` + item_html + `
                                </select>
                                `;

                                $('#search_wif_typing').html(main_html);

                                $("#search_wif_typing .select").select2({
                                    placeholder: "ရှာရန်",
                                });
                                swal({
                                    icon: 'success',
                                    title: 'သိမ်းဆည်းပြီး!',
                                    text: 'Voucher သိမ်းဆည်းပြီးပါပြီ!!',
                                    button: false,
                                    timer: 1500,
                                })
                            }



                        }
                    });
                } else {
                    //last_row_id
                    // alert("out");
                    $.ajax({
                        type: 'POST',
                        url: '/testVoucher',
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "item": item,
                            "grand": grand,
                            "voucher_code": voucher_code,
                            "sales_customer_id": last_row_id,
                            "sales_customer_name": name,
                            "credit_amount": credit,
                            "voucher_id": parseInt(voucher_id),
                            "cus_pay": cus_pay,
                            "editvoucher": editvoucher ?? 0 ,
                        },
                        success: function(data) {
                            if (data.status == 0) {
                                swal({
                                    icon: 'error',
                                    title: 'မှားယွင်းနေပါသည်.',
                                    text: 'ပြန်စစ်ပါ..',
                                })
                            } else {
                                $('#voucherCode').val(data.voucher_code);
                                $('.vou_code').empty();
                                $('.vou_code').text(data.voucher_code);
                                localStorage.setItem('exitvoucher', JSON.stringify(data.id));
                                clearLocalstorage(right_now_customer);
                                formReset();
                                $('#counting_unit_select').empty();

                                var item_html = ``;

                                $.each(data.items, function(i, item) {

                                    if (item.shop_counting_units) {
                                        $.each(item.shop_counting_units, function(j, counting_unit) {

                                            $.each(counting_unit.shop_stockcount, function(k,
                                                stock) {

                                                if (stock.from_id == from_id) {
                                                    stockcountt = stock.stock_qty;
                                                }
                                            })
                                            item_html += `
                                    <option class="text-black" data-unitname="${counting_unit.unit_name}"
                                        data-itemname="${item.shop_item_name }"
                                        data-normal="${counting_unit.normal_sale_price }"
                                        data-whole="${counting_unit.whole_sale_price }"
                                        data-order="${counting_unit.order_price }" data-currentqty="${ stockcountt }"
                                        value="${counting_unit.id }">${item.shop_item_name }-${item.shop_brand.name }
                                        ${counting_unit.unit_name }&nbsp;&nbsp; ${stockcountt}ခု&nbsp;&nbsp;
                                        ${counting_unit.normal_sale_price}ကျပ်</option>
                                    `;
                                        })
                                    }

                                })
                                var main_html = `
                    <select class="p-4 select form-control text-black" name="item" id="counting_unit_select">
                        <option></option>` + item_html + `
                    </select>
                            `;

                                $('#search_wif_typing').html(main_html);

                                $("#search_wif_typing .select").select2({
                                    placeholder: "ရှာရန်",
                                });

                                swal({
                                    icon: 'success',
                                    title: 'သိမ်းဆည်းပြီး!',
                                    text: 'Voucher သိမ်းဆည်းပြီးပါပြီ!!',
                                    button: false,
                                    timer: 1500,
                                })
                            }

                        }
                    });
                    //end last_row_id
                }
            });

        </script>

    @endsection
