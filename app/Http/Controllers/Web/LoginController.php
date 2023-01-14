<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\SocialFacebookAccountService;
use App\User;
use App\VerifyUser;
use App\Patient;
use App\Doctor;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Socialite;
use Session;
use DateTime;
use Auth;

class LoginController extends Controller
{
    public function index(Request $request){

    	if (Session::has('user')) {

			if ($request->session()->get('user')->hasRole('Doctor') || $request->session()->get('user')->hasRole('DoctorC')) {
				session()->put("from",1);
				return redirect()->route('admin_dashboard');   //doctor.dashboard
			}

			elseif ($request->session()->get('user')->hasRole('Employee') || $request->session()->get('user')->hasRole('EmployeeC'))
			{
				return redirect()->route('admin_dashboard');
			}

            elseif ($request->session()->get('user')->hasRole('Shoporder'))
			{
				return redirect()->route('admin_dashboard');
			}

			elseif ($request->session()->get('user')->hasRole('Patient') || $request->session()->get('user')->hasRole('PatientC'))
			{
				return redirect()->route('booking_list');
			}

		} else {

			return view('Login.login');

		}
    }

    public function LoginProcess(Request $request){

    	$validator = Validator::make($request->all(), [
			'email' => 'required',
			'password' => 'required',
		]);

		if ($validator->fails()) {

			alert()->error('Something Wrong!');

			return redirect()->back();

		}


		$password = $request->password;

		$user = User::where('email', '=', $request->email)->first();

        // dd($user->hasRole('Sale'));

		if (empty($user)) {

			alert()->error('သင်၏ အီးမေးလ် တစ်ခုခုတော့မှားနေပြီ။')->autoclose(2000);

			return redirect()->back();
		}
		elseif (!\Hash::check($password, $user->password)) {

			alert()->error('သင်၏ စကားဝှက် တစ်ခုခုတော့မှားနေပြီ။')->autoclose(2000);

			return redirect()->back();
		}else{

			session()->put('user', $user);

			if ($user->hasRole('Doctor') || $user->hasRole('DoctorC')) {

				$profile_name = $user->name;

				$profile_pic = Doctor::where('user_id', $user->id)->first()->photo;

				session()->put('profile_pic', $profile_pic);

				session()->put('profile_name', $profile_name);

				alert()->success('Successfully Login!')->autoclose(2000);

				session()->put("from",1);

				return redirect()->route('admin_dashboard');   //doctor.dashboard

			}
			elseif ($user->hasRole('Employee') || $user->hasRole('EmployeeC') || $user->hasRole('Shopowner'))
			{
				$profile_name = $user->name;

				$profile_pic = Employee::where('user_id', $user->id)->first()->photo;

				session()->put('profile_pic', $profile_pic);

				session()->put('profile_name', $profile_name);

				alert()->success('Successfully Login!')->autoclose(2000);

				return redirect()->route('admin_dashboard');
			}
            elseif ( $user->hasRole('Sale'))
			{
				$profile_name = $user->name;

				$profile_pic = Employee::where('user_id', $user->id)->first()->photo;

				session()->put('profile_pic', $profile_pic);

				session()->put('profile_name', $profile_name);

				alert()->success('Successfully Login!')->autoclose(2000);

				return redirect()->route('shopowner');
			}
			else{

				$profile_name = $user->name;

				$profile_pic = Patient::where('user_id', $user->id)->first()->photo;

				session()->put('profile_pic', $profile_pic);

				session()->put('profile_name', $profile_name);

				alert()->success('Successfully Login!')->autoclose(2000);

				return redirect()->route('booking_list');
			}
	    }
	}

	public function LogoutProcess(Request $request){

		Auth::logout();

		Session::flush();

		alert()->success('Successfully Logout!')->autoclose(2000);

		return redirect()->route('login_page');

	}

	/*public function verifyUser($token){

		$verifyUser = VerifyUser::where('token', $token)->first();

		$now = $now = new DateTime('Asia/Yangon');

		$today = $now->format('Y-m-d H:i:s');

  		if(isset($verifyUser)){

			$user = $verifyUser->user;

			    if(!$user->verified) {

			      $verifyUser->user->email_verified_at = $today;

			      $verifyUser->user->save();

			      $status = "Your e-mail is verified. You can now login.";
			    }
			    else{

			      $status = "Your e-mail is already verified. You can now login.";
			    }
		} else {

			return redirect('/login')->with('warning', "Sorry your email cannot be identified.");
		}

  		return redirect('/')->with('status', $status);
	}*/
}
