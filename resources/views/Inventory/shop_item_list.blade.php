@extends('master')

@section('title','Shop Item List')

@section('content')

<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">
        <h4 class="font-weight-normal">Shop Item List</h4>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">

                <div class="row">
                    <div class="col-md-6">
                        <form action="" enctype="multipart/form-data" method="POST">
                            @csrf
                            <input type="file" name="select_file">
                            <button type="submit" class="btn btn-danger">Import</button>
                        </form>
                    </div>

                    <div class="col-md-6">
                        <a href="#" class="btn btn-outline-primary float-right" data-toggle="modal" data-target="#create_item">
                            <i class="fas fa-plus"></i>
                           Add Item
                        </a>
                    </div>

                </div>



                <div class="modal fade" id="create_item" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Item Create Form</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <form class="form-material m-t-40" method="post" action="{{route('shop_item_store')}}" enctype='multipart/form-data'>
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Category</label>
                                                    <select class="form-control select2 m-b-10" name="category_id" id="category" style="width: 100%" onchange="showSubCategory(this.value)">
                                                        <option value="">Select</option>
                                                        @foreach($categories as $category)
                                                        <option value="{{$category->id}}">{{$category->category_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">SubCategory</label>
                                                    <select class="form-control select2" style="width: 100%" id="subcategory" name="sub_category_id" onchange="showBrand(this.value)">
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Brand</label>
                                                    <select class="form-control select2" style="width: 100%" id="brand" name="brand_id"  onchange="showType(this.value)">
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Type</label>
                                                    <select class="form-control select2" style="width: 100%" id="type" name="type_id">
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mt-2">
                                                <label class="control-label">Customer Console</label>
                                                <div class="switch">
                                                    <label>On
                                                        <input type="checkbox" checked name="customer_console"><span class="lever"></span>Off</label>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Item Photo</label>
                                                    <input type="file" name="photo_path" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Item Code</label>
                                                    <input type="text" name="item_code" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Item Name</label>
                                                    <input type="text" name="item_name" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-actions">
                                            <div class="row">
                                                <div class=" col-md-9">
                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                    <button type="button" class="btn btn-inverse" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">Category</label>
                        <select class="form-control" onchange="showRelatedSubCategory(this.value)">
                            <option value="">Select</option>
                            @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->category_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="control-label">SubCategory</label>
                        <select class="form-control" id="subcategory" onchange="showRelatedItemList(this.value)">
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">

                        <ul class="nav nav-pills m-t-30 m-b-30 ">
                            <li class="nav-item">
                                <a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">
                                    Option One
                                </a>
                            </li>
                            <li class=" nav-item">
                                <a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false">
                                    Option Two
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content br-n pn">
                            <div id="navpills-2" class="tab-pane">
                            <button id="print" style="margin-top: -60px;" class="btn btn-info float-right" type="button">
                                <span class="float-right"><i class="fa fa-print"></i>Print</span>
                            </button>
                            <div class="clearfix"></div>
                                <div class="row">



                                    <div class="col-md-12">
                                        <div class="card shadow">
                                            <div class="card-body printableArea">
                                                <h4 class="card-title">Inventory List</h4>
                                                <p class="card-title float-right text-primary">Inventory Total<span style="font-weight:400" class="text-dark" id="inventorytotal"></span></p>

                                                <div class="table-responsive text-black">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th style="overflow:hidden;white-space: nowrap;">Item Name</th>
                                                                <th>Unit Name</th>
                                                                <th style="overflow:hidden;white-space: nowrap;">Current Quantity</th>

                                                                <!-- <th class="groundqty" style="overflow:hidden;white-space: nowrap;">@lang('lang.ground') @lang('lang.quantity')</th>
                                                                <th class="groundqty" style="overflow:hidden;white-space: nowrap;">@lang('lang.differences')</th> -->

                                                                <th style="overflow:hidden;white-space: nowrap;">Purchase Price</th>
                                                                <th style="overflow:hidden;white-space: nowrap;">SubTotal</th>

                                                            </tr>
                                                        </thead>
                                                        <br>
                                                        <tbody>
                                                            <?php $i = 1; $alltotal=0?>

                                                            @foreach($units as $unit)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td style="overflow:hidden;white-space: nowrap;">{{$unit->shop_item->shop_item_name}}</td>
                                                                <td style="overflow:hidden;white-space: nowrap;">{{$unit->unit_name}}</td>
                                                                <td>{{$unit->current_quantity}}</td>
                                                                <!-- <td class="groundqty"></td>
                                                                <td class="groundqty"></td> -->
                                                                <td>{{$unit->purchase_price}}</td>
                                                                <td>
                                                                    <?php
                                                                    $subtotal=$unit->purchase_price * $unit->current_quantity;
                                                                   echo $subtotal;
                                                                   ?>

                                                                </td>

                                                            </tr>
                                                            <?php
                                                            $alltotal+=$subtotal;
                                                            ?>
                                                            @endforeach
                                                          <input type="hidden" id="alltotal" value="<?php echo $alltotal ?>">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div id="navpills-1" class="tab-pane active">
                                <div class="table-responsive text-black">
                                    <table class="table" id="example23">
                                        <thead>
                                            <tr>
                                                <th>Item Code</th>
                                                <th>Item Name</th>
                                                <th>Related Category</th>
                                                <th>Related SubCategory</th>
                                                <th>Unit List</th>
                                                <th>Unit Conversation</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="item">
                                            @foreach($item_lists as $item)
                                            <tr>
                                                <td>{{$item->shop_item_code}}</td>
                                                <td>{{$item->shop_item_name}}</td>
                                                <td>{{$item->shop_category->category_name ?? "Default Category"}}</td>
                                                <td>{{$item->shop_sub_category->name??""}}</td>
                                                <td>
                                                    <a href="{{route('shop_count_unit_list',$item->id)}}" class="btn btn-outline-info">
                                                        Check</a>
                                                </td>
                                                <td>
                                                    <a href="" class="btn btn-outline-info">
                                                        Change Unit</a>
                                                </td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-outline-warning" data-toggle="modal" data-target="#edit_item{{$item->id}}">
                                                        <i class="fas fa-edit"></i></a>

                                                    <a href="#" class="btn btn-outline-danger" onclick="ApproveLeave('{{$item->id}}')">
                                                        <i class="fas fa-trash-alt"></i></a>
                                                </td>

                                                <div class="modal fade" id="edit_item{{$item->id}}" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Edit Category Form</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <form class="form-material m-t-40" method="post" action="" enctype='multipart/form-data'>
                                                                    @csrf

                                                                    <div class="form-group">
                                                                        <label class="font-weight-bold">Code</label>
                                                                        <input type="text" name="item_code" class="form-control" value="{{$item->item_code}}">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="font-weight-bold">Name</label>
                                                                        <input type="text" name="item_name" class="form-control" value="{{$item->item_name}}">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="control-label">Item Photo</label>
                                                                        <input type="file" name="photo_path" class="form-control">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="font-weight-bold">Related Category</label>
                                                                        <select class="form-control select2 m-b-10" name="category_id" style="width: 100%">
                                                                            @foreach($categories as $category)
                                                                            <option value="{{$category->id}}" @if($item->shop_category_id === $category->id) selected='selected' @endif >{{$category->category_name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="font-weight-bold">Related Sub Category</label>
                                                                        <select class="form-control select2 m-b-10" name="sub_category_id" style="width: 100%">
                                                                            @foreach($sub_categories as $sub_category)
                                                                            @if($sub_category->shop_category_id == $item->shop_category_id)
                                                                            <option value="{{$sub_category->id}}" @if($item->shop_sub_category_id === $sub_category->id) selected='selected' @endif>{{$sub_category->name}}</option>
                                                                            @endif
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary" value="Save">
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
                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('js')
    <script src="{{asset('js/jquery.PrintArea.js')}}" type="text/JavaScript"></script>

    <script>
        $(document).ready(function() {
            var inventorytotal=$("#alltotal").val();
            console.log(inventorytotal);
            $('#inventorytotal').text(inventorytotal);

                //print button
            $("#print").click(function() {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $("div.printableArea").printArea(options);
        });


            $(".select2").select2();

            $('#example23').DataTable({

                "paging": false,
                "ordering": true,
                "info": false,

            });
        });

        function ApproveLeave(value) {

            var item_id = value;

            swal({
                    title: "@lang('lang.confirm')",
                    icon: 'warning',
                    buttons: ["@lang('lang.no')", "@lang('lang.yes')"]
                })

                .then((isConfirm) => {

                    if (isConfirm) {

                        $.ajax({
                            type: 'POST',
                            url: 'item/delete',
                            dataType: 'json',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "item_id": item_id,
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
        }

        function showSubCategory(value) {

            var category_id = value;

            $('#sub_category').empty();

            $.ajax({
                type: 'POST',
                url: '/shopSubCategory',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "category_id": category_id,
                },

                success: function(data) {

                    console.log(data);
                    $('#subcategory').append($('<option>').text('Select'));
                    $.each(data, function(i, value) {

                        $('#subcategory').append($('<option>').text(value.name).attr('value', value.id));
                    });

                }

            });

        }
        function showBrand(value) {

            var category_id = $('#category').val();
            var sub_category_id = value;

            $('#brand').empty();

            $.ajax({
                type: 'POST',
                url: '/shopBrand',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "category_id": category_id,
                    "sub_category_id": sub_category_id,
                },

                success: function(data) {

                    console.log(data);

                    $('#brand').append($('<option>').text('Select'));
                    $.each(data, function(i, value) {

                        $('#brand').append($('<option>').text(value.name).attr('value', value.id));
                    });

                }

            });

        }
        function showType(value) {

        var category_id = $('#category').val();
        var sub_category_id = $('#subcategory').val();
        var brand_id = value;

        $('#type').empty();

        $.ajax({
            type: 'POST',
            url: '/shopType',
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                "category_id": category_id,
                "sub_category_id": sub_category_id,
                "brand_id": brand_id,
            },

            success: function(data) {

                console.log(data);

                $('#type').append($('<option>').text('Select'));
                $.each(data, function(i, value) {

                    $('#type').append($('<option>').text(value.name).attr('value', value.id));
                });

            }

        });

        }

        function showRelatedSubCategory(value) {

            console.log(value);

            $('#subcategory').prop("disabled", false);

            var category_id = value;

            $('#subcategory').empty();

            $.ajax({
                type: 'POST',
                url: '/shopSubCategory',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "category_id": category_id,
                },

                success: function(data) {

                    console.log(data);
                    $('#subcategory').append($('<option>').text('Select'));
                    $.each(data, function(i, value) {

                        $('#subcategory').append($('<option>').text(value.name).attr('value', value.id));
                    });

                }

            });
        }

        function showRelatedItemList(value) {

            $('#item').empty();

            console.log(value);

            var sub_category_id = value;

            var items = @json($item_lists);

            var html = "";

            console.log(items);

            $.each(items, function(i, v) {

                if (v.shop_sub_category_id == sub_category_id) {

                    var related_category = v.category.category_name;

                    if (v.sub_category) {

                        var related_subcategory = v.sub_category.name;

                    } else {

                        var related_subcategory = "";
                    }

                    var url1 = '{{route('count_unit_list',":item_id")}}';

                    url1 = url1.replace(':item_id', v.id);


                    var url2 = '{{route('unit_relation_list',":item_id")}}';

                    url2 = url2.replace(':item_id', v.id);

                    html += `
                        <tr>
                            <td>${v.shop_item_code}</td>
                            <td>${v.shop_item_name}</td>
                            <td>${related_category}</td>
                            <td>${related_subcategory}</td>
                            <td>
                                <a href="${url1}" class="btn btn-outline-info">
                                @lang('lang.check')</a>
                            </td>
                            <td>
                                <a href="${url2}" class="btn btn-outline-info">
                                @lang('lang.change_unit')</a>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-outline-warning" data-toggle="modal" data-target="#edit_item${v.id}">
                                    <i class="fas fa-edit"></i></a>

                                <a href="#" class="btn btn-outline-danger" onclick="ApproveLeave('${v.id}')">
                            <i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>`

                    $('#item').html(html);
                }

            });
        }
    </script>
    @endsection
