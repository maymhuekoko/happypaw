@extends('master')

@section('title','Purchase Details')

@section('place')

{{-- <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">@lang('lang.purchase') @lang('lang.details')</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.back_to_dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('lang.purchase') @lang('lang.details')</li>
    </ol>
</div> --}}

@endsection

@section('content')

<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">
        <h4 class="font-weight-normal">Purchase Detail Page</h4>
    </div>
</div>

<div class="row">
    <div class="col-md-14">
        <div class="card shadow">
            <div class="card-header">
                <h4 class="font-weight-bold mt-2">Purchase Detail</h4>
            </div>
            <div class="card-body">

            	<div class="row">
            		<div class="col-md-6">

            			<div class="row">
			              	<div class="font-weight-bold text-primary col-md-5">Purchase Date</div>
			              	<h5 class="font-weight-bold col-md-4 mt-1">
			              		{{date('d-m-Y', strtotime($purchase->purchase_date))}}
			              	</h5>
				        </div>

				        <div class="row mt-1">
			              	<div class="font-weight-bold text-primary col-md-5">Total Price</div>
			              	@php
			              	    $backup_total = 0;
			              	    $sub_total = 0;
                                $k = 0;
			              	    foreach($shop_counting_unit as $unit){
			              	        $sub_total = $shop_unit[$k++]->quantity * $unit->purchase_price;
			              	        $backup_total += $sub_total;
			              	    }
			              	@endphp
			              	<h5 class="font-weight-bold col-md-4 mt-1">{{($purchase->total_price==0)? $backup_total : $purchase->total_price}} ကျပ်</h5>
				        </div>

				        <div class="row mt-1">
			              	<div class="font-weight-bold text-primary col-md-5">Total Quantity</div>
			              	<h5 class="font-weight-bold col-md-4 mt-1">{{$purchase->total_quantity}}</h5>
				        </div>

				        <div class="row mt-1">
			              	<div class="font-weight-bold text-primary col-md-5">Supplier Name</div>
			              	<h5 class="font-weight-bold col-md-4 mt-1">{{$purchase->supplier_name}}</h5>
				        </div>

				        <div class="row mt-1">
			              	<div class="font-weight-bold text-primary col-md-5">Purchase By</div>
			              	<h5 class="font-weight-bold col-md-4 mt-1">Purchaser</h5>
				        </div>


            		</div>
					<div class="col-md-5">
						<form action="{{route('purchase_delete')}}" method="POST">
							@csrf
							<input type="hidden" name="purchase_id" value="{{$purchase->id}}">
							{{-- <button class="btn btn-danger float-right">Delete</button> --}}

						</form>
					</div>
            		<div class="col-md-7" style="margin-left:auto;margin-right:auto;">
            			<h4 class="font-weight-bold mt-2 text-primary text-center">Purchase Unit</h4>
            			<div class="table-responsive text-black">
		                    <table class="table" id="example23" >
		                        <thead>
		                            <tr>
		                                <th>Index</th>
		                                <th>Item Name</th>
		                                <th>Purchase Qty</th>
		                                <th>Purchase Price</th>
		                                <th>Sub Total</th>
		                            </tr>
		                        </thead>
		                        <tbody id="units_table">
		                            @php
                                        $i = 1 ;
                                        $j = 0;
                                        $m = 0;
                                        $n = 0;
                                    @endphp

		                            @foreach($shop_counting_unit as $unit)
		                                <tr>
		                                    <td>{{$i++}}</td>
		                                	<td>{{$unit->unit_name}}</td>
											<td class="w-100">

												<input type="number" class="form-control w-100 purchaseinput text-black" data-purchaseinput="purchaseinput{{$unit->id}}" data-olderqty="{{$shop_unit[$m++]->quantity}}"
												data-purchaseid="{{$purchase->id}}" id="purchaseinput{{$unit->id}}" data-id="{{$unit->id}}" value="{{$shop_unit[$n++]->quantity}}">

											</td>

		                                	<td>{{$unit->purchase_price}}</td>
		                                	<td>{{$shop_unit[$j++]->quantity * $unit->purchase_price}}</td>
		                                </tr>
		                            @endforeach
		                        </tbody>
		                    </table>
		                </div>
            		</div>

            	</div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')

<script>

    $('#units_table').on('keypress','.purchaseinput',function(){
        var keycode= (event.keyCode ? event.keyCode : event.which);
        if(keycode=='13'){

            var new_qty = $(this).val();
            var unit_id= $(this).data('id');
            var olderqty = $(this).data('olderqty');
            var purchase_id = $(this).data('purchaseid');
            $.ajax({

                type:'POST',

                url:'{{route('purchaseupdate-ajax')}}',

                data:{
                    "_token":"{{csrf_token()}}",
                    "new_qty": new_qty,
                    "olderqty": olderqty,
                    "unit_id":unit_id,
					"purchase_id": purchase_id
                },

                success:function(data){
                    if(data){
                        swal({
                            toast:true,
                            position:'top-end',
                            title:"Success",
                            text:"Stock Changed!",
                            button:false,
                            timer:500,
                            icon:"success"
                        });
                        $(`#purchaseinput${unit_id}`).addClass("is-valid");
						setTimeout(function(){
						window.location.reload();
						}, 1000);
                    }
                    else{
                        swal({
                            toast:true,
                            position:'top-end',
                            title:"Error",
                            button:false,
                            timer:1500
                        });
                        $(`#${stockinputid}`).addClass("is-invalid");
                    }
                },
                });
        }
    })



</script>
@endsection
