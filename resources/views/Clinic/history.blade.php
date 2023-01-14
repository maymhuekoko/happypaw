@extends('master')
@section('title', 'History')
@section('content')
<style>
    .select2-container--default .select2-selection--multiple {
    background-color: white;
    border: 1px solid rgb(201, 198, 198);
    border-radius: 4px;
    cursor: text;
    padding:11px
}
</style>
    <div class="row">

        <div class="col-sm-5 col-md-4">
            <h4 class="page-title font-weight-bold">Search Patients</h4>
        </div>
        <div class="col-md-4 offset-2">
        <div class="form-group row">
            <label class="col-md-2 mt-2 pinkcolor" for="patientFilter">Filter</label>
            <select class="col-md-4 form-control " id="patientFilter" class="form-control">
                <option value="name" selected>Name</option>
                <option value="date">Date</option>
            </select>
        </div>
        </div>

    </div>

    <div class="row" id="nameFilter">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="row filter-row p-2">
                    <div class="col-sm-6 col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Pet Id</label>
                                <input class="form-control floating" type="text" id="pet_code">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Pet Name</label>
                                <input class="form-control floating" type="text" id="pet_name">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Owner Name</label>
                                <input class="form-control floating" type="text" id="owner_name">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Breed</label>
                                <input class="form-control floating" type="text" id="breed">
                        </div>
                    </div>
                    {{-- <div class="col-sm-6 col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Diagnosis</label>
                                <select class="select2 floating" style="width: 100%" class="m-4" name="diagnosis[]" multiple id="diagnosis">
                                    <option>Select Diagnosis</option>
                                    @foreach ($diagnosis as $diag)
                                        <option class="form-control" value="{{ $diag->id }}">{{ $diag->name }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div> --}}
                        <div class="col-sm-6 col-md-2">
                            <button class="btn bpinkcolor text-white btn-block" onclick="searchPatient('all')">Search</button>
                        </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row" id="dateFilter">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="row filter-row p-2">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label pinkcolor">From Date</label>
                                <input class="form-control floating" type="date" id="fromdate">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label pinkcolor">To Date</label>
                                <input class="form-control floating" type="date" id="todate">
                        </div>
                    </div>
                        <div class="col-sm-6 col-md-3">
                            <button class="btn bpinkcolor text-white btn-block" onclick="searchPatient('all')">Search</button>
                        </div>
                </div>

            </div>
        </div>
    </div>

                <div class="row">
                    <div class="card-body">
                                <div class="card shadow p-2">

                                    <div class="row px-3">

                                        <table class="table table-striped custom-table">
                                            <thead>
                                                <tr>
                                                    {{-- <th><i class="fa fa-check-square checkall"></i></th> --}}
                                                    <th>No.</th>
                                                    <th>Ped Id</th>
                                                    <th>Pet Name</th>
                                                    <th>Owner Name</th>
                                                    <th>Breed</th>
                                                    <th>counts</th>
                                                    <th>Appointments</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table_body">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                        {{-- <div class="btn btn-success checkallConfirm float-right mx-3">Confirm</div> --}}


                    </div>
                </div>

@endsection

@section('js')

    <script>
        $(document).ready(function() {
            $(".select2").select2();
            $("#check_date").datetimepicker({
                format: 'YYYY-MM-DD'
            });

        $('#datetimepicker3').datetimepicker({
            format: 'LT'
        });

        $('#dateFilter').hide();
        $('#nameFilter').show();

        $('#patientFilter').change(function (e) { 
            e.preventDefault();
            var conceptName = $('#patientFilter :selected').val();
            if(conceptName== "date"){
                $('#nameFilter').hide();
                $('#dateFilter').show();
            }else{
                $('#dateFilter').hide();
                $('#nameFilter').show();
            }
        });

        }); //jquery end

        function searchPatient(todayorall){

            var filterName = $('#patientFilter :selected').val();

            // let diagnosis= $('#diagnosis').val();
            let breed= $('#breed').val();
            let pet_name= $('#pet_name').val();
            let owner_name =$('#owner_name').val();
            let pet_code =$('#pet_code').val();

            let fromdate =$('#fromdate').val();
            let todate =$('#todate').val();

            if ($.trim(pet_name) == '' && $.trim(pet_code) == '' && $.trim(owner_name) == '' && $.trim(breed) == '' && $.trim(fromdate) == '' && $.trim(todate) == '') {
                swal({
                    title: "Failed!",
                    text: "Please fill one of the field!",
                    icon: "info",
                    timer: 3000,
                });
            }
            else{
                $.ajax({
                type:'POST',
                url:'/searchpatient/todayappointments',
                dataType:'json',
                data:{
                        "_token": "{{ csrf_token() }}",
                        "pet_name":pet_name,
                        "owner_name":owner_name,
                        "pet_code":pet_code,
                        "todayorall": todayorall,
                        // "diagnosis": diagnosis,
                        "breed": breed,
                        "fromdate": fromdate,
                        "todate": todate,
                        "filterName": filterName,

                    },

                success:function(data){
                    if(data.length<=0){

                    }
                    else{
                        var html= ``;
                        var j=1;
                        $.each(data, function(i, value) {
                            console.log(data);
                            html+= `
                                <tr>
                                    <td>${j++}</td>
                                    <td>${value.pet_code}</td>
                                    <td>${value.name}</td>
                                    <td>${value.ownername}</td>
                                    <td>${value.breed}</td>
                                    <td>${value.appointments_count}</td>
                                    <td>
                                        <a href="appointments/${value.id}" class="btn bbluecolor text-white">Details</a>

                                    </td>

                                </tr>
                            `;

                        });
                        $('#table_body').html(html);
                    }
                      
                }

                });
            }
        }

    </script>


@endsection
