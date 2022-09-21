<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    public function index() {
        $shop_id = auth('sanctum')->user()->id;
        $orders = Package::where('shop_id', $shop_id)->with('products')->get();
        if (sizeOf($orders) > 0) {
            return response()->json(['orders' => $orders]);
        } else {
            return response()->json(['message' => 'Shop không có đơn hàng nào']);
        }
    }

    public function create(Request $request){
        $rules = [
            'buyer_id' => 'required',
            'pick_name'    => 'required',
            'pick_phone' => 'required',
            'pick_address' => 'required',
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'price' => 'required',
            'products' => 'required'
        ];

        $input = $request->only('buyer_id', 'pick_name','pick_phone', 'pick_address', 'name', 'address', 'phone',
            'price', 'products'
        );
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        try {
            $input['status'] = 0;
            $products = $request->products;
            DB::transaction(function() use($input, $products){
                $input['shop_id'] = auth('sanctum')->user()->id;
                $order = Package::create($input);
                foreach($products as $product) {
                    $cur_product = Product::find($product['id']);
                    $order->products()->attach($cur_product, ['quantity' => $product['quantity']]);
                }
            });
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in create order',
                'error' => $error,
            ]);
        }
    }
}
