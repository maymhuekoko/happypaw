@extends('master')

@section('title','Shop Counting Unit List')

@section('content')

<style>
    .btn {
    width: 100px;
    overflow: hidden;
    white-space: nowrap;
  }
</style>

<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">
        <h4 class="font-weight-normal">Counting Unit List</h4>
    </div>
</div>


<div class="row">
    <div class="col-md-9">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title">{{$item->item_name}}'s Unit List </h4>
                <div class="table-responsive text-black">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Unit Code</th>
                                <th>Unit Original Code</th>
                                <th>Unit Name</th>
                                <th style="overflow:hidden;white-space: nowrap;">Current Quantity</th>
                                <th style="overflow:hidden;white-space: nowrap;">Reorder Quantity</th>
                                <th style="overflow:hidden;white-space: nowrap;">Normal Sale Price</th>
                                <th style="overflow:hidden;white-space: nowrap;">Whole Sale Price</th>
                                <th style="overflow:hidden;white-space: nowrap;">Purchase Price</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1;?>

                            @foreach($units as $unit)
                            <tr>
                                <td>{{$i++}}</td>

                                <td style="overflow:hidden;white-space: nowrap;">{{$unit->unit_code}}</td>
                                <td style="overflow:hidden;white-space: nowrap;">{{$unit->original_code}}</td>
                                <td style="overflow:hidden;white-space: nowrap;">{{$unit->unit_name}}</td>
                                <td>{{$unit->current_quantity}}</td>
                                <td>{{$unit->reorder_quantity}}</td>
                                <td>{{$unit->normal_sale_price}}</td>
                                <td>{{$unit->whole_sale_price}}</td>
                                <td>{{$unit->purchase_price}}</td>
                                <td style="text-overflow: ellipsis; white-space: nowrap;">
                                    <a href="#" class="btn btn-outline-info" data-toggle="modal" data-target="#unit_code{{$unit->id}}">
                                    Add Code</a>

                                    <a href="#" class="btn btn-outline-info" data-toggle="modal" data-target="#original_code{{$unit->id}}">
                                    Add Original Code</a>

                                    <a href="#" class="btn btn-outline-warning" data-toggle="modal" data-target="#edit_item{{$unit->id}}">
                                        <i class="fas fa-edit"></i></a>

                                    <a href="#" class="btn btn-outline-danger" onclick="ApproveLeave('{{$unit->id}}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>

                                <div class="modal fade" id="unit_code{{$unit->id}}" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Unit Code Form</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                    <div class="modal-body">
                                        <form class="form-material" method="post" action="">
                                            @csrf

                                            <div class="row jusitify-content-center">
                                                <div class="form-group col-12">
                                                    <label class="font-weight-bold">Unit Code</label>
                                                    <input type="text" name="code" class="form-control" value="{{$unit->unit_code}}">
                                                </div>

                                            </div>

                                            <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary" value="@lang('lang.update_counting_unit')">
                                        </form>
                                    </div>

                                  </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="original_code{{$unit->id}}" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Original Code Form</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                    <div class="modal-body">
                                        <form class="form-material" method="post" action="">
                                            @csrf

                                            <div class="row jusitify-content-center">
                                                <div class="form-group col-12">
                                                    <label class="font-weight-bold">Unit Original Code</label>
                                                    <input type="text" name="code" class="form-control" value="{{$unit->original_code}}">
                                                </div>

                                            </div>

                                            <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary" value="@lang('lang.update_counting_unit')">
                                        </form>
                                    </div>

                                  </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="edit_item{{$unit->id}}" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">@lang('lang.edit_counting_unit_form')</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                    <div class="modal-body">
                                        <form class="form-material" method="post" action="">
                                            @csrf

                                            <div class="row jusitify-content-center">
                                                <div class="form-group col-12">
                                                    <label class="font-weight-bold">Unit Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{$unit->unit_name}}">
                                                </div>
                                                <div class="form-group col-12">
                                                    <label class="font-weight-bold">Current Quantity</label>
                                                    <input type="number" name="current_quantity" class="form-control" value="{{$unit->current_quantity}}">
                                                </div>
                                                <div class="form-group col-12">
                                                    <label class="font-weight-bold">Reorder Quantity</label>
                                                    <input type="number" name="reorder_quantity" class="form-control" value="{{$unit->reorder_quantity}}">
                                                </div>
                                                <div class="form-group col-12">
                                                    <label class="font-weight-bold">Purchase price</label>
                                                    <input type="number" name="purchase_price" class="form-control" value="{{$unit->purchase_price}}">
                                                </div>
                                                <div class="form-group col-4">
                                                    <label class="font-weight-bold">Normal Sale Price</label>
                                                    <input type="number" name="normal_price" id="normal_price" class="form-control" value="{{$unit->normal_sale_price}}">
                                                    <input type="hidden" id="unchange_normal" value="{{$unit->normal_sale_price}}">
                                                </div>
                                                <div class="form-group col-4">
                                                    <input type="checkbox" class="custom-control-input" name="normal_fixed_flash" id="normal_fixed_flash" value="1"
                                                   @if ($unit->normal_fixed_flash==1)
                                                       checked
                                                   @endif
                                                    >
                                                    <label class="font-weight-bold" for="normal_fixed_flash">Fiexd ထားရန်</label>

                                                </div>
                                                <div class="form-group col-4">
                                                    <label class="font-weight-bold">percent (%)</label>
                                                    <input type="number" name="normal_fixed_percent" id="normal_fixed_percent" class="form-control" value="{{$unit->normal_fixed_percent}}">
                                                </div>
                                                <div class="form-group col-4">
                                                    <label class="font-weight-bold">Wholesale Price</label>
                                                    <input type="number" name="whole_price" id="whole_price" class="form-control" value="{{$unit->whole_sale_price}}">
                                                    <input type="hidden" id="unchange_whole" value="{{$unit->whole_sale_price}}">

                                                </div>
                                                <div class="form-group col-4">
                                                    <input type="checkbox" class="custom-control-input" id="whole_fixed_flash" name="whole_fixed_flash" value="1"
                                                    @if ($unit->whole_fixed_flash==1)
                                                    checked
                                                    @endif
                                                >
                                                    <label class="font-weight-bold" for="whole_fixed_flash">Fiexd ထားရန်</label>

                                                </div>
                                                <div class="form-group col-4">
                                                    <label class="font-weight-bold">percent (%)</label>
                                                    <input type="number" name="whole_fixed_percent" id="whole_fixed_percent" class="form-control" value="{{$unit->whole_fixed_percent}}">
                                                </div>

                                            </div>

                                            <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary" value="Update Counting Unit">
                                        </form>
                                    </div>

                                  </div>
                                    </div>
                                </div>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="card-title">Unit Create Form</h3>
                <form class="form-material m-t-40" method="post" action="{{route('shop_count_unit_store')}}">
                    @csrf
                    <input type="hidden" value="{{$item->id}}" name="item_id">

                    <div class="form-group">
                        <label class="font-weight-bold">Unit Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter Unit Name">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Current Quantity</label>
                        <input type="number" name="current_qty" class="form-control" placeholder="Enter Current Quantity">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Reorder Quantity</label>
                        <input type="number" name="reorder_qty" class="form-control" placeholder="Enter Reorder Quantity">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Purchase Price</label>
                        <input type="number" name="purchase_price" id="purchase_prc" class="form-control" placeholder="Enter Purchase Price">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Normal Sale Price</label>
                        <input type="number" name="normal_price" class="form-control calculatepercent" data-type="normal" data-action="pricetopercent" placeholder="Enter Normal Sale Price"
                        id="normal_perprice"
                        >
                    </div>
                    <div class="form-group row">
                        <div class=" col-6">
                            <input type="checkbox" class="custom-control-input" name="normal_fixed" id="normal_fixed" value="1"
                            >
                            <label class="font-weight-bold" for="normal_fixed">Fiexd</label>
                        </div>
                        <div class=" col-6">
                            <label class="font-weight-bold">%</label>
                            <input type="number" name="normal_percent" class="form-control calculatepercent" data-action="percenttoprice" data-type="normal" id="normal_percent">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Whole Sale Price</label>
                        <input type="number" name="whole_price" class="form-control calculatepercent" data-type="whole" data-action="pricetopercent" placeholder="Enter Wholesale Price"
                        id="whole_perprice"
                        >
                    </div>
                    <div class="form-group row">
                        <div class=" col-6">
                            <input type="checkbox" class="custom-control-input" name="whole_fixed" id="whole_fixed" value="1"
                            >
                            <label class="font-weight-bold" for="whole_fixed">Fiexd</label>
                        </div>
                        <div class=" col-6">
                            <label class="font-weight-bold">%</label>
                            <input type="number" name="whole_percent" id="whole_percent" class="form-control calculatepercent" data-action="percenttoprice" data-type="whole">
                        </div>
                    </div>

                    <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary" value="Save">
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>

    $(document).ready(function(){

        $(".select2").select2();
//in countingunit edit form
        $('#normal_fixed_percent').keyup(function(){
            var normal_fixed_percent = $('#normal_fixed_percent').val();
           var unchange_normal= $('#unchange_normal').val();
           var normal_with_percent = parseInt(unchange_normal) +(unchange_normal * (normal_fixed_percent/100));
           $('#normal_price').empty();
           $('#normal_price').val(Math.round(normal_with_percent));
        })

        $('#whole_fixed_percent').keyup(function(){
            var whole_fixed_percent = $('#whole_fixed_percent').val();
           var unchange_whole= $('#unchange_whole').val();
           var whole_with_percent = parseInt(unchange_whole) +(unchange_whole * (whole_fixed_percent/100));
           $('#whole_price').empty();
           $('#whole_price').val(Math.round(whole_with_percent));
        })

        $('#order_fixed_percent').keyup(function(){
            var order_fixed_percent = $('#order_fixed_percent').val();
           var unchange_order= $('#unchange_order').val();
           var order_with_percent = parseInt(unchange_order) +(unchange_order * (order_fixed_percent/100));
           $('#order_price').empty();
           $('#order_price').val(Math.round(order_with_percent));
        })
//in countingunit create form
        $('.calculatepercent').keyup(function(){
            var type = $(this).data('type');
            var action = $(this).data('action');
            var value = $(this).val();
            var purchase_prc = $('#purchase_prc').val();


            if(action=="pricetopercent"){
                var percent = ((value-purchase_prc)/purchase_prc)*100;
                var id = type+"_percent";
                $("#"+id).val(Math.round(percent));
            }
            else{
                var price = parseInt(purchase_prc) +(purchase_prc * (value/100));
                var id = type+"_perprice";
                $("#"+id).val(price);
            }

        })

    });

    function ApproveLeave(value){

        var unit_id = value;

        swal({
            title: "@lang('lang.confirm')",
            icon:'warning',
            buttons: ["@lang('lang.no')", "@lang('lang.yes')"]
        })

      .then((isConfirm)=>{

        if(isConfirm){

          $.ajax({
              type:'POST',
                url:'delete',
                dataType:'json',
                data:{
                  "_token": "{{ csrf_token() }}",
                  "unit_id": unit_id,
                },

              success: function(){

                      swal({
                            title: "@lang('lang.success')!",
                            text : "@lang('lang.successfully_deleted')!",
                            icon : "success",
                        });

                        setTimeout(function(){
               window.location.reload();
            }, 1000);


                    },
                });
        }
      });

    }

</script>
@endsection
