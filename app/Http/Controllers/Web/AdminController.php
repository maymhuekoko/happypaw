<?php

namespace App\Http\Controllers\Web;

use App\From;
use App\Purchase;
use App\ShopItem;
use App\Supplier;
use App\PayCredit;
use App\SalesCustomer;
use App\ShopStockcount;
use App\ShopCountingUnit;
use App\SupplierPayCredit;
use App\SupplierCreditList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function changeclinic($clinic_id,Request $request)
    {
        $from_id = $request->session()->put('from',$clinic_id);
        return back();
    }
    public function changeshop($shop_id,Request $request)
    {
        $from_id = $request->session()->put('from',$shop_id);
        return back();
    }
    //Shop Purchase
    protected function getPurchaseHistory(Request $request){

        $purchase_lists = Purchase::all();

        return view('Owner.purchase_lists', compact('purchase_lists'));
    }

    protected function getPurchaseHistoryDetails($id){

        try {
             $shop_counting_unit = [];
            $purchase = Purchase::findOrFail($id);
            $shop_unit = DB::table('shop_counting_unit_purchase')->where('purchase_id',$id)->get();
            foreach($shop_unit as $s_unit){
                $array = ShopCountingUnit::find($s_unit->shop_counting_unit_id);
                array_push($shop_counting_unit,$array);
            }

        } catch (\Exception $e) {

            alert()->error('Something Wrong! Purchase Cannot be Found.');

            return redirect()->back();
        }
        // dd($shop_counting_unit);

        return view('Owner.purchase_details', compact('purchase','shop_unit','shop_counting_unit'));

    }

    public function purchaseUpdateAjax(Request $request)
    {
        $purchase = Purchase::findOrfail($request->purchase_id);

        $diff_qty = $request->new_qty - $request->olderqty;

        $unit = DB::table('shop_counting_unit_purchase')->where('shop_counting_unit_id', $request->unit_id)->where('purchase_id',$request->purchase_id)->update(['quantity' => $request->new_qty]);


        $unit_first = DB::table('shop_counting_unit_purchase')->where('shop_counting_unit_id', $request->unit_id)->where('purchase_id',$request->purchase_id)->first();

        // $unit->quantity = $diff_qty;

        $diff_total= ($diff_qty) * $unit_first->price;

        $purchase_new_total = $purchase->total_price + ($diff_total);

        try {

            $purchase->total_price = $purchase_new_total;
            $purchase->save();

        } catch (\Exception $e) {
            return response()->json(0);
        }

        try {
            $update_stock = ShopStockcount::where('shop_counting_unit_id',$request->unit_id)->where('from_id',3)->first();

        } catch (\Exception $e) {
            return response()->json(0);

        }

        $balanced_qty = $update_stock->stock_qty + ($diff_qty);

        $update_stock->stock_qty= $balanced_qty;

        $update_stock->save();

        return response()->json($update_stock);

    }

    public function purchaseDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'purchase_id' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }


        // try {

        $purchase =Purchase::findOrfail($request->purchase_id);

        $purchase_units= $purchase->shop_counting_unit;

        foreach($purchase_units as $unit){

            $current_stock= ShopStockcount::where("shop_counting_unit_id",$unit->id)->where('from_id',3)->first();

            $balance_qty = $current_stock->stock_qty - $unit->pivot->quantity;
            if($balance_qty <0) {

            alert()->error("Stock ပြန်နုတ်ရန် မလုံလောက်ပါ..");

            return redirect()->back();
        }
            $current_stock->stock_qty = $balance_qty;

            $current_stock->save();

            $counting_units_delete= DB::table('shop_counting_unit_purchase')->where('shop_counting_unit_id', $unit->id)->where('purchase_id',$purchase->id)->delete();




        }
            // $purchase->counting_unit()->delete();


            $delete_credit = SupplierCreditList::where('purchase_id',$purchase->id)->first();
            $delete_credit->delete();
            $purchase->delete();
        // } catch (Exception $e) {

            // alert()->error("ဖျက်မရပါ..");

            // return redirect()->back();
        // }

        alert()->success("Successfully Deleted");

        return redirect()->route('purchase_list');

    }

    protected function createPurchaseHistory(){


        $items = ShopItem::with('shop_counting_units')->with("shop_counting_units.shop_stockcounts")->get();
        $supplier = Supplier::all();
        return view('Owner.create_purchase', compact('items','supplier'));
    }

    public function purchasepriceUpdate(Request $request)
    {
        try{
            $counting_unit = ShopCountingUnit::findOrfail($request->unit_id);
        } catch (\Exception $e) {
            return response()->json(0);
        }
        $counting_unit->update([
            'purchase_price' => $request->purchase_price,
            'normal_sale_price' => $request->normal_price,
            'whole_sale_price' => $request->whole_price,
            'order_price' => $request->order_price,
            // "normal_fixed_flash"=> $request->normal_fixed ?? 0,
            "normal_fixed_percent"=>  $request->normal_percent,
            // "whole_fixed_flash"=> $request->whole_fixed ?? 0,
            "whole_fixed_percent"=> $request->whole_percent,
            // "order_fixed_flash"=> $request->order_fixed ?? 0,
            "order_fixed_percent"=> $request->order_percent,
         ]);

         return response()->json($counting_unit);

    }

    public function show_supplier_credit_lists()
    {

        $supplier_credit_list = Supplier::all();
        return view('Owner.supplier_credit_list',compact('supplier_credit_list'));
    }

    public function store_supplier(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone_number' => 'required',
        ]);

        $suppliers = Supplier::create([
             'name' => $request->name,
             'phone_number' => $request->phone_number,
        ]);

    alert()->success('successfully stored Supplier Data');
    return back();
    }

    public function supplier_credit($id)
    {
        //   dd('hello');
        $supplier = Supplier::find($id);
        $creditlist = SupplierCreditList::all();
        $credit = SupplierCreditList::where('supplier_id',$id)->get();
       $paypay = SupplierPayCredit::where('supplier_id',$id)->get();
    //    dd($credit);
       return view('Owner.supplier_credit_detail',compact('credit','supplier','paypay','creditlist'));
    }

    public function store_allSupplierPaid(Request $request,$id)
    {
        $SID = Supplier::find($id);

        if($SID->credit_amount == 0){
            $SID->status = 1;
            $SID->save();
        }
        $purchase = SupplierCreditlist::where('supplier_id',$id)->where('paid_status',0)->get();
        $pay_amount = $request->repay;
        $supplier = Supplier::find($id);
        if($supplier->credit_amount == 0)
        {
            $supplier->status = 1;
            $supplier->save();
        }
        $saletotal = $supplier->credit_amount - $pay_amount;
        $supplier->credit_amount = $saletotal;
        $supplier->save();
        $variable = 0;
        foreach($purchase as $purchases)
        {
         $repaycreditvoucher = SupplierPayCredit::where('purchase_id',$purchases->purchase_id)->first();
        $paypay = PayCredit::where('sale_customer_id',$id)->first();
        $last = $purchases->credit_amount - $pay_amount;

        if($last > 0)
        {
            $last = $last;
        }
        else{
            $last = $last * -1;
        }
        if($purchases->credit_amount <= $pay_amount)
        {


            if($repaycreditvoucher == null)
            {
                // dd("hello");
                if($purchases->credit_amount <= $request->repay)
                {
                    $begin_amt = $purchases->credit_amount;
                }
                else{
                    $begin_amt = $pay_amount;
                }
                $purchases->credit_amount = 0;
                $purchases->paid_status = 1;
                $purchases->save();

                    $paycredit = SupplierPayCredit::create([
                        'supplier_id' => $id,
                        'left_amount' => 0,
                        'description' => $request->remark,
                        'purchase_id'=>$purchases->purchase_id,

                        'pay_amount' => $begin_amt,
                        'pay_date' => $request->repaydate,
                        'paid_status' => 1,

                         ]);



            }
            else{
                // dd("hello2");
                if($purchases->credit_amount <= $request->repay)
                {
                    $begin_amout = $purchases->credit_amount;
                }
                else{
                    $begin_amtout = $pay_amount;
                }
                $purchases->credit_amount = 0;
                $purchases->paid_status = 1;
                $purchases->save();
                $paycredit = SupplierPayCredit::create([
                    'supplier_id' => $id,
                    'left_amount' => 0,
                    'description' => $request->remark,
                    'purchase_id'=>$purchases->purchase_id,

                    'pay_amount' => $begin_amout,
                    'pay_date' => $request->repaydate,
                    'paid_status' => 1,

                     ]);
        $change_status = SupplierCreditlist::where('purchase_id',$purchases->purchase_id)->first();
        if($change_status->credit_amount == 0)
        {
            // dd("hello0000");
        $paycredd = SupplierPayCredit::where('purchase_id',$change_status->purchase_id)->get();

        foreach($paycredd as $paycreedd)
        {
        $insertone = 1;
        $paycreedd->paid_status = 1;
            // dd($paycreedd->voucher_status);
        $paycreedd->save();
        // dd($paycreedd->voucher_status);

        }
        }

            }



        $pay_amount = $last;

        }


        else
        {
            // dd($purchases->purchase_id);

                $purchases->credit_amount = $last;
            $purchases->paid_status = 0;
            $purchases->save();


            $paycredit = SupplierPayCredit::create([
                'supplier_id' => $id,
                'left_amount' => $last,
                'description' => $request->remark,
                'purchase_id'=>$purchases->purchase_id,
                'pay_amount' => $pay_amount,
                'pay_date' => $request->repaydate,
                'paid_status' => 0,

        ]);

        // dd("end");

        $pay_amount = 0;
        }

        }
        return back();
    }

    public function store_eachPaidSupplier(Request $request)
    {
        // dd($request->all());
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required',
        //     'email' => 'required|unique:App\User,email',
        //     'password' => 'required',
        //     'phone' => 'required',
        //     'role' => 'required',
        // ]);

        // if ($validator->fails()) {

        //     alert()->error("Something Wrong! Validation Error");

        //     return redirect()->back();
        // }
        $sale_customer = Supplier::find($request->supid);

        $credit_list = SupplierCreditlist::where('purchase_id',$request->purchase_id)->where('supplier_id',$request->supid)->first();

        $pay = $credit_list->credit_amount - $request->payamt;
        $credit_list->credit_amount = $pay;
        $sale_customer->credit_amount = $pay;
        $sale_customer->save();
        $credit_list->paid_status = 0;
        $credit_list->save();
        if($pay == 0)
        {
            $credit_list->paid_status =1;
            $credit_list->save();
            $pay_credit = SupplierPayCredit::create([
                'supplier_id' => $request->supid,
                'purchase_id' => $request->purchase_id,
                'left_amount' => $pay,
                'description' => $request->dest,
                'voucher_id'=>$request->vou_id,
                'pay_amount' => $request->payamt,
                'pay_date' => $request->paydate,
                'paid_status' => 1,
            ]);
        }
        elseif($pay != 0)
        {
            $credit_list->paid_status =0;
            $credit_list->save();
            $pay_credit = SupplierPayCredit::create([
                'supplier_id' => $request->supid,
                'purchase_id' => $request->purchase_id,
                'left_amount' => $pay,
                'description' => $request->dest,
                'voucher_id'=>$request->vou_id,
                'pay_amount' => $request->payamt,
                'pay_date' => $request->paydate,
                'paid_status' => 0,
            ]);
        }
        // dd($pay);
        if($pay == 0){
            $paycre = SupplierPayCredit::where('purchase_id',$request->purchase_id)->get();

            foreach($paycre as $paycree)
            {
            $paycree->paid_status = 1;

            $paycree->save();
            }
        }

        $supplier = SalesCustomer::find($request->supid);
        $creditlist = SupplierCreditlist::all();
        $credit = SupplierCreditlist::where('supplier_id',$request->supid)->get();
        $paypay = SupplierPayCredit::where('supplier_id',$request->supid)->get();

        return back()->with(compact('paypay','supplier','creditlist','credit'));

    }

    public function getPurchase_Info(Request $request)
    {
        // dd($request->credit_list_id);
        $credit_list = SupplierCreditList::find($request->credit_list_id);
        $purchase = Purchase::find($credit_list->purchase_id);
        // dd($purchase);
        return response()->json($purchase);
    }

    protected function storePurchaseHistory(Request $request){
        // dd($request->pay_method);
        $validator = Validator::make($request->all(), [
            'purchase_date' => 'required',
            'supp_name' => 'required',
            'unit' => 'required',
            'price' => 'required',
            'qty' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }

        $user_code = $request->session()->get('user')->id;

        $unit = $request->unit;
        // dd($unit);

        $price = $request->price;

        $qty = $request->qty;

        $total_qty = 0;

        $total_price = 0;

        $psub_total = 0;

        foreach($price as $p){
            foreach($qty as $q){
                $psub_total = $p * $q;
                $total_price += $psub_total;
            }
        }

        foreach ($qty as $q) {

            $total_qty += $q;
        }
        $supplier = Supplier::find($request->supp_name);
        if($request->pay_method == 1)
        {

        $supplier->credit_amount +=  $request->credit_amount;
        $supplier->save();
        }
        try {

            $purchase = Purchase::create([
                'supplier_name' => $supplier->name,
                'supplier_id' => $request->supp_name,
                'total_quantity' => $total_qty,
                'total_price' => $total_price,
                'purchase_date' => $request->purchase_date,
                'purchase_by' => $user_code,
                'credit_amount' => $request->credit_amount,
            ]);

            if($request->pay_method == 1)
            {
                // dd($request->supp_name);
                $supplier_credit = SupplierCreditList::create([
                    'supplier_id' => $request->supp_name,
                    'purchase_id' => $purchase->id,
                    'credit_amount' => $request->credit_amount,
                    // 'repay_date' => $request->repay_date,
                ]);
                // dd($supplier_credit);
            }

            //   dd($purchase);
            for($count = 0; $count < count($unit); $count++){
                // dd($purchase->shop_counting_unit());
                // $purchase->shop_counting_unit()->attach($unit[$count], ['quantity' => $qty[$count], 'price' => $price[$count]]);
                DB::table('shop_counting_unit_purchase')->insert([
                    'purchase_id' => $purchase->id,
                    'shop_counting_unit_id' => $unit[$count],
                    'quantity' => $qty[$count],
                    'price' => $price[$count],
                ]);
                // dd('hello');
                $stockcount = ShopStockcount::where('from_id',3)->where('shop_counting_unit_id',$unit[$count])->first();
                // dd($stockcount);
                $balance_qty = ($stockcount->stock_qty + $qty[$count]);

                $stockcount->stock_qty = $balance_qty;

                $stockcount->save();
                // dd($stockcount);

            }

        } catch (\Exception $e) {

            alert()->error('Something Wrong! When Purchase Store.');

            return redirect()->back();
        }

        alert()->success("Success");

        return redirect()->route('purchase_list');
    }

}
