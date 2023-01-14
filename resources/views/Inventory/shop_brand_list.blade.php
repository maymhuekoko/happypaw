@extends('master')
@section('title', 'ShopBrand')
@section('content')


<div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">Shop Brand</h3>

</div>

<div class="row mt-5">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title">Shop Brand Lists</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Brand code</th>
                                <th>Related Category</th>
                                <th>Related Sub Category</th>
                                <th>Brand Name</th>
                                <th class="text-center">Action</th>

                            </tr>
                        </thead>
                        <tbody id="category_table">
                        <?php $i=1;

                            ?>
                            @foreach($brands as $brand)

                            <tr>
                            <td>{{$i++}}</td>
                                <td>{{$brand->shop_brand_code}}</td>
                                <td>{{$brand->shop_category->category_name}}</td>
                                <td>{{$brand->shop_sub_category->name}}</td>
                                <td>{{$brand->name}}</td>

                                <td class="text-center">
                                    <a href="#" class="btn bneonblue text-white" data-toggle="modal" data-target="#edit_brand{{$brand->id}}">
                                Edit</a>



                                    <a href="#" class="btn bpinkcolor text-white" onclick="ApproveLeave('{{$brand->id}}')">
                                Delete</a>

                                </td>

                                <div class="modal fade" id="edit_brand{{$brand->id}}" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                  <div class="modal-header">
                                    <h4 class="modal-title pinkcolor">Edit Category Form</h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                  </div>

                                    <div class="modal-body">
                                        <form class="form-material m-t-40" method="post" action="{{route('shop_brand_update', $brand->id)}}">
                                            @csrf
                                            <div class="form-group">
                                                <label class="font-weight-bold">Code</label>
                                                <input type="number" name="brand_code" class="form-control" value="{{$brand->shop_brand_code}}">
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Category</label>
                                                <select class="form-control" name="category" required>

                                                    @foreach($categories as $category)
                                                    <option value="{{$category->id}}">{{$category->category_name}}</option>
                                                    @endforeach
                                                </select>
                                                </div>
                                            <div class="form-group">
                                            <label class="font-weight-bold">Sub Category</label>
                                            <select class="form-control" name="sub_category" required>

                                                @foreach($subcategories as $subcategory)
                                                <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>
                                                @endforeach
                                            </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Name</label>
                                                <input type="text" name="brand_name" class="form-control" value="{{$brand->name}}">
                                            </div>
                                            <input type="submit" name="btnsubmit" class="btnsubmit float-right btn bbluecolor text-white" value="Save">
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


    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="card-title">Create Brand Form</h3>
                <form class="form-material m-t-40" method="post" action="{{route('shop_brand_store')}}">
                    @csrf
                    <div class="form-group">
                        <label class="font-weight-bold">Code</label>
                        <input type="number" name="brand_code" class="form-control @error('category_code') is-invalid @enderror" placeholder="">


                        @error('brand_code')
                            <span class="invalid-feedback alert alert-danger" role="alert"  height="100">
                                {{ $message }}
                            </span>
                        @enderror


                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Category</label>
                        <select class="form-control" name="category" onchange="showSubCategory(this.value)" required>
                            <option value="">select category</option>
                            @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->category_name}}</option>
                            @endforeach
                        </select>
                        @error('category')
        <span class="invalid-feedback alert alert-danger" role="alert"  height="100">
            {{ $message }}
        </span>
    @enderror
                        </div>
                    <div class="form-group">
                                            <label class="font-weight-bold">Sub Category</label>
                                            <select class="form-control select2" style="width: 100%" id="sub_category" name="sub_category">
                                            </select>
                                            @error('category')
                            <span class="invalid-feedback alert alert-danger" role="alert"  height="100">
                                {{ $message }}
                            </span>
                        @enderror
                                            </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Brand Name</label>
                        <input type="text" name="brand_name" class="form-control @error('brand_name') is-invalid @enderror" placeholder="">


                        @error('brand_name')
                            <span class="invalid-feedback alert alert-danger" role="alert"  height="100">
                                {{ $message }}
                            </span>
                        @enderror


                    </div>
                    <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-warning" value="save brand">
                </form>
            </div>
        </div>
    </div>
</div>


</div>



@endsection
@section('js')
<script>
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
        $('#sub_category').append($('<option>').text('Select'));
        $.each(data, function(i, value) {

            $('#sub_category').append($('<option>').text(value.name).attr('value', value.id));
        });

    }

});

}
function ApproveLeave(value){

var brand_id = value;

swal({
    title: "Confirm",
    icon:'warning',
    buttons: ["No", "Yes"]
})

.then((isConfirm)=>{

if(isConfirm){

    $.ajax({
        type:'POST',
        url:'shop_brand/delete',
        dataType:'json',
        data:{
          "_token": "{{ csrf_token() }}",
          "brand_id": brand_id,
        },

        success: function(){

            swal({
                title: "Success!",
                text : "Successfully Deleted!",
                icon : "success",
            });

            setTimeout(function(){window.location.reload()}, 1000);


        },
    });
}
});


}

</script>
@endsection


