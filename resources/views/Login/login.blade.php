<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/img/bahosi.png')}}">

    <title>Happy Paw Vet-Clinic </title>

    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">

    {{-- <link rel="stylesheet" type="text/css" href="http://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css"> --}}

    <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>  

   
</head>

<body>
    @include('sweet::alert')

    <div class="main-wrapper account-wrapper">
        <div class="account-page">
			<div class="account-center">
				<div class="account-box">
                    <form action="{{route('user_login')}}" class="form-signin" method="post">
                        @csrf
						<div class="account-logo">
                            <img src="{{asset('assets/img/bahosi.png')}}" alt="">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" autofocus="" class="form-control" name="email">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                        
                        
                        <div class="form-group text-center">
                            <button type="submit" class="btn bbluecolor text-white account-btn">Login</button>
                        </div>                        
                        
                        
                    </form>

                </div>
			</div>
        </div>
    </div>
    <script src="{{asset('assets/js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/app.js')}}"></script>
</body>


<!-- login23:12-->
</html>