@extends('master')

@section('title','Purchase History')

@section('place')

{{-- <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">@lang('lang.purchase_history') @lang('lang.list')</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.back_to_dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('lang.purchase_history') @lang('lang.list')</li>
    </ol>
</div> --}}

@endsection

@section('content')

<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">
        <h4 class="font-weight-normal">Purchase History List</h4>
    </div>

    <div class="col-md-7 col-4 align-self-center">
        <div class="d-flex m-t-10  mb-3 justify-content-end">
            <a href="{{route('create_purchase')}}" class="btn btn-outline-primary">
                <i class="fas fa-plus"></i>
                Purchase History Create
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive text-black">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Purchase Date</th>
                            <th>Total Quantity</th>
                            <th>Total Price</th>
                            <th>Purchase By</th>
                            <th>Supplier Name</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1;?>
                        @foreach($purchase_lists as $list)
                            <tr>
                                <th>{{$i++}}</th>
                                <th>{{date('d-m-Y', strtotime($list->purchase_date))}}</th>
                                <th>{{$list->total_quantity}}</th>
                                <th>{{$list->total_price}}</th>
                                <th>{{$list->user->name}}</th>
                                <th>{{$list->supplier_name}}</th>
                                <th class="text-center">
                                    <a href="{{route('purchase_details',$list->id)}}" class="btn btn-outline-primary">
                                        <i class="fas fa-check"></i>
                                        Check Details
                                    </a>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
