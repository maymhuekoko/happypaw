<?php

namespace App\Http\Controllers\Web;

use App\From;
use App\Item;
use App\Stockcount;
use App\CountingUnit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    protected function getStockPanel(Request $request)
    {
        $request->session()->put('ShopOrWh','shop');
    	return view('Stock.stock_panel');
    }

    protected function getStockCountPage(Request $request)
    {

        $role= $request->session()->get('user')->role;
        if($role=='Sale_Person'){

            $item_from= $request->session()->get('user')->from_id;
            
      }
      else {
        $item_from= $request->session()->get('from');
      }
       $items= From::find($item_from)->items()->with('category')->with('sub_category')->with('counting_units')->with('counting_units.stockcount')->get();

        $shops = From::all();
        
    	return view('Stock.stock_count_page', compact('items','shops'));
    }

    protected function getStockPricePage(Request $request)
    {
        
        $role= $request->session()->get('user')->role;
        if($role=='Sale_Person' || $role=='Warehouse'){
            $item_from= $request->session()->get('user')->from;
      }
      else {
        $item_from= $request->session()->get('from');
      }

        $items= From::find($item_from)->items;

        $units = CountingUnit::whereNull('deleted_at')->orderBy('item_id', 'asc')->get();

        $fromsshop=From::find($item_from);
        $categories=[];
        $items = $fromsshop->items;
        foreach($items as $key=>$item){
           
            $category_count=count($categories);
            if($category_count ==0){
                array_push($categories,$item->category);
            }
            else{
            
                for($i=0;$i<$category_count;$i++)
            {
               
                if ($item->category->id !== $categories[$i]->id) {
                  
                    array_push($categories,$item->category);
                   
                }   
            }
        }
        }

    	return view('Stock.stock_price_page', compact('units','categories','items'));
    }

    protected function getStockReorderPage(Request $request)
    {
        $units = CountingUnit::whereColumn('stock_qty', "<=" ,'reorder_quantity')->whereNull("deleted_at")->get();
        $items= Item::all();
    	return view('Stock.reorder_page', compact('units','items'));
    }

    public function getStoPanel()
    {
        return view('Stock.s_panel');
        
    }
    protected function updateStockCount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit_id' => 'required',
            'quantity' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error('Something Wrong! Validation Error!');

            return redirect()->back();
        }

        $id = $request->unit_id;

        try {
            
            $unit = CountingUnit::findOrFail($id);

        } catch (\Exception $e) {
            
            alert()->error("Counting Unit Not Found!")->persistent("Close!");
            
            return redirect()->back();

        }

        $unit->current_quantity = $request->quantity;

        $unit->reorder_quantity = $request->reorder??$unit->reorder_quantity;

        $unit->save();

        alert()->success('Successfully Updated!');

        return redirect()->back();
    }
    public function stockUpdateAjax(Request $request)
    {
        $stock = Stockcount::updateOrCreate([
            'counting_unit_id'=> $request->unit_id,
            'from_id'=> $request->shop_id,
        ],
        [
            'stock_qty' => $request->stock_qty,
        ]
        );

        if($stock){
            return response()->json($stock);
        }
        else{
            return response()->json(0);
        }
    }
    protected function updateStockPrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit_id' => 'required',
            'purchase_price' => 'required',
            'normal_sale_price' => 'required',
            'whole_sale_price' => 'required',
            'company_sale_price' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error('Something Wrong! Validation Error!');

            return redirect()->back();
        }

        $id = $request->unit_id;

        try {
            
            $unit = CountingUnit::findOrFail($id);

        } catch (\Exception $e) {
            
            alert()->error("Counting Unit Not Found!")->persistent("Close!");
            
            return redirect()->back();

        }

        $unit->purchase_price = $request->purchase_price;

        $unit->normal_sale_price = $request->normal_sale_price;

        $unit->whole_sale_price = $request->whole_sale_price;

        $unit->company_sale_price = $request->company_sale_price;
        
        $unit->save();

        alert()->success('Successfully Updated!');

        return redirect()->back();
    }
    
}
