<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/img/bahosi.png')}}">

                            <!--     Template Link -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap-datetimepicker.css')}}">


    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/dataTables.bootstrap4.min.css')}}">

    {{-- <link rel="stylesheet" type="text/css" href="http://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css"> --}}

    <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"></script>  --}}
    <style>
        .preloader{
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url('../image/Profile/loader.gif') 50%50% no-repeat rgb(249, 249, 249);
            opacity: 0.9;
        }
        .plaintext {
            outline:0;
            border-width:0 0 1px;
        }
    </style>
    <title>@yield('title') </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    <div class="preloader" id="preloaders"></div>

    @include('sweet::alert')
    <div class="main-wrapper">
        <div class="header">
            <div class="header-left">
                <div class="logo">
                    <img src="{{asset('assets/img/bahosi.png')}}" width="40" height="40" alt=""> <span>Happy-paw vet-clinic</span>
                </div>
            </div>
            <a id="toggle_btn" href="javascript:void(0);"><i class="fa fa-bars mt-3"></i></a>
            <a id="mobile_btn" class="mobile_btn float-left" href="#sidebar"><i class="pinkcolor fa fa-bars"></i></a>


            <p class="pt-3 text-white float-left">
                @if (session()->get('from')==1)
                    Clinic One
                @elseif(session()->get('from')==2)
                    Clinic Two
                @elseif(session()->get('from')==3)
                    Shop One
                @elseif(session()->get('from')==4)
                    Shop Two
                @else
                    Shop Two
                @endif
            </p>
            <ul class="nav user-menu float-right">
                <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                        <span class="user-img">
                            <img class="rounded-circle" src="{{ asset('/image/'.session('profile_pic')) }}" width="24" alt="Admin">
                        </span>
                        <span>{{session('profile_name')}}</span>

                    </a>
                    <div class="dropdown-menu">
                        @if(session()->get('user')->hasRole('Employee') || session()->get('user')->hasRole('EmployeeC') || session()->get('user')->hasRole('Shopowner') || session()->get('user')->hasRole('Sale'))

                        <a class="dropdown-item" href="{{route('admin_profile')}}">My Profile</a>

                        <a class="dropdown-item" href="{{route('change_admin_pw_ui')}}">Change Password</a>

                        @elseif(session()->get('user')->hasRole('Doctor') || session()->get('user')->hasRole('DoctorC'))
                        <a class="dropdown-item" href="">My Profile</a>

                        {{-- <a class="dropdown-item" href="{{route('change_doc_pw_ui')}}">Change Password</a> --}}
                        <a class="dropdown-item" href="">Change Password</a>

                        @else

                        <a class="dropdown-item" href="{{route('patient_profile')}}">My Profile</a>

                        <a class="dropdown-item" href="{{route('change_pw_ui')}}">Change Password</a>

                        @endif
                        <a class="dropdown-item" href="{{route('logout')}}">Logout</a>
                    </div>
                </li>
            </ul>
                   {{-- change clinic --}}
            @if(session()->get('user')->hasRole('Shopowner') || session()->get('user')->hasRole('Sale'))
            <ul class="nav user-menu float-right">
                <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                        <span>Change Shops</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{route('change_shop',3)}}">Shop One</a>
                        <a class="dropdown-item" href="{{route('change_shop',4)}}">Shop Two</a>

                    </div>
                </li>
            </ul>
            @else
            <ul class="nav user-menu float-right">
                <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                        <span>Change Clinics</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{route('change_clinic',1)}}">Clnic One</a>
                        <a class="dropdown-item" href="{{route('change_clinic',2)}}">Clinic Two</a>

                    </div>
                </li>
            </ul>
            @endif

            <div class="dropdown mobile-user-menu float-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="pinkcolor fa fa-ellipsis-v mt-2"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    @if(session()->get('user')->hasRole('Employee') || session()->get('user')->hasRole('EmployeeC'))

                    <a class="dropdown-item" href="{{route('admin_profile')}}">My Profile</a>

                    <a class="dropdown-item" href="{{route('change_admin_pw_ui')}}">Change Password</a>

                    @elseif(session()->get('user')->hasRole('Doctor') || session()->get('user')->hasRole('DoctorC'))

                    <a class="dropdown-item" href="">My Profile</a>

                    {{-- <a class="dropdown-item" href="{{route('change_doc_pw_ui')}}">Change Password</a> --}}
                    <a class="dropdown-item" href="">Change Password</a>

                    @else

                    <a class="dropdown-item" href="">My Profile</a>
                    {{-- <a class="dropdown-item" href="{{route('patient_profile')}}">My Profile</a> --}}

                    {{-- <a class="dropdown-item" href="{{route('change_pw_ui')}}">Change Password</a> --}}
                    <a class="dropdown-item" href="">Change Password</a>

                    @endif

                    <a class="dropdown-item" href="{{route('logout')}}">Logout</a>
                </div>
            </div>
        </div>

        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        @if(session()->get('user')->hasRole('Employee') || session()->get('user')->hasRole('EmployeeC'))

                        <li class="menu-title">Admin Panel</li>

                        @elseif(session()->get('user')->hasRole('Doctor') || session()->get('user')->hasRole('DoctorC'))

                        <li class="menu-title">Doctor Panel</li>

                        @elseif(session()->get('user')->hasRole('Shopowner'))

                        <li class="menu-title">Shop Owner Panel</li>

                        @elseif(session()->get('user')->hasRole('Sale'))

                        <li class="menu-title">Sale Person Panel</li>

                        @else

                        <li class="menu-title">Patient Panel</li>

                        @endif


                        @if(session()->get('user')->hasRole('Employee') || session()->get('user')->hasRole('EmployeeC') || session()->get('user')->hasRole('Shopowner'))

                        <li>
                            <a href="{{route('admin_dashboard')}}"><i class="pinkcolor fa fa-dashboard"></i> <span>Dashboard</span></a>
                        </li>

                        @if(session()->get('user')->hasRole('Employee'))
                        <li>
                            <a href="{{route('admin_booking_list')}}"><i class="pinkcolor fa fa-calendar"></i><span>Check Booking List</span></a>
                        </li>
                        <li>
                            <a href="{{route('get_token')}}"><i class="pinkcolor fa fa-plus"></i><span>Get Booking Token</span></a>
                        </li>
                        <li class="submenu ">
                            <a href="#"><i class="pinkcolor fa fa-user-md"></i> <span> Sale</span> <span class="menu-arrow"></span></a>

                            <ul style="display: none;">
                                <li><a href="{{route('sale_page')}}">Sale Page</a></li>

                                <li><a href="{{route('sale_history')}}">Sale Record</a></li>


                                <li><a href="{{route('stock_count')}}">Stocks</a></li>

                                <li><a href="{{route('stock_reorder_page')}}">Reorder Lists</a></li>


                            </ul>
                        </li>
                        @endif

                        @if(session()->get('user')->hasRole('EmployeeC'))


                        {{-- <li>
                            <a href="{{route('today.appointments')}}"><i class="pinkcolor fa fa-calendar"></i><span>Appointments</span></a>
                        </li>
                        <li>
                            <a href="{{route('history')}}"><i class="pinkcolor fas fa-file-medical-alt"></i><span> Clinic Patient History</span></a>
                        </li>    --}}
                        <li>
                            <a href="{{route('sale_history')}}"><i class="pinkcolor fas fa-file-signature"></i><span> Sale History</span></a>
                        </li>

                        @endif

                        @if(session()->get('user')->hasRole('Shopowner'))

                        <li class="submenu">
                            <a href="#"><i class="pinkcolor fa fa-user-md"></i> <span>Inventory</span> <span class="menu-arrow"></span></a>

                            <ul style="display: none;">
                                <li><a href="{{route('shop_category_lists')}}">Category List</a></li>

                                <li><a href="{{route('shop_sub_category_lists')}}">Sub Category List</a></li>

                                <li><a href="{{route('shop_brand_lists')}}">Brand List</a></li>

                                <li><a href="{{route('shop_type_lists')}}">Type List</a></li>

                                <li><a href="{{route('shop_item_list')}}">Item List</a></li>

                                <li><a href="{{route('shop_item_assign')}}">Assign Item</a></li>
                            </ul>
                        </li>


                        <li class="submenu">
                            <a href="#"><i class="pinkcolor fas fa-file-signature"></i> <span>Sale</span> <span class="menu-arrow"></span></a>

                            <ul style="display: none;">
                                <li>
                                    <a href="{{route('shopowner')}}"><span> Sale Page</span></a>
                                </li>
                                <li>
                                    <a href="{{route('shop_sale_history')}}"><span> Sale History</span></a>
                                </li>
                            </ul>
                        </li>

                        <li class="submenu">
                            <a href="#"><i class="pinkcolor fas fa-cart-arrow-down"></i><span>Purchase</span> <span class="menu-arrow"></span></a>

                            <ul style="display: none;">
                                <li>
                                    <a href="{{route('purchase_list')}}"><span> Purchase List</span></a>
                                </li>
                                <li>
                                    <a href="{{route('supplier_credit_list')}}"><span> Supplier Credit List</span></a>
                                </li>
                            </ul>
                        </li>


                        @endif




                        @if(session()->get('user')->hasRole('Employee'))

                        <li class="submenu">
                            <a href="#"><i class="pinkcolor fa fa-user-md"></i> <span> Doctors</span> <span class="menu-arrow"></span></a>

                            <ul style="display: none;">
                                <li><a href="{{route('doctor_list')}}">Doctors List</a></li>

                                <li><a href="{{route('schedule_list')}}">Doctor Schedule</a></li>

                                <li><a href="{{route('change_sch_list')}}">Change Doctor Schedule</a></li>

                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="pinkcolor fas fa-store-alt"></i><span>Inventory</span> <span class="menu-arrow"></span></a>

                            <ul style="display: none;">
                                <li><a href="{{route('show_category_lists')}}">Category</a></li>

                                <li><a href="{{route('show_sub_category_lists')}}">Sub Category</a></li>

                                <li><a href="{{route('show_brand_lists')}}">Brand</a></li>

                                <li><a href="{{route('show_type_lists')}}">Type</a></li>

                                <li><a href="{{route('show_item_lists')}}">Items</a></li>

                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="pinkcolor fa fa-user-md"></i> <span> Admin</span> <span class="menu-arrow"></span></a>

                            <ul style="display: none;">

                                <li><a href="{{route('services.lists')}}">Services</a></li>

                                <li><a href="{{route('packages.lists')}}">Packages</a></li>

                                <li><a href="{{route('state_list')}}">State and Town</a></li>

                                <li><a href="{{route('buidling_info')}}">Building</a></li>


                                <li><a href="{{route('advertisement.index')}}">Advertisements</a></li>

                                <li><a href="{{route('announcement.index')}}">Announcement Lists</a></li>

                            </ul>
                        </li>
                        @endif
                        @endif
                        @if(session()->get('user')->hasRole('Doctor') || session()->get('user')->hasRole('DoctorC'))
                        <li>
                            <a href="{{route('admin_dashboard')}}"><i class="pinkcolor fa fa-dashboard"></i> <span>Dashboard</span></a>
                        </li>
                        <li>
                            <a href="{{route('doc.profile')}}"><i class="pinkcolor fa fa-user-md"></i><span>Profile</span></a>
                        </li>
                        <li>
                            <a href="{{route('doctor.schedulelist')}}"><i class="pinkcolor fas fa-user-clock"></i><span>Your Schedules</span></a>
                        </li>
                        @if (session()->get('user')->hasRole('Doctor'))
                        <li>
                            <a href="{{route('doctor.manualbookings')}}"><i class="pinkcolor fa fa-hospital-o"></i><span>Manual Booking</span></a>
                        </li>
                        <li>
                            <a href="{{route('doctor.onlinebookings')}}"><i class="pinkcolor fas fa-laptop-medical"></i><span>Online Booking</span></a>
                        </li>
                        <li>
                            <a href="{{route('doctor.patientHistory')}}"><i class="pinkcolor fas fa-file-signature"></i><span>Patient History</span></a>
                        </li>


                        @elseif(session()->get('user')->hasRole('DoctorC'))
                        <li>
                            <a href="{{route('patientregister')}}"><i class="pinkcolor fa fa-plus"></i><span>Pet Register</span></a>
                        </li>
                        <li>
                            <a href="{{route('today.appointments')}}"><i class="pinkcolor fa fa-calendar"></i><span>Appointments</span></a>
                        </li>
                        <li>
                            <a href="{{route('history')}}"><i class="pinkcolor fas fa-file-medical-alt"></i><span> Pet History</span></a>
                        </li>
                        <li>
                            <a href="{{route('vaccine')}}"><i class="pinkcolor fas fa-file-medical-alt"></i><span> Vaccine Record</span></a>
                        </li>
                        @if (session()->get('user')->isOwner(1))
                        <li>
                            <a href="{{route('sale_history')}}"><i class="pinkcolor fas fa-file-signature"></i><span> Sale History</span></a>
                        </li>

                        <li class="submenu">
                            <a href="#"><i class="pinkcolor fa fa-user-md"></i> <span> Doctors & Counter</span> <span class="menu-arrow"></span></a>

                            <ul style="display: none;">
                                <li><a href="{{route('doctor_list')}}">Doctors & Counter List</a></li>

                                <li><a href="{{route('schedule_list')}}">Doctor Schedule</a></li>

                                <li><a href="{{route('change_sch_list')}}">Change Doctor Schedule</a></li>

                            </ul>
                        </li>

                        <li class="submenu">
                            <a href="#"><i class="pinkcolor fas fa-store-alt"></i><span>Inventory</span> <span class="menu-arrow"></span></a>

                            <ul style="display: none;">
                                <li><a href="{{route('show_category_lists')}}">Category</a></li>

                                <li><a href="{{route('show_sub_category_lists')}}">Sub Category</a></li>


                                {{-- <li><a href="{{route('show_type_lists')}}">Type</a></li> --}}

                                <li><a href="{{route('show_item_lists')}}">Items</a></li>

                                <li><a href="{{route('item_assign')}}">Assign Items</a></li>
                                <li><a href="{{route('stock_count')}}">Stock Counts</a></li>


                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="pinkcolor fa fa-user-md"></i> <span> Admin</span> <span class="menu-arrow"></span></a>

                            <ul style="display: none;">

                                <li><a href="{{route('services.lists')}}">Services</a></li>

                                <li><a href="{{route('packages.lists')}}">Packages</a></li>

                                {{-- <li><a href="{{route('state_list')}}">State and Town</a></li> --}}

                                {{-- <li><a href="{{route('buidling_info')}}">Building</a></li> --}}


                                <li><a href="{{route('advertisement.index')}}">Advertisements</a></li>

                                <li><a href="{{route('announcement.index')}}">Announcement Lists</a></li>

                                <li><a href="{{route('getDiagnosis')}}">Diagnosis Lists</a></li>

                                <li><a href="{{route('department_list')}}">Clinics</a></li>

                            </ul>
                        </li>
                        @endif
                        @if (session()->get('user')->hasRole('DoctorC') && session()->get('user')->isOwner(0))
                        <li>
                            <a href="{{route('getDiagnosis')}}"><i class="pinkcolor fas fa-file-signature"></i><span> Diagnosis Lists</span></a>
                        </li>
                        @endif

                        @endif

                        @endif

                         @if (session()->get('user')->hasRole('Sale'))
                         <li class="submenu">
                            <a href="#"><i class="pinkcolor fas fa-file-signature"></i> <span>Sale</span> <span class="menu-arrow"></span></a>

                            <ul style="display: none;">
                                <li>
                                    <a href="{{route('shopowner')}}"><span> Sale Page</span></a>
                                </li>
                                <li>
                                    <a href="{{route('shop_sale_history')}}"><span> Sale History</span></a>
                                </li>
                            </ul>
                        </li>
                         @endif
                        <li>
                            <a href="{{route('logout')}}"><i class="pinkcolor fa fa-power-off"></i> <span>Logout</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="page-wrapper">
            <div class="content">

                @yield('content')


            </div>
        </div>
    </div>


    <div class="sidebar-overlay" data-reff=""></div>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>


    <script src="{{asset('assets/js/moment.min.js')}}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>

    <script src="{{asset('assets/js/jquery.dataTables1.min.js')}}"></script>

    <script src="{{asset('assets/js/select2.min.js')}}"></script>

    <script src="{{asset('assets/js/dataTables.bootstrap4.min.js')}}"></script>

    <script src="{{asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>

    <script src="{{asset('assets/js/jquery.PrintArea.js')}}" type="text/JavaScript"></script>

    <script src="{{asset('assets/js/jquery.slimscroll.js')}}"></script>

    <script src="{{asset('assets/js/app.js')}}"></script>

    <script src="{{asset('assets/js/validation.js')}}"></script>

    @yield('js')


