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
        $id = $request->id;
        $shipperId = $request->shipper_id;
        $orderDayFrom = $request->order_day_from;
        $orderDayTo = $request->order_day_to;
        $receiveDayFrom = $request->receive_day_from;
        $receiveDayTo = $request->receive_day_to;

        $orders = Package::byId($id)->orderDayIn($orderDayFrom, $orderDayTo)
                    ->receiveDayIn($receiveDayFrom, $receiveDayTo)
                    ->byShipperId($shipperId)->with('products')->paginate(10);
        if (!$orders->isEmpty()) {
            $total = 0;
            foreach($orders as $order) {
                $total += $order->deliver_cost;
            }
            return response()->json(['orders' => $orders, 'total' => $total]);
        } else {
            return response()->json(['message' => 'Không có đơn hàng nào']);
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
            'deliver_id' => 'required',
            'deliver_name' => 'required',
            'deliver_phone' => 'required',
            'price' => 'required',
            'products' => 'required',
            'deliver_cost' => 'required'
        ];

        $input = $request->only( 'pick_name','pick_phone', 'pick_address', 'name', 'address', 'phone',
            'price', 'deliver_phone', 'deliver_name', 'deliver_id', 'products', 'deliver_cost'
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
                    $curProduct = Product::find($product['id']);
                    if (!$curProduct) {
                        throw new CustomException("Product with id {$product['id']} not found");
                    }
                    $order->products()->attach($curProduct, ['quantity' => $product['quantity']]);
                }
                return response()->json([
                    'orders' => $order
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
