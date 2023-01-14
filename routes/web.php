<?php

use App\Doctor;
use App\Events\DoctorChange;
use App\Events\TestingEvent;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Expr\FuncCall;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/pusher', function(){
	event(new DoctorChange('Hey how are you'));
});

Route::get('/', 'Web\LoginController@index')->name('login_page');

Route::post('LoginProcess', 'Web\LoginController@LoginProcess')->name('user_login');

Route::get('/LogoutProcess', 'Web\LoginController@LogoutProcess')->name('logout');

Route::group(['middleware' => ['UserAuth']], function () {

	//Common Ajax Function

	Route::post('AjaxDepartment', 'Web\ScheduleController@AjaxDepartment')->name('AjaxDepartment');

	Route::post('AjaxScheduleDate', 'Web\ScheduleController@AjaxScheduleDate')->name('AjaxScheduleDate');

	Route::post('AjaxScheduleTime', 'Web\ScheduleController@AjaxScheduleTime')->name('AjaxScheduleTime');

	//Announcement & Advertisement

	Route::post('Announcement_Store', 'Web\OperatorController@announcementStore')->name('announcement_store');

	Route::get('Announcement', 'Web\OperatorController@announcementIndex')->name('announcement.index');

	Route::post('Advertisement_Store', 'Web\OperatorController@advertiesmentStore')->name('advertisement_store');

	Route::get('Advertisement', 'Web\OperatorController@advertiesmentIndex')->name('advertisement.index');


	//Building Controller

	Route::get('BuildingInfo', 'Web\BuildingController@BuidlingList')->name('buidling_info');

	Route::post('StoreBuidling', 'Web\BuildingController@StoreBuidling')->name('buidling_store');

	Route::post('StoreFloor', 'Web\BuildingController@StoreFloor')->name('floor_store');

	Route::post('StoreRoom', 'Web\BuildingController@StoreRoom')->name('room_store');

	Route::post('AjaxCheckRoomTime', 'Web\BuildingController@AjaxCheckRoomTime');

	Route::post('AjaxRoomCheck', 'Web\BuildingController@AjaxRoomCheck');

	Route::post('AjaxBuildingCheck', 'Web\BuildingController@AjaxBuildingCheck');

	Route::post('AjaxRoomList', 'Web\BuildingController@AjaxRoomList');

	//Schedule Controller


	Route::post('StoreChangeDoctorList', 'Web\ScheduleController@storeChangeDoctorList')->name('store_change_doctor');

	Route::post('revisedLists', 'Web\ScheduleController@revisedLists')->name('revisedLists');


	Route::get('ScheduleList', 'Web\ScheduleController@ScheduleList')->name('schedule_list');

	Route::get('CreateScheduleDay', 'Web\ScheduleController@CreateScheduleDay')->name('create_schedule_day');

	Route::post('StoreScheduleDay', 'Web\ScheduleController@StoreScheduleDay')->name('store_schedule_day');


	Route::post('StoreDoctorTime', 'Web\ScheduleController@StoreDoctorTime')->name('store_doctor_time');

	//Operator Controller

	Route::get('AdminDashboard', 'Web\OperatorController@AdminDashboard')->name('admin_dashboard');

	Route::get('AdminBookingList', 'Web\OperatorController@getBookingListUi')->name('admin_booking_list');

	Route::post('DoctorBookingList', 'Web\OperatorController@ajaxDoctorBookingList')->name('ajax_doc_booking_list');

	Route::get('TokenCheckInUI', 'Web\OperatorController@getTokencheckinUI')->name('token_checkin');

	Route::post('AjaxTokenCheckIn', 'Web\OperatorController@ajaxTokenCheckIn');

	Route::get('AdminProfile', 'Web\OperatorController@AdminProfile')->name('admin_profile');

	Route::get('CounterProfile/{admin_id}', 'Web\OperatorController@counterProfile')->name('counter_profile');

	Route::get('CounterProfileEdit/{admin_id}', 'Web\OperatorController@counterProfileEdit')->name('counter_profile_edit');

	Route::post('CounterProfileEdit', 'Web\OperatorController@counterProfileEditSave')->name('counter_profile_edit_save');

	Route::get('CreateCounter', 'Web\OperatorController@createCounter')->name('create_counter');

	Route::post('CreateCounter/save', 'Web\OperatorController@createCounterSave')->name('create_counter_save');


	Route::get('ChangeAdminPasswordUI', 'Web\OperatorController@AdminChangePassUI')->name('change_admin_pw_ui');

	Route::put('ChangeAdminPassword', 'Web\OperatorController@AdminChangePass')->name('change_admin_pw');

	Route::get('DepartmentList', 'Web\OperatorController@DepartmentList')->name('department_list');

	Route::get('CreateDepartment', 'Web\OperatorController@CreateDepartment')->name('create_department');

	Route::post('StoreDepartment', 'Web\OperatorController@StoreDepartment')->name('store_department');

	Route::get('EditDepartment/{department}', 'Web\OperatorController@EditDepartment')->name('edit_department');

	Route::put('UpdateDepartment/{department}', 'Web\OperatorController@UpdateDepartment')->name('update_department');

	Route::get('GetToken', 'Web\OperatorController@GetToken')->name('get_token');

	Route::post('SearchDoctors', 'Web\OperatorController@SearchDoctors');

	Route::post('StoreBookingToken', 'Web\OperatorController@StoreBookingToken')->name('store_booking_token');

	// Route::post('EditBookingRecord', 'Web\OperatorController@editBookingRecord')->name('edit_booking_record');

	Route::post('AdminConfirmBooking', 'Web\OperatorController@adminconfirmbooking')->name('admin_confirm_booking');

	Route::post('AdminCheckInBooking', 'Web\OperatorController@admincheckinbooking')->name('admin_checkin_booking');


	Route::post('checkedAllConfirm', 'Web\OperatorController@checkedallconfirm')->name('checkedAllConfirm');

	Route::post('AdminCancleBooking', 'Web\OperatorController@admincanclebooking')->name('admin_cancle_booking');

	Route::post('AdminDoneBooking', 'Web\OperatorController@admindonebooking')->name('admin_done_booking');

	Route::get('StateList', 'Web\OperatorController@getStateList')->name('state_list');

	Route::post('StoreTown', 'Web\OperatorController@storeTown')->name('store_town');

	Route::post('AjaxSearchTown', 'Web\OperatorController@ajaxSearchTown');

	Route::post('EditTown', 'Web\OperatorController@editTown')->name('edit_town');

	//DoctorController

	Route::get('DoctorList', 'Web\DoctorController@DoctorList')->name('doctor_list');

	Route::get('CreateDoctor', 'Web\DoctorController@CreateDoctor')->name('create_doctor');

	Route::post('StoreDoctor', 'Web\DoctorController@StoreDoctor')->name('store_doctor');

    //Shop Owner
    Route::get('CreateShopowner', 'Web\OperatorController@createShopowner')->name('create_shopowner');
    Route::post('CreateShoporder/save', 'Web\OperatorController@createShoporderSave')->name('create_shoporder_save');
    Route::get('Shopowner', 'Web\OperatorController@Shopowner')->name('shopowner');
    Route::get('Shop/SaleHistory','Web\OperatorController@getShopSaleHistoryPage')->name('shop_sale_history');
    Route::post('Shop/Search-History', 'Web\OperatorController@searchShopSaleHistory')->name('search_shopsale_history');
    Route::get('Shop/Voucher-Details/{id}', 'Web\OperatorController@getShopVoucherDetails')->name('getShopVoucherDetails');
    Route::post('voucher-delete', 'Web\OperatorController@voucherDelete')->name('voucher_delete');
    Route::get('clinicone', 'Web\OperatorController@clinicone')->name('clinicone');
    //Shop Inventoy
    Route::get('shop_category_lists','Web\InventoryController@shop_category_lists')->name('shop_category_lists');
    Route::post('shop_store_category','Web\InventoryController@shop_store_category')->name('shop_category_store');
	Route::post('shop_category/update/{id}', 'Web\InventoryController@shop_updateCategory')->name('shop_category_update');
	Route::post('category/delete', 'Web\InventoryController@shop_deleteCategory');

    Route::get('shop_sub_category_list','Web\InventoryController@shop_sub_category_list')->name('shop_sub_category_lists');
	Route::post('shop_subcategory/store', 'Web\InventoryController@shop_storeSubCategory')->name('shop_sub_category_store');
	Route::post('shop_subcategory/update/{id}', 'Web\InventoryController@shop_updateSubCategory')->name('shop_sub_category_update');
	Route::post('shop_subcategory/delete', 'Web\InventoryController@shop_deleteSubCategory');

    Route::get('shop_brand_list','Web\InventoryController@shop_brand_list')->name('shop_brand_lists');
	Route::post('shop_brand/store', 'Web\InventoryController@shop_storeBrand')->name('shop_brand_store');
	Route::post('shop_brand/update/{id}', 'Web\InventoryController@shop_updateBrand')->name('shop_brand_update');
	Route::post('shop_brand/delete', 'Web\InventoryController@shop_deleteBrand');

    Route::get('shop_type_list','Web\InventoryController@shop_type_list')->name('shop_type_lists');
	Route::post('shop_type/store', 'Web\InventoryController@shop_storeType')->name('shop_type_store');
	Route::post('shop_type/update/{id}', 'Web\InventoryController@shop_updateType')->name('shop_type_update');
	Route::post('shop_type/delete', 'Web\InventoryController@shop_deleteType');

    Route::get('shop_item', 'Web\InventoryController@shopitemList')->name('shop_item_list');
    Route::post('shopSubCategory', 'Web\InventoryController@shopSubCategory');
    Route::post('shopBrand', 'Web\InventoryController@shopBrand');
    Route::post('shopType', 'Web\InventoryController@shopType');
    Route::post('item/shopstore', 'Web\InventoryController@shopstoreItem')->name('shop_item_store');

    Route::get('Shop-Count-Unit/{item_id}', 'Web\InventoryController@getShopUnitList')->name('shop_count_unit_list');
    Route::post('Shop-Count-Unit/store', 'Web\InventoryController@storeShopUnit')->name('shop_count_unit_store');

    Route::get('shop-item-assign', 'Web\InventoryController@shopitemAssign')->name('shop_item_assign');
    Route::post('assign-shopitem', 'Web\InventoryController@AssignShopitem');
    Route::post('assign-shopitem-ajax', 'Web\InventoryController@shopitemAssignajax')->name('shopitem_assign_ajax');

    Route::post('getCountingUnitsByShopItemCode', 'Web\OperatorController@getCountingUnitsByShopItemCode');
    Route::post('AjaxGetCustomerwID','Web\OperatorController@getSalesCustomerWithID')->name('AjaxGetCustomerwID');
    Route::post('AjaxStoreCustomer','Web\OperatorController@storeSalesCustomer')->name('AjaxStoreCustomer');
    Route::post('saleCustomerDelete','Web\OperatorController@deleteSalesCustomer')->name('saleCustomerDelete');
    Route::post('testVoucher', 'Web\OperatorController@storetestVoucher');
    Route::post('getCustomerInfo', 'Web\OperatorController@getCustomerInfo');
    //End Inventory

    //Shop Purchase
    Route::get('Purchase', 'Web\AdminController@getPurchaseHistory')->name('purchase_list');
    Route::get('Purchase/Details/{id}', 'Web\AdminController@getPurchaseHistoryDetails')->name('purchase_details');
    Route::get('Purchase/Create', 'Web\AdminController@createPurchaseHistory')->name('create_purchase');
    Route::post('purchseupdate-ajax', 'Web\StockController@purchaseUpdateAjax')->name('purchaseupdate-ajax');
    Route::post('purchase_delete', 'Web\AdminController@purchaseDelete')->name('purchase_delete');
    Route::post('purchaseprice/update', 'Web\AdminController@purchasepriceUpdate')->name('purchasepriceupdate');
    Route::post('Purchase/Store', 'Web\AdminController@storePurchaseHistory')->name('store_purchase');
    Route::get('suppliercreditlist','Web\AdminController@show_supplier_credit_lists')->name('supplier_credit_list');
    Route::post('store_supplier', 'Web\AdminController@store_supplier')->name('store_supplier');
    Route::get('supcredit/{id}','Web\AdminController@supplier_credit')->name('supcredit');
    Route::post('store_all_suppliercredit/{id}','Web\AdminController@store_allSupplierPaid')->name('store_all_suppliercredit');
    Route::post('store_each_paid_supplier','Web\AdminController@store_eachPaidSupplier')->name('store_each_paid_supplier');
    Route::post('getPurchaseData','Web\AdminController@getPurchase_Info')->name('getPurchaseData');

	//Inventory

	Route::get('category_list','Web\InventoryController@category_list')->name('show_category_lists');
	Route::post('store_category','Web\InventoryController@store_category')->name('category_store');
	Route::post('category/update/{id}', 'Web\InventoryController@updateCategory')->name('category_update');
	Route::post('category/delete', 'Web\InventoryController@deleteCategory');

	Route::get('sub_category_list','Web\InventoryController@sub_category_list')->name('show_sub_category_lists');
	Route::post('subcategory/store', 'Web\InventoryController@storeSubCategory')->name('sub_category_store');
	Route::post('subcategory/update/{id}', 'Web\InventoryController@updateSubCategory')->name('sub_category_update');
	Route::post('subcategory/delete', 'Web\InventoryController@deleteSubCategory');

	Route::get('brand_list','Web\InventoryController@brand_list')->name('show_brand_lists');
	Route::post('brand/update/{id}', 'Web\InventoryController@updateBrand')->name('brand_update');
	Route::post('brand/store', 'Web\InventoryController@storeBrand')->name('brand_store');
    Route::post('brand/delete', 'Web\InventoryController@deletebrand');
	Route::post('showSubCategory', 'Web\InventoryController@showSubCategory');

	Route::get('type_list','Web\InventoryController@type_list')->name('show_type_lists');
	Route::post('type/store', 'Web\InventoryController@storeType')->name('type_store');
    Route::post('type/delete', 'Web\InventoryController@deletetype');
	Route::post('type/update/{id}', 'Web\InventoryController@updateType')->name('type_update');
	Route::post('showBrand', 'Web\InventoryController@showBrand');


	Route::get('item_list','Web\InventoryController@item_list')->name('show_item_lists');
	Route::post('item/store', 'Web\InventoryController@storeItem')->name('item_store');
	Route::post('item/update', 'Web\InventoryController@updateItem')->name('item_update');
	Route::post('item_delete', 'Web\InventoryController@deleteItem')->name('item_delete');
	Route::post('showType', 'Web\InventoryController@showType');
	Route::post('showSubCategoryFrom', 'Web\InventoryController@showSubCategoryFrom');

	Route::post('ajaxitemdetail', 'Web\InventoryController@ajaxitemdetail');


//Service

Route::get('services','Web\ServiceController@serviceList')->name('services.lists');
Route::post('services/update/{id}','Web\ServiceController@serviceUpdate')->name('services.update');
Route::post('services/store','Web\ServiceController@serviceStore')->name('services.store');
Route::post('services/delete','Web\ServiceController@serviceDelete')->name('services.delete');

// DoctorServices ajax
Route::post('/doctor/services','Web\ServiceController@doctorServices')->name('doctor.services');
Route::post('/ajaxservices/other-services','Web\ServiceController@ajaxOtherServices');
Route::post('/ajaxpackages','Web\PackageController@ajaxpackageList');

//Package
Route::get('packages','Web\PackageController@packageList')->name('packages.lists');
Route::post('packages/update/{id}','Web\PackageController@packageUpdate')->name('packages.update');
Route::post('packages/store','Web\PackageController@packageStore')->name('packages.store');
Route::post('packages/delete','Web\PackageController@packageDelete')->name('packages.delete');


    //Counting Unit
	Route::get('Count-Unit/{item_id}', 'Web\InventoryController@getUnitList')->name('count_unit_list');
    Route::post('Count-Unit/store', 'Web\InventoryController@storeUnit')->name('count_unit_store');
    Route::post('Count-Unit/update/{id}', 'Web\InventoryController@updateUnit')->name('count_unit_update');
    Route::post('Count-Unit/code_update/{id}', 'Web\InventoryController@updateUnitCode')->name('count_unit_code_update');
    Route::post('Count-Unit/original_code_update/{id}', 'Web\InventoryController@updateOriginalCode')->name('original_code_update');
    Route::post('Count-Unit/delete', 'Web\InventoryController@deleteUnit');
    Route::post('AjaxGetItem', 'Web\InventoryController@AjaxGetItem');
    Route::post('searchItem', 'Web\InventoryController@searchItem');

    //Counting Unit Relation
    Route::get('Unit-Relation/{item_id}', 'Web\InventoryController@unitRelationList')->name('unit_relation_list');
    Route::post('Unit-Relation/store', 'Web\InventoryController@storeUnitRelation')->name('unit_relation_store');
    Route::post('Unit-Relation/update/{id}', 'Web\InventoryController@updateUnitRelation')->name('unit_relation_update');

    //Counting Unit Conversion
    Route::get('Unit-Convert/{unit_id}', 'Web\InventoryController@convertUnit')->name('convert_unit');
    Route::post('ajaxCountUnit', 'Web\AdminController@ajaxCountUnit')->name('ajaxCountUnit');
    //Route::post('Unit-Convert/store', 'Web\InventoryController@convertCountUnit')->name('convert_count_unit');

    //StockCount
    Route::get('Stock-Count/Count', 'Web\StockController@getStockCountPage')->name('stock_count');
    Route::get('Stock-Count/Reorder', 'Web\StockController@getStockReorderPage')->name('stock_reorder_page');

	//AJAX INVENTORY
    Route::post('ajaxConvertResult', 'Web\InventoryController@ajaxConvertResult');

	//End inventory

	//start Sale
	  Route::get('Sale', 'Web\SaleController@getSalePage')->name('sale_page');
	  Route::post('Sale/Voucher', 'Web\SaleController@storeVoucher');
	  Route::post('Sale/Get-Voucher', 'Web\SaleController@getVucherPage')->name('get_voucher');
	  Route::get('Sale/History', 'Web\SaleController@getSaleHistoryPage')->name('sale_history');
	  Route::get('Sale/SummaryMain','Web\SaleController@getVoucherSummaryMain')->name('voucher_summary_main');
	  Route::post('Sale/SummaryDetail','Web\SaleController@searchItemSalesByDate')->name('search_item_sales_by_date');
	  Route::post('Sale/Search-History', 'Web\SaleController@searchSaleHistory')->name('search_sale_history');
	  Route::get('Sale/Search-History', 'Web\SaleController@searchSaleHistoryget');
	  Route::get('Sale/Voucher-Details/{id}', 'Web\SaleController@getVoucherDetails')->name('getVoucherDetails');

	  Route::post('calculate_current','Web\SaleController@calculateCurrent');

	  Route::post('getCountingUnitsByItemId', 'Web\SaleController@getCountingUnitsByItemId');
	  Route::post('delivery/states', 'Web\SaleController@deliveryState');

//End Sale
//ORDER
		Route::get('Order/Voucher-Details/{id}', 'Web\OrderController@getVoucherDetails')->name('voucher_order_details');


	//DOCTOR DASHBORAD


//Clinic
	Route::get('patient/register', 'Web\ClinicController@patientregister')->name('patientregister');
	Route::post('appointment/store', 'Web\ClinicController@appointmentStore')->name('appointmentstore');
	Route::post('searchpatient', 'Web\ClinicController@searchpatient');
	Route::post('oldpatient/appointment', 'Web\ClinicController@oldpatientAppointment')->name('appointment.oldpatient');
	Route::get('appointments/{patient_id}', 'Web\ClinicController@appointments')->name('appointments');

	//today appointments
	Route::get('appointments', 'Web\ClinicController@todayAppointments')->name('today.appointments');
	Route::post('searchpatient/todayappointments', 'Web\ClinicController@searchpatientToday');
	Route::post('appointments/delete', 'Web\ClinicController@todayaptdelete')->name('todayaptdelete');

	Route::post('searchAppointments/filter', 'Web\ClinicController@searchAppointments');

	Route::get('records/{appointment_id}', 'Web\ClinicController@appointmentRecord')->name('appointmentRecord');
	Route::get('patient/history/{appointment_id}', 'Web\ClinicController@patientHistory')->name('patienthist');
	Route::post('store/record', 'Web\ClinicController@storeRecord')->name('storeRecord');
	Route::post('store/recordinfo', 'Web\ClinicController@storeRecordInfo')->name('storeRecordInfo');
	Route::post('attachments/store', 'Web\ClinicController@attachmentsStore')->name('attachments.store');

	Route::post('attachments/delete', 'Web\ClinicController@attachmentsDelete')->name('attachments.delete');
	Route::post('addservices', 'Web\ClinicController@addserviceCounter')->name('addserviceCounter');

	//clinic history
	Route::get('clinichistory', 'Web\ClinicController@history')->name('history');

	Route::post('clinic/storevoucher', 'Web\ClinicController@storeVoucher')->name('clinic.storevoucher');

	Route::get('Diagnosis', 'Web\ClinicController@getDiagnosis')->name('getDiagnosis');

	Route::post('Diagnosis/store', 'Web\ClinicController@diagnosisStore')->name('diagnosis_store');

	Route::post('Diagnosis/storeOntime', 'Web\ClinicController@diagnosisStoreOntime')->name('diagnosis_store_ontime');

	Route::post('Vaccines/storeOntime', 'Web\ClinicController@vaccinesStoreOntime')->name('vaccines_store_ontime');

	Route::post('attachmentimage', 'Web\ClinicController@attachimg')->name('attachimg');

	Route::post('medicalrecord', 'Web\ClinicController@medicalrecord')->name('medicalrecord');
	Route::get('medicalrecord', function () {
		return back();
	});
	Route::get('vaccine-record/{patient_id}', 'Web\ClinicController@vaccinerecord')->name('vaccinerecord');

});