</body>


</html>

<script type="text/javascript">

  //loader
    $(window).on('load', function(){
        $("#preloaders").fadeOut(100);
    });
    $(document).ajaxStart(function(){
        $("#preloaders").show();
    });
    $(document).ajaxComplete(function(){
        $("#preloaders").hide();
    });
//link active
    $(function() {
        var url = window.location.pathname; //sets the variable "url" to the pathname of the current window
//    alert(url);
        var activePage = url.substring(url.lastIndexOf('/') + 1); //sets the variable "activePage" as the substring after the last "/" in the "url" variable
        $('.sidebar-menu li a').each(function() { //looks in each link item within the primary-nav list
            var linkPage = this.href.substring(this.href.lastIndexOf('/') + 1); //sets the variable "linkPage" as the substring of the url path in each &lt;a&gt;
            if (activePage == linkPage) { //compares the path of the current window to the path of the linked page in the nav item
                $(this).parent().addClass('active'); //if the above is true, add the "active" class to the parent of the &lt;a&gt; which is the &lt;li&gt; in the nav list
            }
        });

        $('.sidebar-menu li ul li a').each(function() { //looks in each link item within the primary-nav list
            var linksubPage = this.href.substring(this.href.lastIndexOf('/') + 1); //sets the variable "linkPage" as the substring of the url path in each &lt;a&gt;
            if (activePage == linksubPage) { //compares the path of the current window to the path of the linked page in the nav item
                console.log($(this).parent().parent());

                $(this).parent().parent().css("display","block"); //if the above is true, add the "active" class to the parent of the &lt;a&gt; which is the &lt;li&gt; in the nav list
                }
        });
    })

    function showType(value) {
var subcategory_id= $('#sub_category').val();
var brand_id = value;

$('#type').empty();

$.ajax({
    type: 'POST',
    url: '/showType',
    dataType: 'json',
    data: {
        "_token": "{{ csrf_token() }}",
        "subcategory_id": subcategory_id,
        "brand_id": brand_id,
    },

    success: function(data) {

        console.log(data);

        $('#type').append($('<option>').text("Select"));

        $.each(data, function(i, value) {

            $('#type').append($('<option>').text(value.name).attr('value', value.id));
        });

    }

});

}

