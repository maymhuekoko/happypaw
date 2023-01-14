@extends('master')
@section('title', 'Make Appointments')
@section('content')

    <div class="row">

        <div class="col-sm-5 col-md-8">
            <h4 class="page-title font-weight-bold">Make Appointments</h4>
        </div>

        <div class="col-md-4 text-right m-b-30">
            {{-- <label class="focus-label">Scan With QR-Code Scanner</label> --}}

            {{-- <input class="form-control" type="text" onchange="QRcodeTest(this.value)" id="qr_code"> --}}

            {{-- <a href="#" class="btn bpinkcolor text-white btn-rounded mt-3" onclick="qrSearch()"><i class="fa fa-check"></i> Check
                Token</a> --}}

            <div class="modal fade" id="token_info" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title pinkcolor">Token Information</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="#close_modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <h5 class="font-weight-bold col-md-5">Token Number</h5>

                                <span class="text col-md-2"> - </span>

                                <span class="badge badge-info col-md-5" id="token_num"></span>
                            </div>

                            <div class="row mt-3">

                                <h5 class="font-weight-bold col-md-5">Patient's Name</h5>

                                <span class="text col-md-2"> - </span>

                                <span class="text col-md-5" id="name"></span>
                            </div>

                            <div class="row mt-3">
                                <h5 class="font-weight-bold col-md-5">Patient's Age</h5>

                                <span class="text col-md-2"> - </span>

                                <span class="text col-md-5" id="age"></span>
                            </div>

                            <div class="row mt-3">
                                <h5 class="font-weight-bold col-md-5">Patient's Phone</h5>

                                <span class="text col-md-2"> - </span>

                                <span class="text col-md-5" id="phone"></span>
                            </div>

                            <div class="row mt-3">
                                <h5 class="font-weight-bold col-md-5">Patient's Address</h5>

                                <span class="text col-md-2"> - </span>

                                <span class="text col-md-5" id="address"></span>
                            </div>

                            <div class="row mt-3">
                                <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">

                <div class="row filter-row p-2">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Date</label>
                            <div class="cal-icon">
                                <input class="form-control floating" type="text" id="check_date">
                            </div>

                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Time</label>
                            <div class="time-icon">
                                <input type="text" class="form-control" id="datetimepicker3" name="time">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Doctors</label>
                            <select class="select2 floating" style="width: 100%" id="doc">
                                <option value="">Select Doctors</option>
                                @foreach ($doctors as $doc)
                                    <option class="form-control" value="{{ $doc->id }}">{{ $doc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Clinics</label>
                            <select class="select2 floating" style="width: 100%" id="clinics">
                                    <option class="form-control" value="1">Clinic 1</option>
                                    <option class="form-control" value="2">Clinic 2</option>
                            </select>
                        </div>
                    </div> --}}
                </div>

            </div>
        </div>
    </div>


    <div class="profile-tabs" id="booking_list">
        <ul class="nav nav-tabs nav-tabs-bottom">

            <li class="nav-item"><a class="nav-link active" href="#bottom-tab2" data-toggle="tab">New Patient</a></li>
            <li class="nav-item"><a class="nav-link" href="#about-cont" data-toggle="tab">Old Patient</a></li>

        </ul>

        <div class="tab-content">

            <div class="tab-pane active" id="bottom-tab2">

                <div class="row ">
                    <div class="card-body">
                        {{-- <div class="btn btn-success checkallConfirm float-right mx-3">Confirm</div> --}}
                    <form action="{{route('appointmentstore')}}" method="post" id="appointmentStoreForm">
                        @csrf
                        <div class="row col-md-6 offset-md-3">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">Pet Name</label>
                                        <input class="form-control " type="text" name="name">
        
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">Owner Name</label>
                                        <input class="form-control " type="text" name="ownername">
        
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">Owner Phone</label>
                                        <input class="form-control " type="text" name="ownerphone">
        
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">D.O.B</label>
                                        <input class="form-control " type="date" name="dob">
        
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">Sex</label>
                                    <select name="sex"  class="form-select form-control" aria-label="Default select example">
                                        <option value="male">male</option>
                                        <option value="female">female</option>
                                        <option value="desexed">desexed</option>
                                      </select>
        
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">Microchop I.O no</label>
                                        <input class="form-control " type="text" name="microchip">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">Species</label>
                                    <select name="species" class="form-select form-control" aria-label="Species">
                                        <option value="canine">canine</option>
                                        <option value="felinae">felinae</option>
                                      </select>
        
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">Breed</label>
                                        <input class="form-control " type="text" name="breed">
        
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">Color</label>
                                        <input class="form-control " type="text" name="color">
                                </div>
                            </div>
                            <input type="hidden" name="appointmentdoc" id="appointmentdoc">
                            <input type="hidden" name="appointmentclinic" id="appointmentclinic">
                            <input type="hidden" name="date" id="date">
                            <input type="hidden" name="time" id="time">
                            <div class="col-sm-12 col-md-12">
                                <a class="btn bbluecolor  w-100 text-white" onclick="appointstore()">Make Appointment</a>
                            </div>

                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <form action="{{route('appointment.oldpatient')}}" method="POST" id="appointmentoldpatient">
            @csrf
            <input type="hidden" name="oldpatientid" id="oldpatientid">
            <input type="hidden" name="oldappointmentdoc" id="oldappointmentdoc">
            {{-- <input type="hidden" name="oldappointmentclinic" id="oldappointmentclinic"> --}}
            <input type="hidden" name="olddate" id="olddate">
            <input type="hidden" name="oldtime" id="oldtime">
            </form>
            <div class="tab-pane show " id="about-cont">

                <div class="row">
                    <div class="card-body">
                                <div class="card shadow">
                    
                                    <div class="row filter-row p-2">
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group form-focus select-focus">
                                                <label class="focus-label">Pet ID</label>
                                                    <input class="form-control floating" type="text" id="pet_code">
                                            </div>
                                        </div>
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
                                                <button class="btn bpinkcolor text-white btn-block" onclick="searchPatient()">Search</button>
                                            </div>
                                    </div>
                    
                                    <div class="row px-3">

                                        <table class="table table-striped custom-table">
                                            <thead>
                                                <tr>
                                                    {{-- <th><i class="fa fa-check-square checkall"></i></th> --}}
                                                    <th>No.</th>
                                                    <th>Pet Code</th>
                                                    <th>Pet Name</th>
                                                    <th>Owner Name</th>
                                                    <th>D.O.B</th>
                                                    <th>Breed</th>
                                                    <th>Action</th>
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
            </div>

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


        function appointstore(){
            var doc_id = $('#doc').val();
            var time = $('#datetimepicker3').val();
            var date = $('#check_date').val();
            let clinic = $("#clinics option:selected").val();
            $('#appointmentdoc').val(doc_id);
            // $('#appointmentclinic').val(clinic);
            $('#date').val(date);
            $('#time').val(time);
            if ($.trim(doc_id) == '') {
                swal({
                    title: "Failed!",
                    text: "Please select Doctor and Date Field!",
                    icon: "info",
                    timer: 3000,
                });
            }
            else{
                $('#appointmentStoreForm').submit();
            }
        }

        function searchPatient(){
            let pet_code= $('#pet_code').val();
            let pet_name= $('#pet_name').val();
            let owner_name =$('#owner_name').val();
            console.log(pet_name);
            if ($.trim(pet_code) == '' && $.trim(pet_name) == '' && $.trim(owner_name) == '') {
                swal({
                    title: "Failed!",
                    text: "Please fill Name, Pet id or Owner Name Field!",
                    icon: "info",
                    timer: 3000,
                });
            }
            else{
                $.ajax({
                type:'POST',
                url:'/searchpatient',
                dataType:'json',
                data:{
                        "_token": "{{ csrf_token() }}",
                        "pet_code":pet_code,
                        "pet_name":pet_name,
                        "owner_name":owner_name,
                    },

                success:function(data){
                    if(data.length<=0){

                    }
                    else{
                        var html= ``;
                        var j=1;
                        $.each(data, function(i, value) {

                            html+= `
                                <tr>
                                    <td>${j++}</td>
                                    <td>${value.pet_code}</td>
                                    <td>${value.name}</td>
                                    <td>${value.ownername}</td>
                                    <td>${value.dob}</td>
                                    <td>${value.breed}</td>
                                    <td>
                  <button class="btn bbluecolor text-white makeappointments" data-id="${value.id}">Make AppointMents</button>
                                    
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

        $('#table_body').on('click','.makeappointments',function(){
            var id = $(this).data('id');
            var time = $('#datetimepicker3').val();
            var doc = $('#doc').val();
            // let clinic = $("#clinics option:selected").val();
            var date = $('#check_date').val();
            $('#oldpatientid').val(id);
            $('#oldappointmentdoc').val(doc);
            // $('#oldappointmentclinic').val(clinic);
            $('#olddate').val(date);
            $('#oldtime').val(time);
            if ($.trim(id) == '' || $.trim(time) == '' || $.trim(date) == '' || $.trim(doc) == '') {
                swal({
                    title: "Failed!",
                    text: "Please select Doctor and Date Field!",
                    icon: "info",
                    timer: 3000,
                });
            }
            else{
                $('#appointmentoldpatient').submit();
            }
        })

    </script>


@endsection
