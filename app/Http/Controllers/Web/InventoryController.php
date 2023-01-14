<?php

namespace App\Http\Controllers\Web;

use App\From;
use App\Item;
use App\Type;
use App\Brand;
use Exception;
use App\Category;
use App\ShopItem;
use App\ShopType;
use App\ShopBrand;
use App\Stockcount;
use App\SubCategory;
use App\CountingUnit;
use App\ShopCategory;
use App\UnitRelation;
use App\ShopStockcount;
use App\UnitConversion;
use App\ShopSubCategory;
use App\ShopCountingUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    //For Shop
      //category
      protected function shop_category_lists()
    {
        $category_lists =  ShopCategory::whereNull("deleted_at")->get();

		return view('Inventory.shop_category_list', compact('category_lists'));

    }
    protected function shop_store_category(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'category_code' => 'required|max:2',
            'category_name' => 'required',
        ]);
        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $excode = ShopCategory::where('category_code',$request->category_code)->get();
        foreach($excode as $ecode){
            if($request->category_code == $ecode->category_code){

                alert()->error('This Shop Category code has been used');
                return redirect()->back();
            }
        }
        $user_code = $request->session()->get('user')->name;
        try {

            $category = ShopCategory::create([
                'category_name' => $request->category_name,
                'created_by' => $user_code,
            ]);

            $category->category_code = sprintf('%s',$request->category_code);

            $category->save();

        } catch (\Exception $e) {

            alert()->error('Something Wrong! When Creating Category.');

            return redirect()->back();
        }
        alert()->success('Successfully Added');

        return redirect()->route('shop_category_lists');
    }
    protected function shop_updateCategory(Request $request,$id)
    {
        // dd($request->all());
        try {

        	$category = ShopCategory::findOrFail($id);

   		} catch (\Exception $e) {

        	alert()->error("Category Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

        $category->category_code = $request->category_code;

        $category->category_name = $request->category_name;

        $category->save();

        alert()->success('Successfully Updated!');

        return redirect()->route('shop_category_lists');
    }
    protected function shop_deleteCategory(Request $request)
    {
        $id = $request->category_id;

        $category = ShopCategory::find($id);

        $category->delete();

        return response()->json($category);
    }

    protected function shop_sub_category_list()
    {
        $categories = ShopCategory::all();

		$sub_categories = ShopSubCategory::all();

		return view('Inventory.shop_sub_category_list', compact('categories','sub_categories'));

    }
    protected function shop_storeSubCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sub_category_code' => 'required|max:3|min:3',
            'sub_category_name' => 'required',
            'category' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $exsub = ShopSubCategory::where('subcategory_code',$request->sub_category_code)->get();
        foreach($exsub as $code){
            if($request->sub_category_code == $code->subcategory_code)

           {

                alert()->error('Subcategory code has been used');
                return redirect()->back();
            }
        }
            $sub_category = ShopSubCategory::create([
                'name' => $request->sub_category_name,
                'shop_category_id' => $request->category,
            ]);

            $sub_category->subcategory_code = sprintf('%03s',$request->sub_category_code);

            $sub_category->save();

    	alert()->success('Successfully Added');

        return redirect()->route('shop_sub_category_lists');
    }
    protected function shop_updateSubCategory(Request $request,$id)
    {
        try {

        	$sub_category = ShopSubCategory::findOrFail($id);

   		} catch (\Exception $e) {

        	alert()->error("Category Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

        $sub_category->subcategory_code = $request->sub_category_code;

        $sub_category->name = $request->sub_category_name;

        $sub_category->shop_category_id = $request->category;
        $sub_category->save();

        alert()->success('Successfully Updated!');

        return redirect()->route('shop_sub_category_lists');
    }
    protected function shop_deleteSubCategory(Request $request)
	{
		$id = $request->sub_category_id;

        $subcategory = ShopSubCategory::find($id);

        $subcategory->delete();

        return response()->json($subcategory);
	}

    protected function shop_brand_list()
    {
        $brands = ShopBrand::all();
        // dd($brands);
        $categories = ShopCategory::all();
        // dd($categories);
		$subcategories = ShopSubCategory::all();

		return view('Inventory.shop_brand_list', compact('brands','categories','subcategories'));

    }
    protected function shop_storeBrand(Request $request)
    {

        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'brand_code' => 'required|max:3|min:3',
            'category' => 'required',
            'sub_category' => 'required',
            'brand_name' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $exsub = ShopBrand::where('shop_brand_code',$request->brand_code)->get();
        foreach($exsub as $code){
            if($request->brand_code == $code->brand_code)

           {

                alert()->error('Brand code has been used');
                return redirect()->back();
            }
        }
            $brand = ShopBrand::create([
                // 'brand_code' => $request->brand_code,
                'name' => $request->brand_name,
                'shop_category_id' => $request->category,
                'shop_sub_category_id' => $request->sub_category,
            ]);

            $brand->shop_brand_code = sprintf('%03s',$request->brand_code);

            $brand->save();

    	alert()->success('Successfully Added');

        return redirect()->route('shop_brand_lists');
    }
    protected function shop_updateBrand(Request $request,$id)
    {
        try {

        	$brand = ShopBrand::findOrFail($id);

   		} catch (\Exception $e) {

        	alert()->error("Brand Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

        $brand->shop_brand_code = $request->brand_code;

        $brand->name = $request->brand_name;
        $brand->shop_category_id = $request->category;
        $brand->shop_sub_category_id = $request->sub_category;
        $brand->save();

        alert()->success('Successfully Updated!');

        return redirect()->route('shop_brand_lists');
    }
    protected function shop_deleteBrand(Request $request)
	{
		$id = $request->brand_id;

        $brand = ShopBrand::find($id);

        $brand->delete();

        return response()->json($brand);
	}

    protected function shop_type_list()
    {
        $types = ShopType::all();
        // dd($types);
        $categories = ShopCategory::all();
        // dd($categories);
		$subcategories = ShopSubCategory::all();

        $brands = ShopBrand::all();

		return view('Inventory.shop_type_list', compact('types','categories','subcategories','brands'));

    }
    protected function shop_storeType(Request $request)
    {

        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'type_code' => 'required|max:3|min:3',
            'category' => 'required',
            'sub_category' => 'required',
            'brand' => 'required',
            'type_name' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $exsub = ShopType::where('shop_type_code',$request->type_code)->get();
        foreach($exsub as $code){
            if($request->shop_type_code == $code->type_code)

           {

                alert()->error('Type code has been used');
                return redirect()->back();
            }
        }
            $type = ShopType::create([
                // 'type_code' => $request->type_code,
                'name' => $request->type_name,
                'shop_category_id' => $request->category,
                'shop_sub_category_id' => $request->sub_category,
                'shop_brand_id' => $request->brand,
            ]);

            $type->shop_type_code = sprintf('%03s',$request->type_code);

            $type->save();

    	alert()->success('Successfully Added');

        return redirect()->route('shop_type_lists');
    }
    protected function shop_updateType(Request $request,$id)
    {
        try {

        	$type = ShopType::findOrFail($id);

   		} catch (\Exception $e) {

        	alert()->error("Type Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

        $type->shop_type_code = $request->type_code;

        $type->name = $request->type_name;
        $type->shop_category_id = $request->category;
        $type->shop_sub_category_id = $request->sub_category;
        $type->shop_brand_id = $request->brand;
        $type->save();

        alert()->success('Successfully Updated!');

        return redirect()->route('shop_type_lists');
    }
    protected function shop_deleteType(Request $request)
	{
		$id = $request->type_id;

        $type = ShopType::find($id);

        $type->delete();

        return response()->json($type);
	}

    protected function shopitemList()
	{
        $item_lists =  ShopItem::whereNull("deleted_at")->orderBy('shop_category_id', 'ASC')->get();

    // dd($item_lists);

        $units=  ShopCountingUnit::whereNull("deleted_at")->orderBy('id', 'ASC')->get();
        // dd($units);
		$categories =  ShopCategory::whereNull("deleted_at")->get();

		$sub_categories = ShopSubCategory::all();

		return view('Inventory.shop_item_list', compact('units','item_lists','categories','sub_categories'));
	}

    protected function shopstoreItem(Request $request)
	{

		$validator = Validator::make($request->all(), [
            'item_code' => 'required',
            'item_name' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'brand_id' => 'required',
            'type_id' => 'required',
        ]);

        if ($validator->fails()) {

        	alert()->error('Validation Error!');

            return redirect()->back();
        }

        $user_code = $request->session()->get('user')->name;

        if (isset($request->customer_console)) {

        	$customer_console = 0;   //Customer ko pya mar
        }else{

        	$customer_console = 1;	//Customer ko ma pya
        }

        if ($request->hasfile('photo_path')) {

			$image = $request->file('photo_path');

			$name = $image->getClientOriginalName();

			$photo_path =  time()."-".$name;

			$image->move(public_path() . '/photo/Item', $photo_path);
		}
		else{

			$photo_path = "default.jpg";

		}

        try {
            // dd($user_code);
            $item = ShopItem::create([
                'shop_item_code' => $request->item_code,
                'shop_item_name' => $request->item_name,
                'created_by' => $user_code,
                'photo_path' => $photo_path,
                'customer_console' => $customer_console,
                'shop_category_id' => $request->category_id,
                'shop_sub_category_id' => $request->sub_category_id,
                'shop_brand_id' => $request->brand_id,
                'shop_type_id' => $request->type_id,
            ]);

        } catch (\Exception $e) {

            alert()->error('Something Wrong! When Creating Item.');

            return redirect()->back();
        }

        alert()->success('Successfully Added');

        return redirect()->route('shop_item_list');
	}

    protected function getShopUnitList($item_id)
    {

		$units = ShopCountingUnit::where('shop_item_id', $item_id)->whereNull("deleted_at")->get();

        try {

        	$item = ShopItem::findOrFail($item_id);

   		} catch (\Exception $e) {

        	alert()->error("Item Not Found!")->persistent("Close!");

            return redirect()->back();
    	}

    	return view('Inventory.shop_unit_list', compact('units','item'));
	}

    protected function storeShopUnit(Request $request)
    {
		$validator = Validator::make($request->all(), [
            'name' => 'required',
            'current_qty' => 'required',
            'reorder_qty' => 'required',
            'purchase_price' => 'required',
            'normal_price' => 'required',
            'whole_price' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // dd($request->all());
            $counting_unit = ShopCountingUnit::create([
                'unit_name' => $request->name,
                'current_quantity' => $request->current_qty,
                'reorder_quantity' => $request->reorder_qty,
                'normal_sale_price' => $request->normal_price,
                'whole_sale_price' => $request->whole_price,
                'purchase_price' => $request->purchase_price,
                'shop_item_id' => $request->item_id,
                "normal_fixed_flash"=> $request->normal_fixed ?? 0,
                "normal_fixed_percent"=> $request->normal_fixed ? $request->normal_percent : 0,
                "whole_fixed_flash"=> $request->whole_fixed ?? 0,
                "whole_fixed_percent"=> $request->whole_fixed ? $request->whole_percent : 0,
            ]);

        }
        catch (\Exception $e) {

            alert()->error('Something Wrong! When Creating Counting Unit.');

            return redirect()->back();
        }

        alert()->success('Successfully Stored!');

        return redirect()->back();
	}

    public function shopitemAssign(Request $request)
    {
        $from = session()->get('from');
        $item_lists =  ShopItem::whereNull("deleted_at")->orderBy('shop_category_id', 'ASC')->with('froms')->get();
        // dd($item_lists);
        $shops= From::where('id',3)->orWhere('id',4)->get();
		return view('Inventory.shop_item_assign', compact('item_lists','shops'));
    }

    public function AssignShopitem(Request $request)
    {
        $item_lists =  ShopItem::whereNull("deleted_at")->orderBy('shop_category_id', 'ASC')->with('shop_category')->with("shop_sub_category")->with('froms')->get();
        $shops= From::all();
		return response()->json($item_lists) ;
    }

    public function shopitemAssignajax(Request $request)
    {
        // dd($request->item_array);
        try{
            $from = From::find($request->from_id);
            $from->shop_items()->sync($request->item_array);
        }
        catch(Exception){
            return response()->json(0);
        }
        $item_array = $request->item_array;
        // dd($item_array);
        for($i=0;$i<count($request->item_array);$i++){
            $counting_u= ShopItem::find($item_array[$i])->shop_counting_units;
            dd($counting_u);
            foreach($counting_u as $key=>$count){
                $stock = ShopStockcount::updateOrCreate([
                    'stock_qty' => $count->current_quantity,
                    'shop_counting_unit_id'=> $count->id,
                    'from_id'=> $request->from_id,
                ]);
            }
        }

        return response()->json(1);
    }

    protected function shopSubCategory(request $request){

	    $category_id = $request->category_id;

	    $subcategory = ShopSubCategory::where('shop_category_id', $category_id)->get();
        // dd($subcategory);
	    return response()->json($subcategory);
    }
    protected function shopBrand(request $request){
        //    dd($request->all());
	    $category_id = $request->category_id;
        $sub_category_id = $request->sub_category_id;
	    $brand = ShopBrand::where('shop_category_id', $category_id)->where('shop_sub_category_id', $sub_category_id)->get();

	    return response()->json($brand);
    }
    protected function shopType(request $request){
        // dd($request->all());
	    $category_id = $request->category_id;
        $sub_category_id = $request->sub_category_id;
        $brand_id = $request->brand_id;
	    $type = ShopType::where('shop_category_id', $category_id)->where('shop_sub_category_id', $sub_category_id)->where('shop_brand_id', $brand_id)->get();

	    return response()->json($type);
    }

    //end shop
    protected function category_list()
    {
        $category_lists =  Category::whereNull("deleted_at")->get();

		return view('Inventory.category_list', compact('category_lists'));

    }
    protected function store_category(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'category_code' => 'required|max:2',
            'category_name' => 'required',
        ]);
        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $excode = Category::where('category_code',$request->category_code)->get();
        foreach($excode as $ecode){
            if($request->category_code == $ecode->category_code){

                alert()->error('This Category code has been used');
                return redirect()->back();
            }
        }
        $user_code = $request->session()->get('user')->name;
        try {

            $category = Category::create([
                'category_name' => $request->category_name,
                'created_by' => $user_code,
            ]);

            $category->category_code = sprintf('%s',$request->category_code);

            $category->save();

        } catch (\Exception $e) {

            alert()->error('Something Wrong! When Creating Category.');

            return redirect()->back();
        }
        alert()->success('Successfully Added');

        return redirect()->route('show_category_lists');
    }
    protected function updateCategory(Request $request,$id)
    {
        // dd($request->all());
        try {

        	$category = Category::findOrFail($id);

   		} catch (\Exception $e) {

        	alert()->error("Category Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

        $category->category_code = $request->category_code;

        $category->category_name = $request->category_name;

        $category->save();

        alert()->success('Successfully Updated!');

        return redirect()->route('show_category_lists');
    }
    protected function deleteCategory(Request $request)
    {
        $id = $request->category_id;

        $category = Category::find($id);

        // $items = Item::where('category_id', $id)->get();

        // foreach ($items as $item) {

        //     foreach ($item->counting_units as $unit) {

        //         $unit->delete();
        //     }
        // }

        // $items = Item::where('category_id', $id)->delete();

        $category->delete();

        return response()->json($category);
    }
    protected function sub_category_list()
    {
        $categories = Category::all();

		$sub_categories = SubCategory::all();

		return view('Inventory.sub_category_list', compact('categories','sub_categories'));

    }
    protected function storeSubCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sub_category_code' => 'required|max:3|min:3',
            'sub_category_name' => 'required',
            'category' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $exsub = SubCategory::where('subcategory_code',$request->sub_category_code)->get();
        foreach($exsub as $code){
            if($request->sub_category_code == $code->subcategory_code)

           {

                alert()->error('Subcategory code has been used');
                return redirect()->back();
            }
        }
            $sub_category = SubCategory::create([
                'name' => $request->sub_category_name,
                'category_id' => $request->category,
            ]);

            $sub_category->subcategory_code = sprintf('%03s',$request->sub_category_code);

            $sub_category->save();

    	alert()->success('Successfully Added');

        return redirect()->route('show_sub_category_lists');
    }
    protected function updateSubCategory(Request $request,$id)
    {
        try {

        	$sub_category = SubCategory::findOrFail($id);

   		} catch (\Exception $e) {

        	alert()->error("Category Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

        $sub_category->subcategory_code = $request->sub_category_code;

        $sub_category->name = $request->sub_category_name;

        $sub_category->category_id = $request->category;
        $sub_category->save();

        alert()->success('Successfully Updated!');

        return redirect()->route('show_sub_category_lists');
    }
    protected function deleteSubCategory(Request $request)
	{
		$id = $request->sub_category_id;

        $subcategory = SubCategory::find($id);

        // $items = Item::where('sub_category_id', $id)->get();

        // foreach ($items as $item) {

        //     foreach ($item->counting_units as $unit) {

        //         $unit->delete();
        //     }
        // }

        // $items = Item::where('sub_category_id', $id)->delete();

        $subcategory->delete();

        return response()->json($subcategory);
	}
    protected function brand_list()
    {
        $brands= Brand::all();
		$categories = Category::all();
		$sub_categories = SubCategory::all();
		// dd($sub_categories);
		return view('Inventory.brand_list', compact('sub_categories','brands','categories'));
    }
    protected function storeBrand(request $request){

	    $validator = Validator::make($request->all(), [
            'brand_code' => 'required|max:2|min:2',
            'brand_name' => 'required',
            'subcategory' => 'required',
        ]);

        // dd($request->all());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $brand_codee= sprintf('%s',$request->brand_code);
            $brand = Brand::firstOrCreate([
                'name' => $request->brand_name,
                'brand_code' => $brand_codee,
            ]);

            $brand->subcategories()->attach($request->subcategory);


    	alert()->success('Successfully Added');

        return redirect()->route('show_brand_lists');
	}
    protected function deletebrand(Request $request)
	{
		$id = $request->brand_id;

        $brand = Brand::find($id);

        // $items = Item::where('brand_id', $id)->get();

        // foreach ($items as $item) {

        //     foreach ($item->counting_units as $unit) {

        //         $unit->delete();
        //     }
        // }

        // $items = Item::where('brand_id', $id)->delete();

        $brand->delete();

        return response()->json($brand);
	}
    protected function updateBrand($id, Request $request)
	{
		try {

        	$brand = Brand::findOrFail($id);

   		} catch (\Exception $e) {

        	alert()->error("Brand Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

        $brand->brand_code = $request->brand_code;

        $brand->name = $request->brand_name;

        $brand->save();

        alert()->success('Successfully Updated!');

        return redirect()->route('show_brand_lists');
	}
    protected function showSubCategory(request $request){

	    $category_id = $request->category_id;

	    $subcategory = SubCategory::where('category_id', $category_id)->get();

	    return response()->json($subcategory);
    }
    protected function type_list()
    {
        $categories=Category::all();

        // $brands= Brand::all();

        // $sub_categories = SubCategory::all();
        $types= Type::all();

		return view('Inventory.type_list', compact('types','categories'));

    }
    protected function storeType(request $request){

	    $validator = Validator::make($request->all(), [
            'subcategory' => 'required',
            'brand' => 'required',
            'type_code' => 'required|max:3|min:3',
            'type_name' => 'required',
        ]);

        // dd($request->all());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $type_codee= sprintf('%03s',$request->type_code);
            $type = Type::create([
                'name' => $request->type_name,
                'type_code' => $type_codee,
                'brand_id'=>$request->brand,
                'sub_category_id'=>$request->subcategory,
            ]);



    	alert()->success('Successfully Added');

        return redirect()->route('show_type_lists');
	}

    protected function deletetype(Request $request)
	{
		$id = $request->type_id;

        $type = Type::find($id);

        // $items = Item::where('type_id', $id)->get();

        // foreach ($items as $item) {

        //     foreach ($item->counting_units as $unit) {

        //         $unit->delete();
        //     }
        // }

        // $items = Item::where('type_id', $id)->delete();

        $type->delete();

        return response()->json($type);
	}
    protected function updateType($id, Request $request)
	{
		try {

        	$type = Type::findOrFail($id);

   		} catch (\Exception $e) {

        	alert()->error("Type Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

        $type->type_code = $request->type_code;

        $type->name = $request->type_name;

        $type->save();

        alert()->success('Successfully Updated!');

        return redirect()->route('show_type_lists');
	}
    protected function showBrand(request $request){

        $subcategory_id = $request->subcategory_id;
        $brands= SubCategory::find($subcategory_id)->brands;

	    return response()->json($brands);
    }

    protected function item_list()
    {


        $item_lists =  Item::whereNull("deleted_at")->orderBy('category_id', 'ASC')->get();

        $units=CountingUnit::whereNull("deleted_at")->orderBy('id', 'ASC')->get();

		$all_categories = Category::where('deleted_at',null)->get();

		$sub_categories = SubCategory::all();

		return view('Inventory.item_list', compact('units','item_lists','sub_categories','all_categories'));

    }
    protected function storeItem(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'item_code' => 'required',
            'item_name' => 'required',
            'category_id' => 'required',
            'subcategory_id' => 'required',
        ]);

        if ($validator->fails()) {

        	alert()->error('Validation Error!');

            return redirect()->back();
        }

        $user_code = $request->session()->get('user')->name;

        if ($request->hasfile('photo_path')) {

			$image = $request->file('photo_path');

			$name = $image->getClientOriginalName();

			$photo_path =  time()."-".$name;

			$image->move(public_path() . '/photo/Item', $photo_path);
		}
		else{

			$photo_path = "default.jpg";

		}

        // try {

            $item = Item::create([
                'item_code' => $request->item_code,
                'item_name' => $request->item_name,
                'created_by' => $user_code,
                'photo_path' => $photo_path,
                'category_id' => $request->category_id,
                'sub_category_id' =>$request->subcategory_id,
                'brand_id'=>0,
                'type_id'=>0,
                'model'=>0,
            ]);
        alert()->success('Successfully Added');

        return redirect()->route('show_item_lists');
	}
    protected function showType(request $request)
    {
        $brand_id = $request->brand_id;
        $subcategory_id= $request->subcategory_id;
        $types = Type::where('brand_id',$brand_id)->where('sub_category_id',$subcategory_id)->get();
	     return response()->json($types);
	}
    protected function showSubCategoryFrom(request $request)
    {
	    $from_id=$request->from_id;
	    $category_id = $request->category_id;
        $items =Category::where('id',$category_id)->get();
        // dd($items);
        $subcategories=[];
        foreach($items as $item){
            // dd($item->sub_category);
            $subcategory_count=count($subcategories);
            // dd($subcategory_count);
            if($subcategory_count ==0){
                array_push($subcategories,$item->sub_category);

            }
            else{

                $arr_ki=[];
                for($i=0;$i<$subcategory_count;$i++)
            {
             array_push($arr_ki,$subcategories[$i]->id);
            }

            if (!in_array($item->sub_category->id,$arr_ki)) {
                array_push($subcategories,$item->sub_category);
            }
        }
        }
        // dd($subcategories);
	    return response()->json($subcategories);
    }

    protected function getUnitList(Request $request,$item_id)
    {

        try {

        	$units = Item::findOrFail($item_id)->counting_units;
            $item = Item::findOrFail($item_id);
            $items= Item::whereNull('deleted_at')->get();
   		} catch (\Exception $e) {

        	alert()->error("Item Not Found!")->persistent("Close!");

            return redirect()->back();
    	}

    	return view('Inventory.unit_list', compact('units','item','items'));
	}
    protected function storeUnit(Request $request)
    {
		$validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error('Something Wrong! When Creating Counting Unit.');

            return redirect()->back();

        }

        try {

            $counting_unit = CountingUnit::create([
                'unit_name' => $request->name,
                'reorder_quantity' => 0,
                'normal_sale_price' => 0,
                'stock_qty' => 0,
                'purchase_price' => 0,
                'item_id' => $request->item_id,
            ]);

        } catch (\Exception $e) {

            alert()->error('Something Wrong! When Creating Counting Unit.');

            return redirect()->back();
        }

        alert()->success('Successfully Stored!');

        return redirect()->back();
	}

    protected function updateUnitCode($id, Request $request)
    {
            // $unitCode = $request->code;
            // dd($unitCode);
            $validator = Validator::make($request->all(), [
                'code' => 'required',

            ]);

            if ($validator->fails()) {

                alert()->error('Something Wrong!');

                return redirect()->back();
            }
        $exunitcode = CountingUnit::where('unit_code',$request->code)->get();
        foreach($exunitcode as $eucode){
            if($request->code == $eucode->unit_code){

                alert()->error('This Unit code has been used');
                return redirect()->back();
            }
        }
        try {

            $unit = CountingUnit::findOrFail($id);


        } catch (\Exception $e) {

            alert()->error("Counting Unit Not Found!")->persistent("Close!");

            return redirect()->back();

        }

            $unit->unit_code= $request->code;
        $unit->save();
        alert()->success('Successfully Stored!');

        return redirect()->back();
    }

    protected function updateOriginalCode($id, Request $request)
    {
        try {

            $unit = CountingUnit::findOrFail($id);

        } catch (\Exception $e) {

            alert()->error("Counting Unit Not Found!")->persistent("Close!");

            return redirect()->back();

        }

        $unit->original_code = $request->code;

        $unit->save();

        alert()->success('Successfully Stored!');

        return redirect()->back();
    }
    protected function updateUnit($id,Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'item_id' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error('Something Wrong! When Creating Counting Unit.');

            return redirect()->back();

        }

        try {

            $unit = CountingUnit::findOrFail($id);

        } catch (\Exception $e) {

            alert()->error("Counting Unit Not Found!")->persistent("Close!");

            return redirect()->back();

        }

        $unit->unit_name = $request->name;
        $unit->item_id= $request->item_id;
        $unit->save();

        alert()->success('Successfully Stored!');

        return redirect()->back();
    }

    protected function deleteUnit(Request $request)
    {
        $id = $request->unit_id;

        $unit = CountingUnit::find($id);

        $unit->delete();

        $unit_relation= UnitRelation::find($id);
        if(!empty($unit_relation)){
            $unit_relation->delete();
        }

        return response()->json($unit);
    }

    protected function unitRelationList($item_id)
    {

        $unit_relation = UnitRelation::where('item_id', $item_id)->get();

        $item = Item::find($item_id);

        $counting_units = CountingUnit::where('item_id', $item_id)->get();

        return view('Inventory.unit_relation', compact('unit_relation','item','counting_units'));

    }

    protected function storeUnitRelation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_unit' => 'required|numeric',
            'to_unit' => 'required|numeric',
            'qty' => 'required|numeric',
        ]);

        if($validator->fails()){

            alert()->error('အချက်အလက် များ မှားယွင်း နေပါသည်။');

            return redirect()->route('unit_relation_list', $request->item_id);
        }

        $unit_relation = UnitRelation::where('item_id', request('item_id'))
            ->where('from_unit', request('from_unit'))
            ->where('to_unit', request('to_unit'))
            ->first();

        $unit_relation_rev = UnitRelation::where('item_id', request('item_id'))
            ->where('from_unit', request('to_unit'))
            ->where('to_unit', request('from_unit'))
            ->first();

        if(!empty($unit_relation) || !empty($unit_relation_rev)){

            alert()->error('This Relation has already Exist!');

            return redirect()->route('unit_relation_list', $request->item_id);

        }else{

            try {

                $unit_relation = UnitRelation::create([
                        "item_id" => $request->item_id,
                        "from_unit" => $request->from_unit,
                        "to_unit" => $request->to_unit,
                        "quantity" => $request->qty,
                ]);

            } catch (\Exception $e) {

                alert()->error('Something Wrong! When Unit Realation.');

                return redirect()->back();
            }

            alert()->success('Successfully Added!');

            return redirect()->route('unit_relation_list', $request->item_id);
        }
    }

    protected function updateUnitRelation($id, Request $request)
    {
        try {

            $unitrelation = UnitRelation::findOrFail($id);
            $unitrelation->from_unit=$request->from_unit;
            $unitrelation->to_unit=$request->to_unit;
            $unitrelation->quantity= $request->qty;
            $unitrelation->save();
            alert()->success("Success");

            return redirect()->back();
        } catch (\Exception $e) {

            alert()->error("Relation Not Found!")->persistent("Close!");

            return redirect()->back();

        }
    }

    protected function deleteUnitRelation(Request $request)
    {
        dd("Hello");
    }

    protected function convertUnit($unit_id)
    {

        $unit = UnitRelation::find($unit_id);

        return view ('Inventory.unit_convert', compact('unit'));
    }

    protected function ajaxConvertResult(Request $request)
    {

        $unit_id = $request->unit_id;

        $from = $request->from;

        $to = $request->to;

        $qty = $request->qty;

        $unit = UnitRelation::find($unit_id);

        $result_qty_one = $qty * $unit->quantity; //2*50

        $from_unit_balance = $unit->to_unit_detail->stock_qty - $qty;

        $to_unit_balance = $unit->from_unit_detail->stock_qty + $result_qty_one;

        $to_unit = CountingUnit::find($request->to);

        $to_unit->stock_qty = $to_unit_balance;

        $to_unit->save();

        $from_unit = CountingUnit::find($request->from);

        $from_unit->stock_qty = $from_unit_balance;

        $from_unit->save();


        $unit_conversion_log = UnitConversion::create([
            "item_id" => $unit->item_id,
            "from_unit_id" => $request->from,
            "from_unit_quantity" => $from_unit_balance,
            "to_unit_id" => $request->to,
            "to_unit_quantity" => $to_unit_balance,
        ]);



        $to_unit_for_name = CountingUnit::find($to);
        $from_unit_for_name = CountingUnit::find($from);

        alert()->success("successfully converted!");
        return response()->json([
            'from_unit_quantity' => $from_unit_balance,
            'from_unit' => $from_unit_for_name->unit_name,
            'to_unit_quantity' => $to_unit_balance,
            'to_unit' => $to_unit_for_name->unit_name,
        ]);

    }

    protected function convertCountUnit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'unit_id' => 'required',
            'result_qty' => 'required',
            'result_unit' => 'required',
            'from_unit_id' => 'required',
            'from_unit_qty' => 'required',
            'to_unit_id' => 'required',
            'to_unit_qty' => 'required',
        ]);

        if($validator->fails()){

            alert()->error('အချက်အလက် များ မှားယွင်း နေပါသည်။');

            return redirect()->back();
        }

        $to_unit = CountingUnit::find($request->to_unit_id);

        $to_unit->current_quantity = $request->to_unit_qty;

        $to_unit->save();

        $from_unit = CountingUnit::find($request->from_unit_id);

        $from_unit->current_quantity = $request->from_unit_qty;

        $from_unit->save();

        $unit_conversion_log = UnitConversion::create([
            "item_id" => $request->item_id,
            "from_unit_id" => $request->from_unit_id,
            "from_unit_quantity" => $request->from_unit_qty,
            "to_unit_id" => $request->to_unit_id,
            "to_unit_quantity" => $request->to_unit_qty,
        ]);




        return redirect()->route('count_unit_list', ['item_id' => $request->item_id]);
    }

    protected function AjaxGetItem(Request $request){

        $shop_id = $request->shop_id;

       $items= From::find($shop_id)->items()->with('category')->with('sub_category')->with('counting_units')->with('counting_units.stockcount')->get();

        return response()->json($items);
    }


    protected function AjaxGetCountingUnit(Request $request){

        $item = $request->item;
        $shop_id = $request->shop_id;
        $counting_units = CountingUnit::where('item_id', $item)->with("stockcount")->whereHas('stockcount',function($query) use($shop_id){
            return $query->where('from_id',$shop_id);
        })->whereNull('deleted_at')->with('item.category')->get();

        return response()->json($counting_units);
    }
    protected function AjaxGetCountingUnitCount(Request $request){

        $item = $request->item;

        $counting_units = CountingUnit::where('item_id', $item)->whereNull('deleted_at')->with('item.category')->with('item.brand')->with('item.type')->with('item.sub_category')->get();

        $count= Item::find($item)->counting_units;
        $stockc=[];
        foreach($count as $c){
            array_push($stockc,$c->stockcount);
        }
        return response()->json([$counting_units,$stockc]);
    }
    public function ajaxitemdetail(Request $request)
    {
        try {
        $item= Item::find($request->item_id);

        } catch (\Exception $e) {

            return response()->json('failed');

        }
        return response()->json($item);
    }
    public function updateItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_code' => 'required',
            'item_name' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {

        	alert()->error('Validation Error!');

            return redirect()->back();
        }

        // try {

            $item = Item::findOrfail($request->item_id)->update([
                'item_code' => $request->item_code,
                'item_name' => $request->item_name,
                'category_id' => $request->category_id,
            ]);

        if ($request->hasfile('photo_path')) {

			$image = $request->file('photo_path');

			$name = $image->getClientOriginalName();

			$photo_path =  time()."-".$name;

			$image->move(public_path() . '/photo/Item', $photo_path);

            $item->photo_path= $photo_path;

            $item->save();
		}
        alert()->success('Successfully updated');

        return back();
	}

    function deleteItem(Request $request)
    {
        try {
            $item = Item::findOrfail($request->item_id);
        } catch (\Exception $e) {
            alert()->error('Something went wrong!');
            return back();
        }
        $item->delete();
        alert()->success('Successfully Deleted !');
        return back();
    }
    public function searchItem(Request $request)
    {
        $searchquery = $request->searchquery;
        $items = Item::where('item_name', 'LIKE', "%{$searchquery}%")->Orwhere('item_code', 'LIKE', "%{$searchquery}%")->get();
        return response()->json($items);

    }
    public function itemAssign(Request $request)
    {
        $from = session()->get('from');
        $item_lists =  Item::whereNull("deleted_at")->orderBy('category_id', 'ASC')->with('froms')->get();
        $shops= From::all();
		return view('Inventory.item_assign', compact('item_lists','shops'));
    }
    public function itemAssignajax(Request $request)
    {
        try{
            $from = From::find($request->from_id);
            $from->items()->sync($request->item_array);
        }
        catch(Exception){
            return response()->json(0);
        }
        $item_array = $request->item_array;
        for($i=0;$i<count($request->item_array);$i++){
            $counting_u= Item::find($item_array[$i])->counting_units;
            foreach($counting_u as $key=>$count){
                $stock = Stockcount::updateOrCreate([
                    'counting_unit_id'=> $count->id,
                    'from_id'=> $request->from_id,
                ]);
            }
        }

        return response()->json(1);
    }
    public function itemAssignShop(Request $request)
    {
        $item_lists =  Item::whereNull("deleted_at")->orderBy('category_id', 'ASC')->with('category')->with("sub_category")->with('froms')->get();
        $shops= From::all();
		return response()->json($item_lists) ;
    }
}
