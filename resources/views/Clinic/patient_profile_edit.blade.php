@extends('master')
@section('title', 'Edit Profile')
@section('content')

    <div class="row">

        <div class="col-sm-5 col-md-8">
            <h4 class="page-title font-weight-bold">Edit Profile</h4>
        </div>
    </div>


    <div class="profile-tabs" id="booking_list">

                <div class="row ">
                    <div class="card-body">
                    <form action="{{route('patient_profile_update')}}" method="post">
                        @csrf
                        <input type="hidden" name="patient_id" value="{{$patient->id}}">
                        <div class="row col-md-6 offset-md-3">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">Pet Name</label>
                                        <input class="form-control " type="text" name="name" value="{{$patient->name}}">
        
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">Owner Name</label>
                                        <input class="form-control " type="text" name="ownername" value="{{$patient->ownername ?? null}}">
        
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">Owner Phone</label>
                                        <input class="form-control " type="text" name="ownerphone" value="{{$patient->ownerphone ?? null}}">
        
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">D.O.B</label>
                                        <input class="form-control " type="date" name="dob" value="{{$patient->dob ??null}}">
        
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">Sex</label>
                                    <select name="sex"  class="form-select form-control" aria-label="Default select example">
                                        <option value="male" @if ($patient->sex == 'male')
                                            selected
                                        @endif>male</option>
                                        <option value="female" @if ($patient->sex == 'female')
                                            selected
                                        @endif>female</option>
                                        <option value="desexed" @if ($patient->sex == 'desexed')
                                            selected
                                        @endif>desexed</option>
                                      </select>
        
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">Microchop I.O no</label>
                                        <input class="form-control " type="text" name="microchip" value="{{$patient->microchip ?? null}}">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">Species</label>
                                    <select name="species" class="form-select form-control" aria-label="Species">
                                        <option value="canine"  @if ($patient->species == 'canine')
                                            selected
                                        @endif >canine</option>
                                        <option value="felinae"  @if ($patient->species == 'felinae')
                                            selected
                                        @endif >felinae</option>
                                      </select>
        
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">Breed</label>
                                        <input class="form-control " type="text" name="breed" value="{{$patient->breed}}">
        
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="focus-label">Color</label>
                                        <input class="form-control " type="text" name="color" value="{{$patient->color}}">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <button class="btn bbluecolor  w-100 text-white">Edit</button>
                            </div>

                        </div>
                    </form>
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



    </script>


@endsection