function showRelatedSubCategoryFrom(value) {

console.log(value);

$('#subcategory').prop("disabled", false);

var category_id = value;
var from_id = $('#from_id option:selected').val();

$('#subcategory').empty();

$.ajax({
    type: 'POST',
    url: '/showSubCategoryFrom',
    dataType: 'json',
    data: {
        "_token": "{{ csrf_token() }}",
        "category_id": category_id,

    },

    success: function(data) {

        console.log(data);
        $('#subcategory').append($('<option>').text('Select Subcategory'));
        $.each(data, function(i, value) {

            $('#subcategory').append($('<option>').text(value.name).attr('value', value.id));
        });
    }
});
}

function showRelatedBrandFrom(value) {
var subcategory_id = value;
$('#also_brand').empty();
var from_id = $('#from_id option:selected').val();
$.ajax({
    type: 'POST',
    url: '/showBrandFrom',
    dataType: 'json',
    data: {
        "_token": "{{ csrf_token() }}",
        "subcategory_id": subcategory_id,
        "from_id":from_id,
    },

    success: function(data) {

        console.log(data);
        $('#also_brand').append($('<option>').text('Select Brand'));

        $.each(data, function(i, value) {

            $('#also_brand').append($('<option>').text(value.name).attr('value', value.id));
        });
    }
});
}

