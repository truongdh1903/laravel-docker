<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Product;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CustomException;
class PackageController extends Controller
{
    public function index(Request $request) {
        $shop_id = auth('sanctum')->user()->id;
        $id = $request->id;
        $order_day_from = $request->order_day_from;
        $order_day_to = $request->order_day_to;
        $receive_day_from = $request->receive_day_from;
        $receive_day_to = $request->receive_day_to;

        $orders = Package::where('shop_id', $shop_id)->byId($id)->orderDayIn($order_day_from, $order_day_to)
                    ->receiveDayIn($receive_day_from, $receive_day_to)->with('products')->get();
        if (!$orders->isEmpty()) {
            return response()->json(['orders' => $orders]);
        } else {
            return response()->json(['message' => 'Shop không có đơn hàng nào']);
        }
    }

    public function create(Request $request){
        $rules = [
            'pick_name'    => 'required',
            'pick_phone' => 'required',
            'pick_address' => 'required',
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'deliver_name' => 'required',
            'deliver_phone' => 'required',
            'price' => 'required',
            'products' => 'required'
        ];

        $input = $request->only( 'pick_name','pick_phone', 'pick_address', 'name', 'address', 'phone',
            'price', 'deliver_phone', 'deliver_name', 'products'
        );
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        try {
            $input['status'] = 0;
            $products = $request->products;
            $output = null;
            return DB::transaction(function() use($input, $products) {
                $input['shop_id'] = auth('sanctum')->user()->id;
                $input['order_day'] = date("Y-m-d H:i:s");
                $input['receive_day'] = date("Y-m-d", strtotime("+1 week"));
                $order = Package::create($input);
                foreach($products as $product) {
                    $cur_product = Product::find($product['id']);
                    if (!$cur_product) {
                        throw new CustomException("Product with id {$product['id']} not found");
                    }
                    $order->products()->attach($cur_product, ['quantity' => $product['quantity']]);
                }
                return response()->json([
                    'order' => $order
                ]);
            });
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in create order',
                'error' => $error->getMessage(),
            ]);
        }
    }
}
