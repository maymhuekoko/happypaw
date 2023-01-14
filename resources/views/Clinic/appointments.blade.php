@extends('master')
@section('title', 'Appointments')
@section('content')

    <div class="row">

        <div class="col-sm-5 col-md-8">
            <h4 class="page-title font-weight-bold">Today Appointments</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">

                <div class="row filter-row pt-3 pb-1 pl-2">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Pet Name</label>
                                <input class="form-control floating" type="text" id="pet_name">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Owner Name</label>
                                <input class="form-control floating" type="text" id="owner_name">
                        </div>
                    </div>
                    
                        <div class="col-sm-6 col-md-3">
                            <button class="btn bpinkcolor text-white btn-block" onclick="searchPatient('today')">Search</button>
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
                                                    <th>Token No.</th>
                                                    <th>Pet Name</th>
                                                    <th>Owner Name</th>
                                                    <th>Breed</th>
                                                    <th>Doctor Name</th>
                                                    <th>Time</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table_body">
                                                @php
                                                    $j=1;
                                                @endphp
                                                @forelse ($appointments as $appointment)
                                                @if (!empty($appointment->voucher))
                                                    @php
                                                        $class = 'bgreencolor';
                                                    @endphp
                                                @else
                                                    @php
                                                        $class = 'bbluecolor';
                                                    @endphp
                                                @endif
                                                    <tr>
                                                        <td>{{$j++}}.</td>
                                                        <td>{{$appointment->token ?? null}}</td>
                                                        <td>{{$appointment->clinic_patient->name ?? null}}</td>
                                                        <td>{{$appointment->clinic_patient->ownername}}</td>
                                                        <td>{{$appointment->clinic_patient->breed}}</td>
                                                        <td>{{$appointment->doctor->name}}</td>
                                                        <td>{{$appointment->time}}</td>
                                                        <td>
                                                            <a href="appointments/{{$appointment->clinic_patient->id}}" class="btn {{$class}} text-white">Details</a>
                                                            @if ($class=="bbluecolor")
                                                            <a href="#" class="btn bpinkcolor text-white" onclick="ApproveLeave('{{$appointment->id}}')">
                                                                <i class="mdi mdi-delete"></i>
                                                                Delete
                                                            </a>
                                                            {{-- <button href="{{route('todayaptdelete',$appointment->id)}}" class="btn bpinkcolor text-white">Delete</button> --}}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                @endforelse
                                               
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


        }); //jquery end

        function ApproveLeave(value){

            var appointment_id = value;

            swal({
                title: "Are you sure ?",
                icon:'warning',
                buttons: ["no", "yes"]
            }) 

            .then((isConfirm)=>{

            if(isConfirm){

            $.ajax({
                type:'POST',
                    url:'appointments/delete',
                    dataType:'json',
                    data:{ 
                    "_token": "{{ csrf_token() }}",
                    "appointment_id": appointment_id,
                    },

                success: function(data){
                        console.log(data);
                        swal({
                                title: "Success!",
                                text : "Successfully deleted!",
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

        function searchPatient(todayorall){
            let pet_name= $('#pet_name').val();
            let owner_name =$('#owner_name').val();
            if ($.trim(pet_name) == '' && $.trim(owner_name) == '') {
                swal({
                    title: "Failed!",
                    text: "Please fill Pet Name and Owner Name Field!",
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
                        "todayorall": todayorall
                    },

                success:function(data){
                    if(data.length<=0){
                        $('#table_body').empty();

                        $('#table_body').html(`
                        <tr>
                        <td colspan="7" class="text-danger">
                            No Found !
                        </td>
                        </tr>`);
                    }
                    else{
                        var html= ``;
                        var j=1;
                        $.each(data, function(i, value) {
                            console.log(data);
                            html+= `
                                <tr>
                                    <td>${j++}</td>
                                    <td>${value.token ?? null}</td>
                                    <td>${value.clinic_patient.name}</td>
                                    <td>${value.clinic_patient.ownername}</td>
                                    <td>${value.clinic_patient.breed}</td>
                                    <td>${value.doctor.name}</td>
                                    <td>${value.time}</td>
                                    <td>
                                        <a href="appointments/${value.clinic_patient.id}" class="btn ${value.voucher ? "bgreencolor" : 'bbluecolor'} text-white">Details</a>

                                    </td>

                                </tr>
                            `;

                        });
                        $('#table_body').empty();
                        $('#table_body').html(html);
                    }
                      
                }

                });
            }
        }

    </script>


@endsection