function showRelatedTypeFrom(value) {
var subcategory_id= $('#subcategory').val();
var brand_id = value;
var from_id = $('#from_id option:selected').val();


$('#also_type').empty();

$.ajax({
    type: 'POST',
    url: '/showTypeFrom',
    dataType: 'json',
    data: {
        "_token": "{{ csrf_token() }}",
        "subcategory_id": subcategory_id,
        "brand_id": brand_id,
        "from_id":from_id,
    },

    success: function(data) {

        console.log(data);

        if(data.length==0){
            $('#also_type').append($('<option>').text("No Found"));
        }else{
            $('#also_type').append($('<option>').text("Select Type"));
            $.each(data, function(i, value) {
            $('#also_type').append($('<option>').text(value.name).attr('value', value.id));
        });
        }
    }

});

}

$('.advenced_search_btn').click(function(){
    $('.advenced_search').toggle();
});

function showRelatedItemFrom(value){

    var brand_id = $('#also_brand').val();
    var type_id = value;
var from_id = $('#from_id option:selected').val();
    $('#also_item').empty();
$.ajax({
    type: 'POST',
    url: '/showItemFrom',
    dataType: 'json',
    data: {
        "_token": "{{ csrf_token() }}",
        "type_id": type_id,
        "brand_id": brand_id,
        "from_id":from_id,

    },
    success: function(data) {

$('#also_item').append($('<option>').text('Select Item'));

$.each(data, function(i, value) {
    $('#also_item').append($('<option>').text(value.item_name).attr('value', value.id));
});

}

});

}

            function editTown(id, name, age, phone ,action) {

            $("#booking_id").attr('value', id);

            $("#booking_name").attr('value', name);

            $("#booking_age").attr('value', age);

            $("#booking_phone").attr('value', phone);
            $("#withdateOrnodate").attr('value', action);


            $("#edit_booking_record").modal("show");

            }

            $("#ajaxSubmitUpdate").click(function(e) {
                e.preventDefault();
                let booking_id = $("#booking_id").val();
                let name = $("#booking_name").val();
                let age = $("#booking_age").val();
                let phone = $("#booking_phone").val();
                let withdateOrnodate = $("#withdateOrnodate").val();
                $.ajax({

                    type: 'POST',

                    url: '{{route('edit_booking_record')}}',

                    data: {
                        "_token": "{{ csrf_token() }}",
                        "booking_id": booking_id,
                        "name": name,
                        "age": age,
                        "phone": phone,
                        "withdateOrnodate": withdateOrnodate,

                    },
                    success: function(data) {
                        if (data) {
                            swal({
                                title: "Success",
                                text: "Successfully Changed!",
                                icon: "info",
                                timer: 3000,
                            });
                            if(data[1]=='nodate'){
                                window.location.reload();
                            }
                            else{
                            $('#edit_booking_record').modal('hide');

                            $("#table_body").empty();
                            $("#online_table_body").empty();
                            myat('withdate');
                            }
                        }
                    }
                });
            });

            $("#table_body").on('click', '.doctor_book_done_btn', function() {
                let booking_id = $(this).data("id");
                let withdateOrnodate = $(this).data("dateorno");
                $('#done_booking_id').val(booking_id);
                $('#donedateOrnodate').val(withdateOrnodate);
                $('#done_booking_record').modal('show');
            })

            $("#my_form").on('submit', function(e){
                e.preventDefault();
                var description = $('#add_description').val();

                var formdata = new FormData(this);
                var diagnosis = $('#diagnosiss').val();
                // alert(diagnosis);
                formdata.append('description',description);
                formdata.append('diagonsis',diagnosis);
                console.log(formdata.getAll('remark_date'));
                console.log(formdata.getAll('description'));
                console.log(formdata.getAll('diagnosis'));
                console.log(formdata.getAll('patienthistory'));
                console.log(formdata.getAll('donedateOrnodate'));
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({

                    type: 'POST',
                    url: '{{route('doctor_done_booking')}}',
                    processData: false,
                    contentType: false,
                    cache:false,
                    data:formdata,
                    success: function(data) {
                        // alert(data);
                        if (data['status']) {
                            console.log(data);
                            swal({
                                title: "Success",
                                text: "Successfully Changed!",
                                icon: "info",
                                timer: 3000,
                            });

                            if(data['withdateOrnodate']=='nodate'){
                                myat('nodate');
                            }else
                            {
                                $("#table_body").empty();
                                $('#done_booking_record').modal('hide');
                                myat('withdate');

                            }


                        }
                    }
                });
            })

            //clinic patient search
function showType(value) {
// alert("hello");
console.log(value);

$('#type').prop("disabled", false);

var category_id = $('#category').val();
var sub_category_id = $('#subcategory').val();
var brand_id = $('#brand').val();
alert(category_id);
alert(sub_category_id);
alert(brand_id);
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

        $('#type').append($('<option>').text("Select"));
        $.each(data, function(i, value) {

            $('#type').append($('<option>').text(value.name).attr('value', value.id));
        });

    }

});
}

</script>
