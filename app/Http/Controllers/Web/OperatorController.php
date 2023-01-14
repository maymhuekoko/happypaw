<?php

namespace App\Http\Controllers\Web;

use App\Day;
use App\From;
use App\Item;
use App\Town;
use App\User;
use DateTime;
use App\Admin;
use App\State;
use App\Doctor;
use App\Booking;
use App\Patient;
use App\Voucher;
use App\Customer;
use App\Employee;
use App\ShopItem;
use Carbon\Carbon;
use App\Department;
use App\DoctorInfo;
use App\DoctorTime;
use App\Appointment;
use App\ShopVoucher;
use App\Announcement;
use App\ShopCategory;
use App\Advertisement;
use App\SalesCustomer;
use App\ShopStockcount;
use App\Traits\ZoomJWT;
use App\ShopSubCategory;
use App\ShopCountingUnit;
use Illuminate\Http\Request;
use App\SaleCustomerCreditlist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OperatorController extends Controller
{
	public function __construct()
	{

		$this->routeList = [
			"U Zaw Win",
			"Daw Zin Win Oo",
			"Daw Zin Zin Win",
			"Daw Win Win Zaw",
			"U Sai Kaung Chit",
			"Daw Khin Myat Min",
			"U Sein Aung Lwin",
			"Daw Khin Myit Sein",
			"Daw Yamone Oo",
			"Daw Yamone Phoo",
			"Daw Zun Phoo Phoo",
			"Daw Aye Nyein Thu",
			"Daw Aye Nyein May",
			"Daw Thet Htar Swe",
			"U Pyae Phyo Win",
			"U Win Pyae Phyo",
			"U Wunna Kyaw",
			"U Aung Htoo Kyaw",
			"U Kyaw Lin Aung",
			"U Aung Lin Kyaw",
		];
	}

	use ZoomJWT;

	const MEETING_TYPE_INSTANT = 1;
	const MEETING_TYPE_SCHEDULE = 2;
	const MEETING_TYPE_RECURRING = 3;
	const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

	protected function AdminDashboard(Request $request)
	{
		$from_id = session()->get('from');

		$now = new DateTime('Asia/Yangon');

		$toady_date = $now->format('Y-m-d');

		$department_lists = Department::all();

		$user = $request->session()->get('user');
		if(session()->get('user')->isOwner(1) || session()->get('user')->hasRole('EmployeeC') || session()->get('user')->hasRole('Shopowner')){
			$bookings = Appointment::where('date', $toady_date)->where('from_clinic',$from_id)->get();
		}
		elseif(session()->get('user')->isOwner(0) && session()->get('user')->hasRole('DoctorC')){
			$doctor = Doctor::where('user_id',$user->id)->first();
			$bookings = Appointment::where('date', $toady_date)->where('doctor_id',$doctor->id)->where('from_clinic',$from_id)->get();
		}

		$announcements = Announcement::all();

		$advertisements = Advertisement::all();

		$count_booking = count($bookings);

		$doctors = Doctor::all();

		$patients = Patient::all();

		$count_doc = count($doctors);

		$count_patient = count($patients);

		$count_dept = count($department_lists);

        if(session()->get('user')->hasRole('EmployeeC') || session()->get('user')->hasRole('DoctorC') || session()->get('user')->hasRole('Shopowner')){
            $voucher_lists =Voucher::where('type', 1)->where('clinicvoucher_status',1)->where('from_id',$from_id)->orderBy('id','desc')->get();
        }
        else{
            $voucher_lists =Voucher::where('type', 1)->orderBy('id','desc')->get();

        }

		$total_sales  = 0;
		foreach ($voucher_lists as $voucher_list){

            $total_sales += $voucher_list->total_price;

        }
        $date = new DateTime('Asia/Yangon');

        $current_date = strtotime($date->format('Y-m-d'));
        $to = $date->format('Y-m-d');

        $weekly = date('Y-m-d', strtotime('-1week', $current_date));

        if(session()->get('user')->hasRole('EmployeeC') || session()->get('user')->hasRole('DoctorC') || session()->get('user')->hasRole('Shopowner')){

            $weekly_data = Voucher::where('type', 1)->where('clinicvoucher_status',1)->where('from_id',$from_id)->whereBetween('voucher_date', [$weekly,$to])->get();
        }
        else{
            $weekly_data = Voucher::where('type', 1)->whereBetween('created_at', [$current_date, $weekly])->get();

        }

        $weekly_sales = 0;

        foreach($weekly_data as $weekly){

            $weekly_sales += $weekly->total_price;
        }

        $current_month = $date->format('m');
        $current_month_year = $date->format('Y');

        $today_date = $date->format('Y-m-d');
        if(session()->get('user')->hasRole('EmployeeC') || session()->get('user')->hasRole('DoctorC') || session()->get('user')->hasRole('Shopowner')){
            $daily = Voucher::where('type', 1)->where("from_id",$from_id)->where('clinicvoucher_status',1)->whereDate('created_at', $today_date)->get();
        }
        else{
            $daily = Voucher::where('type', 1)->whereDate('created_at', $today_date)->get();

        }

        $daily_sales = 0;

        foreach($daily as $day){

            $daily_sales += $day->total_price;
        }

        if(session()->get('user')->hasRole('EmployeeC') || session()->get('user')->hasRole('DoctorC') || session()->get('user')->hasRole('Shopowner')){

            $monthly = Voucher::where('type', 1)->where('clinicvoucher_status',1)->where('from_id',$from_id)->whereMonth('created_at',$current_month)->whereYear('created_at',$current_month_year)->get();

        }
        else{
            $monthly = Voucher::where('type', 1)->whereMonth('created_at',$current_month)->get();

        }

        $monthly_sales = 0;

        foreach ($monthly as $month){

            $monthly_sales += $month->total_price;
        }

		return view('Admin.dashboard', compact('department_lists', 'count_doc', 'count_patient', 'count_dept', 'doctors', 'count_booking', 'announcements', 'advertisements','total_sales','daily_sales','monthly_sales','weekly_sales'));
	}

	protected function getBookingListUi()
	{

		$doctors = Doctor::all();

		$departments = Department::all();

		$now = new DateTime;

		$today = $now->format('Y-m-d');

		$booking_lists = Booking::where('booking_date', $today)->with('doctor')->get();

		$booking_count = count($booking_lists);

		return view('Admin.booking_list', compact('doctors', 'departments','booking_lists','booking_count'));
	}

	protected function ajaxDoctorBookingList(Request $request)
	{

		$request_date = $request->check_date;

		$status = $request->status;

		$doctor = Doctor::where('id', $request->doctor_id)->with('department')->first();


		if ($status == 6) {

			$booking_lists = Booking::where('booking_date', $request_date)->where("doctor_id", $request->doctor_id)->get();
		} else {

			$booking_lists = Booking::where('booking_date', $request_date)->where("doctor_id", $request->doctor_id)->where("status", $status)->get();
		}

		$booking_count = count($booking_lists);

		return response()->json([
			'doctor' => $doctor,
			'booking_lists' => $booking_lists,
			'booking_count' => $booking_count,
			'status' => $status,
		]);
	}

	protected function AjaxTokenCheckIn(Request $request)
	{

		$token_number = $request->token_number;

		$booking = Booking::where('token_number', $token_number)->first();

		$booking_list = Booking::where('doctor_id', $booking->doctor_id)->where('booking_date', $booking->booking_date)->get();

		return response()->json([
			'booking' => $booking,
			'booking_lists' => $booking_list,
		]);
	}

	protected function AdminProfile(Request $request)
	{

		$user_id = getUserId($request);

		$user = $request->session()->get('user');

		$user_email = $user->email;

		$admin = Admin::where('user_id', $user_id)->first();

		return view('Admin.profile', compact('admin', 'user_email'));
	}
	protected function counterProfile(Request $request,$counter_id)
	{

		$admin = Employee::with('user')->findOrfail($counter_id);
		$user_email= $admin->user->email;
		return view('Admin.profile', compact('admin','user_email'));
	}
	protected function counterProfileEdit(Request $request,$counter_id)
	{

		$admin = Employee::with('user')->findOrfail($counter_id);
		$user_email= $admin->user->email;
		return view('Admin.editprofile', compact('admin','user_email'));
	}
	protected function counterProfileEditSave(Request $request)
	{
		$validator = Validator::make($request->all(), [
			"employee_id" => "required",
			"name" => "required",
			"code" => "required",
			"phone" => "required",
			"dob" => "required",
			"email" => "required",
		]);
		if($request->password){
			$validator = Validator::make($request->all(), [
				"employee_id" => "required",
				"name" => "required",
				"code" => "required",
				"phone" => "required",
				"dob" => "required",
				"email" => "required",
				"password"=> "required|min:6"
			]);
		}

		if ($validator->fails()) {

			alert()->error('Please Fill All Fields!');
			return redirect()->back();
		}
		$employee = Employee::findOrfail($request->employee_id);

		$employeeupdate=$employee->update([
			"name" => $request->name,
			"employee_code" => $request->code,
			"phone" => $request->phone,
			"dob" => $request->dob
		]);
		$employee->user->update([
			'email'=> $request->email
		]);
		if($request->password){
			$employee->user->update([
				'password'=> Hash::make($request->password)
			]);
		}
		alert()->success('Successfully Changed!');

		return back();
	}

	public function createCounter(Request $request)
	{
		return view('Admin.createcounter');
	}
    public function createShopowner(Request $request)
	{
		return view('Admin.createshoporder');
	}
	public function createCounterSave(Request $request)
	{
			$validator = Validator::make($request->all(), [
				"name" => "required",
				"phone" => "required",
				"dob" => "required",
				"email" => "required",
				"password"=> "required|min:6"
			]);

		if ($validator->fails()) {

			alert()->error('Please Fill All Fields!');
			return redirect()->back();
		}
		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'phone' => $request->phone,
			'password' => Hash::make($request->password)
		]);
		$user->assignRole(4);

        if ($request->hasfile('image')) {

			$image = $request->file('image');

			$name = $image->getClientOriginalName();

			$photo_path =  time()."-".$name;

			$image->move(public_path() . '/image/admin/', $photo_path);

			$path = '/image/admin/'. $photo_path;

		}
		else{
			$path = '/image/admin/user.jpg';

		}
		$employee_code =  "EMP_" . sprintf("%03s", $user->id);


		$employee=Employee::create([
			"name" => $request->name,
			"employee_code" => $request->code,
			"phone" => $request->phone,
			"dob" => $request->dob,
			"user_id"=> $user->id,
			"photo" => $path,
			"position_id" =>1,
			"employee_code" => $employee_code
		]);

		alert()->success('Successfully Added!');

		return redirect()->route('doctor_list');
	}
    public function createShoporderSave(Request $request)
	{
			$validator = Validator::make($request->all(), [
				"name" => "required",
				"phone" => "required",
				"dob" => "required",
				"email" => "required",
				"password"=> "required|min:6"
			]);

		if ($validator->fails()) {

			alert()->error('Please Fill All Fields!');
			return redirect()->back();
		}
		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'phone' => $request->phone,
			'password' => Hash::make($request->password)
		]);
		$user->assignRole(6);

        if ($request->hasfile('image')) {

			$image = $request->file('image');

			$name = $image->getClientOriginalName();

			$photo_path =  time()."-".$name;

			$image->move(public_path() . '/image/admin/', $photo_path);

			$path = '/image/admin/'. $photo_path;

		}
		else{
			$path = '/image/admin/user.jpg';

		}
		$employee_code =  "EMP_" . sprintf("%03s", $user->id);


		$employee=Employee::create([
			"name" => $request->name,
			"employee_code" => $request->code,
			"phone" => $request->phone,
			"dob" => $request->dob,
			"user_id"=> $user->id,
			"photo" => $path,
			"position_id" =>2,
			"employee_code" => $employee_code
		]);

		alert()->success('Successfully Added!');

		return redirect()->route('doctor_list');
	}
    protected function shopowner(){
        $categories = ShopCategory::all();
        $sub_categories = ShopSubCategory::all();
        $items = ShopItem::all();
        $salescustomers = SalesCustomer::all();
        $customers = Customer::all();
        $date = new DateTime('Asia/Yangon');
        $today_date = $date->format('Y-m-d');
        // dd($today_date);
        $last_voucher = Voucher::get()->last();

        $voucher_code =  "VOU-".date('dmY')."-".sprintf("%04s", ($last_voucher->id + 1));
    	return view('Owner.home',compact('items','categories','sub_categories','salescustomers','date','today_date','customers','voucher_code'));
    }

    public function getCountingUnitsByShopItemCode(Request $request){

        $unit_code = $request->unit_code;

        $units = ShopCountingUnit::where('unit_code', $unit_code)->orWhere('original_code', $unit_code)->with('shop_item')->with('shop_brand')->first();
        // dd($units);
        return response()->json($units);
    }

    protected function getSalesCustomerWithID(Request $request){

        $salescustomerwID = SalesCustomer::findOrFail($request->customer_id);

        $cust_credit = SaleCustomerCreditlist::where('sales_customer_id',$request->customer_id)->first();

        return response()->json([
            'sale_credit' => $cust_credit,

            'sale_cust' => $salescustomerwID]);
    }

    protected function storeSalesCustomer(Request $request){
        // dd($request->all());
        $sales_customer = SalesCustomer::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'credit_amount' => $request->credit_amount,
        ]);
        // dd($sales_customer);
        $last_row= DB::table('sales_customers')->orderBy('id', 'DESC')->first();
        Session::flash('data',$last_row);
        return response()->json([
            "success" => 1,
            "message" => "Customer is successfully added",
            "last_row"=>$last_row,

        ]);
    }

    public function storetestVoucher(Request $request)
    {
        // dd($request->all());
        // return 0;
        $validator = Validator::make($request->all(), [
            'item' => 'required',
            'grand' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
            ]);
        }

        $user = session()->get('user');
        // dd("ok");

        $shop_id = session()->get('from');

        if($request->editvoucher != 0 ){
            $units = ShopVoucher::findOrfail($request->editvoucher)->shop_counting_unit;
            foreach($units as $unit){
                $stock = ShopStockcount::where('shop_counting_unit_id',$unit->id)->where('from_id',$shop_id)->first();
                $balanceQty = $stock->stock_qty + $unit->pivot->quantity;
                $stock->stock_qty = $balanceQty ;
                $stock->save();
            }
            $deleted = DB::table('shop_vouchers')->where('id', $request->editvoucher)->delete();
        }

        try {


        // dd(json_decode(json_encode($request->grand)));
        // dd("ok");

        $date = new DateTime('Asia/Yangon');
        //   dd($date);
        $today_date = $date->format('d-m-Y h:i:s');

        $voucher_date = $date->format('Y-m-d');

        $today_time = $date->format('g:i A');

        $items = json_decode($request->item);
        // dd($items);

        $grand = json_decode($request->grand);

        $total_quantity = $grand->total_qty;

        // dd($total_quantity);
        $total_amount = $grand->sub_total;

        if($grand->vou_discount == 'foc'){
            $discount = 'foc';
            $total_wif_discount = 0;
        }
        else if($grand->vou_discount > 0) {
            $discount = $grand->vou_discount;
            $total_wif_discount = $grand->sub_total - $grand->vou_discount;
        }
        else {
            $discount= 0;
            $total_wif_discount = $grand->sub_total;

        }


        $voucher = ShopVoucher::create([
            'sale_by' => $user->id,
            'voucher_code' => $request->voucher_code,
            'total_price' =>  $total_amount,
            'discount' => $discount,
            'total_quantity' => $total_quantity,
            'voucher_date' => $voucher_date,
            'type' => 1,
            'status' => 0,
            'sales_customer_id' => $request->sales_customer_id,
            'sales_customer_name' => $request->sales_customer_name,
            'from_id'=> $shop_id,
            'pay' => $request->cus_pay,
            'change' => $request->credit_amount ? 0 : (int)($request->cus_pay)- (int)($total_wif_discount),
        ]);
        // dd( $request->repaymentDate);
         if(isset($request->credit_amount) && $request->credit_amount > 0){
             $sales_customer = SalesCustomer::find($request->sales_customer_id);
             $sales_customer->credit_amount += $request->credit_amount;
            //  $sales_customer->deleted_at = null;
             $sales_customer->save();

                $salescustomer_credit = SaleCustomerCreditlist::create([
                    'sales_customer_id' => $request->sales_customer_id,
                    'voucher_id' => $voucher->id,
                    'voucher_code' => $voucher->voucher_code,
                    'repaymentdate'=> $request->repaymentDate,
                    'credit_amount'=>$request->credit_amount,
                 ]);
         }
        //  return response()->json($items);
        // dd($items);
        foreach ($items as $item) {

            if($item->discount == 'foc'){
                $item_discount = 'foc';
            }
            else if($item->discount > 0){
                $item_discount = $item->selling_price -  ( (int) $item->discount );
            }
            else {
                $item_discount = 0;
            }
            // dd($item->id);
            $voucher->shop_counting_unit()->attach($item->id, ['quantity' => $item->order_qty,'price' => $item->selling_price,'discount'=> $item_discount]);

            $counting_unit = ShopCountingUnit::find($item->id);
            $stock=$counting_unit->shop_stockcounts->where('from_id', $shop_id)->first();
            // dd($stock);
            if($stock != null){
                $balance_qty = ($stock->stock_qty - $item->order_qty);

                $stock->stock_qty = $balance_qty;

                $stock->save();
            }

            // dd($stock);

        }

        // $role= $request->session()->get('user')->role;
        // dd($role);
        // if($role=='Sale_Person'){
        //     $item_from= $request->session()->get('user')->from_id;
        // }
        // else {
            $item_from= $request->session()->get('from');
            // dd($item_from);
        // }
        $froms=From::find($item_from);
        $shopitems = $froms->shop_items()->with('shop_category')->with('shop_counting_units')->with("shop_counting_units.shop_stockcounts")->with('shop_sub_category')->with('shop_brand')->get();
        // dd($shopitems);
        $last_voucher = ShopVoucher::get()->last();

        $voucher_code =  "VOU-".date('dmY')."-".sprintf("%04s", ($last_voucher->id + 1));
        // dd($voucher);
        return response()->json([
            'status' => 1,
            // 'voucher'=>$voucher,
            'voucher_code' => $voucher_code,
            'items' =>$shopitems,
        ]);

        } catch (\Exception $e) {
            // dd($e);
            return response()->json([
                'status' => 0,
            ]);

        }
    }

    //Sale History
    protected function getShopSaleHistoryPage(Request $request){
            // dd('Hello');
        $item_from= $request->session()->get('from');
        // dd($item_from);

        $voucher_lists =ShopVoucher::where('type', 1)->orderBy('id','desc')->where('from_id',$item_from)->get();

        $total_sales  = 0;

        foreach ($voucher_lists as $voucher_list){
            if($voucher_list->discount > 1 && gettype($voucher_list->discount) != "string"){
                $total_sales += ($voucher_list->total_price) - ((int) $voucher_list->discount);
            }
            else if ($voucher_list->discount == 0){
                $total_sales += $voucher_list->total_price;
            }
            else{
                $total_sales += 0;
            }

        }
        $date = new DateTime('Asia/Yangon');

        $current_date = strtotime($date->format('Y-m-d'));
        $to = $date->format('Y-m-d');

        $weekly = date('Y-m-d', strtotime('-1week', $current_date));


            $weekly_data = ShopVoucher::where('type', 1)->where('from_id',$item_from)->whereBetween('voucher_date', [$weekly,$to])->get();

        $weekly_sales = 0;

        foreach($weekly_data as $weekly){
            if($weekly->discount > 1 && gettype($weekly->discount) != "string"){
                $weekly_sales += ($weekly->total_price) - ((int) $weekly->discount);
            }
            else if ($weekly->discount == 0){
                $weekly_sales += $weekly->total_price;
            }
            else{
                $weekly_sales += 0;
            }
        }

        $current_month = $date->format('m');
        $current_month_year = $date->format('Y');

        $today_date = $date->format('Y-m-d');
            $daily = ShopVoucher::where('type', 1)->where("from_id",$item_from)->whereDate('created_at', $today_date)->get();


        $daily_sales = 0;
        foreach($daily as $day){
            if($day->discount > 1 && gettype($day->discount) != "string"){
                $daily_sales += ($day->total_price) - ((int) $day->discount);
            }
            elseif ($day->discount == 0){
                $daily_sales += $day->total_price;
            }
            else {
                $daily_sales += 0;
            }
        }

            $monthly = ShopVoucher::where('type', 1)->where('from_id',$item_from)->whereMonth('created_at',$current_month)->whereYear('created_at',$current_month_year)->get();


        $monthly_sales = 0;

        foreach ($monthly as $month){

            if($month->discount > 1 && gettype($month->discount) != "string"){
                $monthly_sales += ($month->total_price) - ((int) $month->discount);
            }
            else if ($month->discount == 0){
                $monthly_sales += $month->total_price;
            }
            else{
                $monthly_sales += 0;
            }
        }

        $search_sales = 0;
        return view('Owner.shop_sale_history',compact('search_sales','voucher_lists','total_sales','daily_sales','monthly_sales','weekly_sales'));
    }

    protected function searchSaleHistory(Request $request){

        $validator = Validator::make($request->all(), [
            'from' => 'required',
            'to' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error('Something Wrong!');

            return redirect()->back();
        }

        $role= $request->session()->get('user')->role;

        if($role=='Sale_Person'){
            $from_id= $request->session()->get('user')->from_id;
        }
        else {
            $from_id= $request->session()->get('from');
        }

        $voucher_lists = ShopVoucher::where('type', 1)->where("from_id",$from_id)->whereBetween('voucher_date', [$request->from, $request->to])->get();

        $search_sales= 0;

        foreach ($voucher_lists as $search_list){

                if($search_list->discount > 1 && gettype($search_list->discount) != "string"){
                    $search_sales += ($search_list->total_price) - ((int) $search_list->discount);
                }
                else if ($search_list->discount == 0){
                    $search_sales += $search_list->total_price;
                }
                else{
                    $search_sales += 0;
                }

        }

        $voucher_lists_all = ShopVoucher::where('type', 1)->where('from_id',$from_id)->get();

        $total_sales  = 0;

        foreach ($voucher_lists_all as $voucher_list){

            if($voucher_list->discount > 1 && gettype($voucher_list->discount) != "string"){
                $total_sales += ($voucher_list->total_price) - ((int) $voucher_list->discount);
            }
            else if ($voucher_list->discount == 0){
                $total_sales += $voucher_list->total_price;
            }
            else{
                $total_sales += 0;
            }

        }

        $date = new DateTime('Asia/Yangon');

        $current_date = strtotime($date->format('Y-m-d'));
        $to = $date->format('Y-m-d');


        $weekly = date('Y-m-d', strtotime('-1week', $current_date));

        $weekly_data = ShopVoucher::where('type', 1)->where('from_id',$from_id)->whereBetween('voucher_date', [$weekly,$to])->get();

        $weekly_sales = 0;

        foreach($weekly_data as $weekly){

            if($weekly->discount > 1 && gettype($weekly->discount) != "string"){
                $weekly_sales += ($weekly->total_price) - ((int) $weekly->discount);
            }
            else if ($weekly->discount == 0){
                $weekly_sales += $weekly->total_price;
            }
            else{
                $weekly_sales += 0;
            }

        }

        $current_month = $date->format('m');
        $current_month_year = $date->format('Y');

        $today_date = $date->format('Y-m-d');

        $daily = ShopVoucher::where('type', 1)->where('from_id',$from_id)->whereDate('created_at', $today_date)->get();

        $daily_sales = 0;

        foreach($daily as $day){

            if($day->discount > 1 && gettype($day->discount) != "string"){
                $daily_sales += ($day->total_price) - ((int) $day->discount);
            }
            else if ($day->discount == 0){
                $daily_sales += $day->total_price;
            }
            else{
                $daily_sales += 0;
            }
        }

        $monthly = ShopVoucher::where('type', 1)->where('from_id',$from_id)->whereMonth('created_at',$current_month)->whereYear('created_at',$current_month_year)->get();

        $monthly_sales = 0;

        foreach ($monthly as $month){

            if($month->discount > 1 && gettype($month->discount) != "string"){
                $monthly_sales += ($month->total_price) - ((int) $month->discount);
            }
            else if ($month->discount == 0){
                $monthly_sales += $month->total_price;
            }
            else{
                $monthly_sales += 0;
            }
        }

        return view('Owner.shop_sale_history',compact('voucher_lists','total_sales','daily_sales','monthly_sales','weekly_sales','search_sales'));

    }

    protected function getShopVoucherDetails(request $request, $id){

        $unit = ShopVoucher::with('shop_counting_unit')->with('shop_counting_unit.shop_stockcounts')->find($id);
        // dd($unit->sale_customer->phone);
        return view('Owner.shop_voucher_details', compact('unit'));
    }

    public function voucherDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'voucher_id' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(0);
        }

        $shop_id = session()->get('from');
        //    dd($shop_id);
        try {
               $units = ShopVoucher::findOrfail($request->voucher_id)->shop_counting_unit;
            //    dd($units);
               foreach($units as $unit){
                // dd('hello');
                $stock = ShopStockcount::where('shop_counting_unit_id',$unit->id)->where('from_id',$shop_id)->first();
                // dd($stock);
                $balanceQty = $stock->stock_qty + $unit->pivot->quantity;
                $stock->stock_qty = $balanceQty ;
                $stock->save();
            }
            $deleted = DB::table('shop_vouchers')->where('id', $request->voucher_id)->delete();

        } catch (\Exception $e) {

            return response()->json(0);

        }

        return response()->json(1);

    }

    protected function getCustomerInfo(Request $request){

        $customer = Customer::where('id',$request->customer_id)->with('user')->first();

        return response()->json($customer);
    }

    protected function getShopVoucherPage(Request $request){
        // dd($request->item);
        $right_now_customer= $request->right_now_customer;
        $date = new DateTime('Asia/Yangon');

        $today_date = $date->format('d-m-Y h:i:s');

        $check_date = $date->format('Y-m-d');

        $items = json_decode($request->item);

        $grand = json_decode($request->grand);

        $last_voucher = ShopVoucher::get()->last();

        $voucher_code =  "VOU-".date('dmY')."-".sprintf("%04s", ($last_voucher->id + 1));

        $salescustomers = SalesCustomer::all();

        $foc = json_decode($request->foc_flag);

        $has_dis = json_decode($request->has_dis);
        // dd($has_dis);

        $discount = json_decode($request->discount);
        $last_voucher = ShopVoucher::get()->last();

        $voucher_code =  "VOU-".date('dmY')."-".sprintf("%04s", ($last_voucher->id + 1));

        $salescustomers = SalesCustomer::all();

        return view('Owner.shopvoucher', compact('has_dis','foc','discount','items','today_date','grand','voucher_code','right_now_customer','salescustomers'));
    }


    public function deleteSalesCustomer(Request $request){
        $id=$request->salecustomer_id;
       $deletesalecustomer= SalesCustomer::findOrFail($id)->delete();
       return response()->json($deletesalecustomer);
    }

    protected function clinicone(){
        return view('Owner.clinicone');
    }

	protected function AdminChangePassUI(Request $request)
	{

		return view('Admin.change_pw');
	}

	protected function AdminChangePass(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'current_pw' => 'required',
			'new_pw' => 'required|confirmed|min:6'
		]);

		if ($validator->fails()) {

			alert()->error('Something Wrong!');
			return redirect()->back();
		}

		$user = $request->session()->get('user');

		$current_pw = $request->current_pw;

		if (!\Hash::check($current_pw, $user->password)) {

			alert()->info("Wrong Current Password!");

			return redirect()->back();
		}

		$has_new_pw = \Hash::make($request->new_pw);

		$user->password = $has_new_pw;

		$user->save();

		alert()->success('Successfully Changed!');

		return redirect()->route('admin_dashboard');
	}

	protected function DepartmentList()
	{

		$department_lists = Department::all();

		return view('Admin/Department/department_list', compact('department_lists'));
	}



	//To update with Modal Box
	protected function CreateDepartment()
	{

		return view('Admin/Department/create_department');
	}

	protected function StoreDepartment(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'description' => 'required',
			'image' => 'required|file'
		]);

		if ($validator->fails()) {

			alert()->error('Something Wrong');

			return redirect()->back();
		}

		if ($request->hasfile('image')) {

			$image = $request->file('image');
			$name = $image->getClientOriginalName();
			$image->move(public_path() . '/image/Department_Image/', $name);
			$image = $name;
		}
		$department = Department::create([
			'name' => $request->name,
			'description' => $request->description,
			'photo_path' => $image,
			'status' => $request->status,
		]);

		$department_id = $department->id;

		$department_code = "DEPT" . sprintf("%04s", $department_id);

		$department->department_code = $department_code;

		$department->save();

		alert()->success('Successfully Added!');

		return redirect()->route('department_list');
	}

	protected function EditDepartment($department, Request $request)
	{

		$department = Department::where('id', $department)->first();

		return view('Admin/Department/edit_department', compact('department'));
	}

	protected function UpdateDepartment($department, Request $request)
	{

		$department = Department::where('id', $department)->first();

		if ($request->dept_status == "on") {

			$department->status = 1;
		} else {

			$department->status = 2;
		}

		$department->name = $request->name;

		$department->description = $request->description;

		$department->save();

		alert()->success('ပြင်ဆင်တာ​အောင်မြင်ပါသည်');

		return redirect()->route('department_list');
	}

	//For Phone Booking From Reception
	protected function GetToken()
	{

		$doctors = Doctor::all();

		return view('Admin.get_token', compact('doctors'));
	}

	//For Phone Booking from Reception
	protected function SearchDoctors(Request $request)
	{

		$now = new DateTime;

		$today = $now->format('Y-m-d');

		$validator = Validator::make($request->all(), [
			'doctor_id' => 'required',
		]);

		if ($validator->fails()) {

			return response()->json(array("errors" => $validator->getMessageBag()), 422);
		}

		$doctor = Doctor::find(request('doctor_id'));

		$days = $doctor->day;

		$doc_range = explode("-", $doctor->doc_info->booking_range);

		$range = 7 *  $doc_range[0];

		$today_string = strtotime($today);

		$available_date = array();

		$final_date = array();

		$start_time_array = array();

		$end_time_array = array();

		for ($i = 0; $i <= $range; $i++) {

			array_push($available_date, date('d-m-Y,l', strtotime("+$i day", $today_string)));
		}

		foreach ($available_date as $ava_date) {

			foreach ($days as $day) {

				if ($day->name == date('l', strtotime($ava_date))) {

					$start_time = date('h:i A', strtotime($day->pivot->start_time));

					$end_time = date('h:i A', strtotime($day->pivot->end_time));

					$object = collect([$ava_date, $start_time, $end_time]);

					array_push($final_date, $object);
				}
			}
		}

		return response()->json($final_date);
	}

	protected function StoreBookingToken(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'booking_date' => 'required',
			'name' => 'required',
			'age' => 'required',
			'phone' => 'required',
			'address' => 'required',
			'bookings' => 'required',
		]);

		if ($validator->fails()) {

			alert()->error('Something Wrong');

			return redirect()->back();
		}
		$person_list = $this->routeList;

		$date = explode(',', $request->booking_date);

		$check_date = date('Y-m-d', strtotime($date[0]));

		$date_save = date('md', strtotime($date[0]));

		$doctor = Doctor::find(request('doctor'));

		$reserved_token = $doctor->doc_info->reserved_token;

		$check_booking = Booking::where('doctor_id', request('doctor'))
			->whereDate('booking_date', $check_date)
			->get();



			if($request->bookings== "manualBooking"){
				$zoom_id= null;
				$zoom_psw= null;
				$start_url= null;
				$join_url= null;
				$booking_status=0;
			}
			else {

				$path = 'users/me/meetings';
				$response = $this->zoomPost($path, [
					'topic' => "online",
					'type' => self::MEETING_TYPE_SCHEDULE,
					'start_time' => $this->toZoomTimeFormat($check_date),
					'duration' => 30,
					'agenda' => "Data",
					'settings' => [
						'host_video' => false,
						'participant_video' => false,
						'waiting_room' => true,
					]
				]);

				$zoom = json_decode($response->body(), true);
				Log::channel('custom')->info($zoom);
				$zoom_id= $zoom['id'];
				$zoom_psw= $zoom['password'];
				$start_url= $zoom['start_url'];
				$join_url= $zoom['join_url'];
				$booking_status=1;

			}

		if (count($check_booking) == 0) {

			for ($i = 1; $i <= $reserved_token; $i++) {

				$random = array_rand($person_list);

				$name = $person_list[$random];

				$book_token = Booking::create([
					'name' => $name,
					'age' => 33,
					'phone' => " 09250206903",
					'address' => "Tarmwe Yangon",
					'booking_date' => $check_date,
					'status' => 1,
					'submit_by' => 0,
					'user_id' => 1,
					'doctor_id' => request('doctor'),
					'floor_id' => 1,
					'booking_status' => 2, //manual booking-0 online-1 reserved-2
				]);

				$token_number = "TKN-" . sprintf("%03s", $i);

				$book_token->token_number = $token_number;

				$book_token->save();
			}

			$check_booking_real = Booking::where('doctor_id', request('doctor'))->whereDate('booking_date', $check_date)->get();

			$booking_array = $check_booking_real->toArray();

			$last_token_booking_arry = array_column($booking_array, 'token_number');

			$last_token = end($last_token_booking_arry);

			$last_token_number = explode('-', $last_token);

			$token = $last_token_number[1] + 1;

			$real_token_number = "TKN-" . sprintf("%03s", $token);

			$real_book_token = Booking::create([
				'token_number' =>  $real_token_number,
				'name' => $request->name,
				'age' => $request->age,
				'phone' => $request->phone,
				'address' => $request->address,
				'booking_date' => $check_date,
				'status' => 1,
				'submit_by' => 0,
				'user_id' => 1,
				'doctor_id' => request('doctor'),
				'floor_id' => 1,
				'booking_status' => $booking_status,
				'zoom_id' => $zoom_id,
				'zoom_psw' => $zoom_psw,
				'start_url' => $start_url,
				'join_url' => $join_url,
			]);

			// alert()->success('Token Number', $real_token_number)->persistent('Close');

			// return redirect()->back();
		} else {

			$booking_array = $check_booking->toArray();

			$last_token_booking_arry = array_column($booking_array, 'token_number');

			$last_token = end($last_token_booking_arry);

			$last_token_number = explode('-', $last_token);

			$token = $last_token_number[1] + 1;

			$real_token_number = "TKN-" . sprintf("%03s", $token);

			$real_book_token = Booking::create([
				'token_number' =>  $real_token_number,
				'name' => $request->name,
				'age' => $request->age,
				'phone' => $request->phone,
				'address' => $request->address,
				'booking_date' => $check_date,
				'status' => 1,
				'user_id' => 1,
				'doctor_id' => request('doctor'),
				'floor_id' => 1,
				'booking_status' => $booking_status,
				'zoom_id' => $zoom_id,
				'zoom_psw' => $zoom_psw,
				'start_url' => $start_url,
				'join_url' => $join_url,
			]);

		}
		$doctor= Doctor::findOrfail(request('doctor'));
		$doctorService= $doctor->services->sum('charges');
		$amount1  =$doctor->online_early_payment/1700; //1.76
		$amount2=round($amount1, 2);

		$amount3 = $amount2* 100;
		$amount =sprintf("%012s", $amount3);
			// dd($doctorService->sum('charges'));
			// alert()->success('Token Number', $real_token_number)->persistent('Close');
		return view('payments.payment4',compact('doctorService','real_book_token','doctor','amount'));

	}

	protected function editBookingRecord(Request $request)
	{

		try {

			$booking = Booking::findOrFail($request->booking_id);
		} catch (\Exception $e) {

			alert()->error("Booking Not Found!")->persistent("Close!");

			return response()->json([
				'status' => "failed",
			]);
		}

		$booking->name = $request->name;

		$booking->age = $request->age;

		$booking->phone = $request->phone;

		$withdateOrnodate= $request->withdateOrnodate;

		if ($booking->save()) {

			return response()->json([$booking->save(),$withdateOrnodate]);;
		} else {

			alert()->error("Database Error!")->persistent("Close!");

			return redirect()->back();
		}
	}

	protected function adminconfirmbooking(Request $request)
	{

		try {

			$booking = Booking::findOrFail($request->booking_id);
		} catch (\Exception $e) {

			alert()->error("Booking Not Found!")->persistent("Close!");

			return response()->json([
				'status' => "failed",
			]);;
		}

		$booking->status = 1;

		if ($booking->save()) {

			return response()->json($booking->save());;
		} else {

			alert()->error("Database Error!")->persistent("Close!");

			return redirect()->back();
		}
	}

	protected function admincheckinbooking(Request $request)
	{

		try {

			$booking = Booking::findOrFail($request->booking_id);
		} catch (\Exception $e) {

			alert()->error("Booking Not Found!")->persistent("Close!");

			return response()->json([
				'status' => "failed",
			]);;
		}

		$booking->status = 4;

		if ($booking->save()) {

			return response()->json($booking->save());;
		} else {

			alert()->error("Database Error!")->persistent("Close!");

			return redirect()->back();
		}
	}
	protected function admincanclebooking(Request $request)
	{

		try {

			$booking = Booking::findOrFail($request->booking_id);
		} catch (\Exception $e) {

			alert()->error("Booking Not Found!")->persistent("Close!");

			return response()->json([
				'status' => "failed",
			]);;
		}

		$booking->status = 2;

		if ($booking->save()) {

			return response()->json($booking->save());;
		} else {

			alert()->error("Database Error!")->persistent("Close!");

			return redirect()->back();
		}
	}


	protected function admindonebooking(Request $request)
	{

		try {
			$booking = Booking::findOrFail($request->booking_id);
		} catch (\Exception $e) {

			alert()->error("Booking Not Found!")->persistent("Close!");

			return response()->json([
				'status' => "failed",
			]);;
		}
		if ($booking->description ==null || $booking->diagnosis == null || $booking->remark_booking_date==null) {

			return response()->json([
				$booking->doctor->services,
				0,
			]);
		}
		$booking->status = 5;
		if ($booking->save()) {

			return response()->json([
				$booking->doctor->services,
				1,
			]);
		} else {

			alert()->error("Database Error!")->persistent("Close!");

			return redirect()->back();
		}
	}

	protected function checkedallconfirm(Request $request)
	{

		try {

			$checked_ids = $request->checked_id;
		} catch (\Exception $e) {

			alert()->error("Something worng!")->persistent("Close!");

			return response()->json([
				'status' => "failed",
			]);;
		}
		$checked_id_objs = (object) $checked_ids;

		foreach ($checked_id_objs as $checked_id_obj) {

			$bookingcomfirm = Booking::findOrFail($checked_id_obj);
			$bookingcomfirm->status = 1;
			$bookingcomfirm->save();
		}
		return response()->json(1);
		// $booking->status = 1;

		// if($booking->save()){

		// 	return response()->json($booking->save());;

		// }else{

		//     alert()->error("Database Error!")->persistent("Close!");

		//      return redirect()->back();
		// }
	}

	//For mobile app
	protected function announcementStore(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'title' => 'required',
			'description' => 'required',
			'short_description' => 'required',
			'photo' => 'required',
		]);

		if ($validator->fails()) {

			alert()->error('Something Wrong');

			return redirect()->back();
		}

		$booking_range = request('range');

		$weekormonth = request('weekormonth');

		if ($request->hasfile('photo')) {

			$image = $request->file('photo');

			$name = $image->getClientOriginalName();

			$image_name =  time() . "-" . $name;

			$image->move(public_path() . '/image/ann/', $image_name);

			$image = $image_name;
		}

		$now = new DateTime('Asia/Yangon');

		$today = $now->format('Y-m-d');

		$today_string = strtotime($today);

		if ($weekormonth == "month") {

			$expire_date = strtotime("+$booking_range months", $today_string);
		} else {

			$expire_date = strtotime("+$booking_range week", $today_string);
		}

		$announcement = Announcement::create([
			'title' => request('title'),
			'description' => request('description'),
			'short_description' => request('short_description'),
			'photo_path' => $image_name,
			'slide_status' => 0,
			'expired_at' => date('Y-m-d', $expire_date),
		]);

		alert()->success('Successfully Added!')->autoclose(2000);

		return redirect()->back();
	}

	protected function advertiesmentStore(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'title' => 'required',
			'short_description' => 'required',
			'description' => 'required',
			'short_description' => 'required',
			'photo' => 'required',
			'start_date' => 'required'
		]);

		if ($validator->fails()) {

			alert()->error('Something Wrong');

			return redirect()->back();
		}

		$booking_range = request('range');

		$weekormonth = request('weekormonth');

		if ($request->hasfile('photo')) {

			$image = $request->file('photo');

			$name = $image->getClientOriginalName();

			$image_name =  time() . "-" . $name;

			$image->move(public_path() . '/image/adv/', $image_name);

			$image = $image_name;
		}

		$today = Carbon::parse($request->start_date);
		// $now = $request->start_date;
		// dd($now);
		// $today = $now->format('Y-m-d');
		$req_date = $today->format('Y-m-d');
		$today_string = strtotime($today);

		if ($weekormonth == "month") {

			$expire_date = strtotime("+$booking_range months", $today_string);
		} else {

			$expire_date = strtotime("+$booking_range week", $today_string);
		}

		$advertisement = Advertisement::create([
			'title' => request('title'),
			'description' => request('description'),
			'short_description' => request('short_description'),
			'photo_path' => $image_name,
			'expired_at' => date('Y-m-d', $expire_date),
			'start_date' =>  $req_date
		]);

		alert()->success('Successfully Added!')->autoclose(2000);

		return redirect()->back();
	}

	public function advertiesmentIndex()
	{
		$advertisements = Advertisement::all();
		return view('Admin.Advertisment.advertisment', compact('advertisements'));
	}
	public function announcementIndex()
	{
		$announcements = Announcement::all();
		return view('Admin.Advertisment.announcement', compact('announcements'));
	}

	protected function getStateList()
	{

		$state_lists = State::all();

		return view('Admin.state_list', compact('state_lists'));
	}

	protected function storeTown(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'code' => 'required',
			'name' => 'required',
			'state_id' => 'required',
			'allowdelivery'=> 'required',
		]);
		if($request->allowdelivery == 1){
			$validator = Validator::make($request->all(), [
				'code' => 'required',
				'name' => 'required',
				'state_id' => 'required',
				'allowdelivery'=> 'required',
				'charges' => 'required'
			]);
		}
		if ($validator->fails()) {

			alert()->error('Something Wrong!');

			return redirect()->back();
		}

		try {

			$town = Town::create([
				'town_code' => $request->code,
				'town_name' => $request->name,
				'state_id' => $request->state_id,
				'status' => $request->allowdelivery,
				'delivery_charges'=> $request->charges,
			]);

		} catch (\Exception $e) {

			alert()->error('Something Wrong!');

			return redirect()->back();
		}

		alert()->success('Successfully Added');

		return redirect()->back();
	}

	protected function ajaxSearchTown(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'state_id' => 'required',
		]);

		if ($validator->fails()) {

			return response()->json(array("errors" => $validator->getMessageBag()), 422);
		}

		$town_lists = Town::where('state_id', $request->state_id)->get();

		return response()->json($town_lists);
	}

	protected function editTown(Request $request)
	{

		try {

			$town = Town::findOrFail($request->town_id);
		} catch (\Exception $e) {

			alert()->error("Town Not Found!")->persistent("Close!");

			return redirect()->back();
		}


		$town->town_code = $request->code;
		$town->town_name = $request->name;
		$town->status = $request->allowdelivery;
		$town->delivery_charges = $request->editcharges;

		$town->save();

		alert()->success('Successfully Updated!');

		return redirect()->back();
	}
}
