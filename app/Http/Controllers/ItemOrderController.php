<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemOrderController extends Controller
{
    public function test(Request $request){
        
       $data = $request->validate([
            'name' => 'required']);

            dd($data);
    }


    public function store(Request $request)
{
    $data = $request->validate([
        'user_id' => 'required',
        'total_price' => 'required|numeric|min:0',
        'status' => 'required|string',
        'order_items' => 'required|array|min:1',
        'order_items.*.food_item_id' => 'required',
        'order_items.*.quantity' => 'required|integer|min:1',
        'order_items.*.price' => 'required|numeric|min:0'
    ]);

    dd($data);
}

}
