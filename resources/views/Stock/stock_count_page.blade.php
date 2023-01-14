@extends('master')

@section('title','Stock Count')

@section('content')
@php
$from_id = session()->get('from')
@endphp 
<input type="hidden" id="isowner" value="{{session()->get('user')->role}}">
<input type="hidden" id="isshop" value="{{session()->get('from')}}">
<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">        
        <h2 class="font-weight-bold">stock counts</h2>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">               
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label text-black">ဆိုင်ရွေးရန်</label>
                            <select class="form-control select2" onchange="getItems(this.value)" id="shop_id">
                                @foreach($shops as $shop)
                                <option value="{{$shop->id}}"
                                @if ($from_id==$shop->id)
                                    selected
                                @endif
                                >{{$shop->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label text-black">select item</label>
                            <select class="form-control select2" id="item_list">
                                <option></option>
                                @foreach ($items as $item)
                                <option value="{{$item->id}}">{{$item->item_name}}</option>
                                @endforeach
                            </select>                            
                        </div>
                    </div>
                </div>

                <div class="row justify-content-end">
                    <button class="btn btn-info" onclick="checkUnit()"> 
                        <i class="fa fa-check"></i> check unit
                    </button>
                </div>
    
            </div>        
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">

        <div class="card card-outline-info">
            <div class="card-header">
                <h4 class="m-b-0 text-white">counting unit list</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive text-black">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>category name</th>
                                <th>item name</th>
                                <th>unit name</th>
                                <th>current quantity</th>
                                <th>reorder quantity</th>
                                @if(session()->get('user')->role == "Owner")
                                <th>action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="units_table">
                            @foreach($items as $item)
                                @foreach ($item->counting_units as $unit)
                                <tr>
                                    <td>{{$item->category->category_name}}</td>
                                    <td>{{$item->item_name}}</td>
                                    <td>{{$unit->unit_name}}</td>
                                    @foreach ($unit->stockcount as $key=>$stockcount)
                                        @php
                                            if($unit->stockcount[$key]->from_id== $from_id){
                                                $stockcountt= $unit->stockcount[$key]->stock_qty;
                                            }
                                        @endphp
                            
                                    @endforeach
                                    <td>
                                        <input type="number" class="form-control w-25 stockinput text-black" data-stockinputid="stockinput{{$unit->id}}" id="stockinput{{$unit->id}}" data-id="{{$unit->id}}"value="{{$stockcountt}}">
                                    </td>
                                    <td>{{$unit->reorder_quantity}}</td>
                                    @if(session()->get('user')->role == "Owner")
                                    <td> 
                                        <a href="#" class="btn btn-outline-warning" onclick="getModal({{$unit->id}})">
                                            update
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                       
                            @endforeach

                            <div class="modal fade" id="edit_unit_qty" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">update counting unit quantity form</h4>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                        </div>

                                        <div class="modal-body">
                                            <form class="form-horizontal m-t-40" method="post" action="{{route('update_stock_count')}}">
                                                @csrf
                                                <input type="hidden" name="unit_id" id="unit_id">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-6 text-black">counting unit quantity</label>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" name="quantity"> 
                                                        
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-6 text-black">reorder level</label>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" name="reorder"> 
                                                        
                                                    </div>
                                                </div>

                                                <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary" value="save">
                                            </form>           
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div>
</div>
@endsection

@section('js')

<script>

    $(document).ready(function(){

        $(".select2").select2();
        $("#item_list").select2({
            placeholder:"ကုန်ပစ္စည်း ရှာရန်",
        });
    });

    function getItems(value){

        var shop_id = value;

        $.ajax({

            type:'POST',

            url:'{{route('AjaxGetItem')}}',

            data:{
                "_token":"{{csrf_token()}}",
                "shop_id": shop_id,           
            },

            success:function(data){
                $('#item_list').empty();             

                $('#item_list').append($('<option>').text("ရှာရန်").attr('value', ""));
                var html = "";
                $.each(data, function(i, value) {

                $('#item_list').append($('<option>').text(value.item_name).attr('value', value.id));
                
                $.each(value.counting_units,function(j,unit){
                    var stockcountt=0;
                    $.each(unit.stockcount,function(k,stock){
                        if(stock.from_id==shop_id){
                             stockcountt= unit.stockcount[k].stock_qty;
                            console.log('stockcount',unit.stockcount[k].stock_qty);
                        }
                    })
                    html += `
                    <tr>
                                    <td>${value.category.category_name}</td>
                                    <td>${value.item_name}</td>
                                    <td>${unit.unit_name}</td>
                                    <td>
                                        <input type="number" class="form-control w-25 stockinput text-black" data-stockinputid="stockinput${unit.id}" id="stockinput${unit.id}" data-id="${unit.id}" value="${stockcountt}">
                                        </td>
                                    <td>${unit.reorder_quantity}</td>
                                    <td> 
                                        <a href="#" class="btn btn-outline-warning" onclick="getModal(${unit.id})">
                                            update
                                        </a>
                                    </td>
                                </tr>
                    `;
                });    
                

            }),
            $('#units_table').empty();
            $('#units_table').html(html); 
            swal({
                toast:true,
                position:'top-end',
                title:"Success",
                text:"Shop Changed!",
                button:false,
                timer:500  
            }); 
        }

    })
}


    function checkUnit(){
        //shop id for owner . isshop for counter
        let shop_id = $('#shop_id').val() ?? $('#isshop').val();

        let item = $('#item_list').val();

        $('#units_table').empty();

        $.ajax({

            type:'POST',

            url:'{{route('AjaxGetCountingUnit')}}',

            data:{
                "_token":"{{csrf_token()}}",
                "item": item,
                "shop_id":shop_id
            },

            success:function(data){
                $.each(data , function(i, value) {
                    var stockcountt=0;
                    $.each(value.stockcount,function(k,stock){
                        if(stock.from_id==shop_id){
                             stockcountt= stock.stock_qty;
                            console.log('stockcount',stock.stock_qty);
                        }
                    })
                    let button = `<a  href="#" class="btn btn-outline-warning" onclick="getModal(${value.id})">Edit</a>`;
                    
                    let inputstock = `<input type="number" class="form-control w-25 stockinput text-black" data-stockinputid="stockinput${value.id}" id="stockinput${value.id}" data-id="${value.id}" value="${stockcountt}">`;
                    // if(isowner == "Owner"){
                        $('#units_table').append($('<tr>')).append($('<td>').text(value.item.category.category_name)).append($('<td>').text(value.item.item_name)).append($('<td>').text(value.unit_name)).append($('<td>').append(inputstock)).append($('<td>').append(value.reorder_quantity)).append($('<td>').append($(button)));
                    // }
                    // else{
                    //     $('#units_table').append($('<tr>')).append($('<td>').text(value.item.category.category_name)).append($('<td>').text(value.item.item_name)).append($('<td>').text(value.unit_name)).append($('<td>').append(stockcountt)).append($('<td>').append(value.reorder_quantity));
                    // }
         
                });


                
            },
        });

    }

    function getModal(value){

        event.preventDefault()

        $("#edit_unit_qty").modal("show");

        $("#unit_id").attr('value', value);
    }
    
    $('#units_table').on('keypress','.stockinput',function(){
        var keycode= (event.keyCode ? event.keyCode : event.which);
        if(keycode=='13'){
            var shop_id = $('#shop_id option:selected').val();
            var stock_qty = $(this).val();
            var unit_id= $(this).data('id');
            var stockinputid = $(this).data('stockinputid');
            $.ajax({

                type:'POST',

                url:'{{route('stockupdate-ajax')}}',

                data:{
                    "_token":"{{csrf_token()}}",
                    "stock_qty": stock_qty,
                    "shop_id":shop_id,
                    "unit_id":unit_id
                },

                success:function(data){
                    if(data){
                        swal({
                            toast:true,
                            position:'top-end',
                            title:"Success",
                            text:"Stock Changed!",
                            button:false,
                            icon:"success",
                            timer:500  
                        });
                        $(`#${stockinputid}`).addClass("is-valid");
                        $(`#${stockinputid}`).blur();
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