Route::group(['middleware' => ['UserAuth']], function () {


	Route::get('editDoctor/{id}', 'Web\DoctorController@editDoctor')->name('edit_doctor');

	Route::post('edit/StoreDoctor', 'Web\DoctorController@editStoreDoctor')->name('edit_store_doctor');

	//doctor admin
	Route::post('EditBookingRecord', 'Web\OperatorController@editBookingRecord')->name('edit_booking_record');
	Route::get('CheckDoctorProfile/{doctor}', 'Web\DoctorController@CheckDoctorProfile')->name('check_doctor_profile');
	Route::get('CheckScheduleTime/{day}/{doctor}', 'Web\ScheduleController@CheckScheduleTime')->name('check_schedule_time');

	Route::get('ChangeScheduleList', 'Web\ScheduleController@ChangeScheduleList')->name('change_sch_list');

	Route::post('StoreChangeScheduleList', 'Web\ScheduleController@storeChangeScheduleList')->name('store_change_schedule');
	//

	Route::post('DoctorDoneBooking', 'Web\DoctorDashboardController@doctordonebooking')->name('doctor_done_booking');

	Route::get('DoctorScheduleList', 'Web\DoctorDashboardController@DoctorScheduleList')->name('doctor.schedulelist');

	Route::get('doctor/dashboard', 'Web\DoctorDashboardController@doctorDashboard')->name('doctor.dashboard');

	Route::get('doctor/profile', 'Web\DoctorDashboardController@doctorProfile')->name('doc.profile');

	Route::get('doctor/manualbookinglists', 'Web\DoctorDashboardController@manualbookingLists')->name('doctor.manualbookings');

	Route::get('doctor/onlinebookinglists', 'Web\DoctorDashboardController@onlinebookingLists')->name('doctor.onlinebookings');

	Route::get('doctor/patientHistory', 'Web\DoctorDashboardController@patientHistory')->name('doctor.patientHistory');
	Route::post('ajax/patientHistory', 'Web\DoctorDashboardController@ajaxPatientHistory')->name('ajaxPatientHistory');


	Route::post('doctor/ajax/manual/bookinglists', 'Web\DoctorDashboardController@ajaxDoctorManualBookingList')->name('ajax_doc_manual_bookings');

	Route::post('doctor/ajax/online/bookinglists', 'Web\DoctorDashboardController@ajaxDoctorOnlineBookingList')->name('ajax_doc_online_bookings');

	Route::post('doctor/startzoom', 'Web\DoctorDashboardController@startzoom')->name('startzoom');

	Route::get('payment/payment4', 'Web\PaymentTestController@payment4')->name('payment4');

	Route::get('storepayment/web/{booking_id}/{invoice_no}', 'Web\DoctorDashboardController@storepaymentweb')->name('storepayment.web');

	Route::get('change_clinic/{clinic_id}', 'Web\AdminController@changeclinic')->name('change_clinic');

    Route::get('change_shop/{shop_id}', 'Web\AdminController@changeshop')->name('change_shop');

	Route::get('item-assign', 'Web\InventoryController@itemAssign')->name('item_assign');

	Route::post('assign-item-ajax', 'Web\InventoryController@itemAssignajax')->name('item_assign_ajax');

	Route::post('assign-itemshop', 'Web\InventoryController@itemAssignShop');

	//stock
	Route::get('Stock-Count/Count', 'Web\StockController@getStockCountPage')->name('stock_count');

	Route::post('Stock-Count/UpdateCount', 'Web\StockController@updateStockCount')->name('update_stock_count');

	Route::post('AjaxGetItem', 'Web\InventoryController@AjaxGetItem')->name('AjaxGetItem');

    Route::post('AjaxGetCountingUnit', 'Web\InventoryController@AjaxGetCountingUnit')->name('AjaxGetCountingUnit');

	Route::post('stockupdate-ajax', 'Web\StockController@stockUpdateAjax')->name('stockupdate-ajax');

	Route::get('vaccine', 'Web\ClinicController@vaccine')->name('vaccine');

	Route::get('patient-profile/{patient_id}', 'Web\ClinicController@patientProfile')->name('patient_profile');
	Route::post('patient-profile-update', 'Web\ClinicController@patientProfileUpdate')->name('patient_profile_update');

});


Route::get('payment/test', 'Web\PaymentTestController@payment1')->name('pay');
Route::get('payment/payment2', 'Web\PaymentTestController@payment2')->name('pay');
Route::get('payment/payment3', 'Web\PaymentTestController@payment3');
Route::get('success', function(){
	dd("success");
});
Route::post('payment/payment3/data', 'Web\PaymentTestController@payment3data')->name('payment3');

Route::post('res1', 'Web\PaymentTestController@getres1');

Route::post('payment/payment4/data', 'Web\PaymentTestController@payment4data')->name('payment4data');

