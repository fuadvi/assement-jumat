<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductReqeust;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            return response()->json([
                [
                    "response_code" => 2009900,
                    "response_message" => "Successful",
                    "data" => []
                ]
            ]);
        }

        return response()->json([
            [
                "response_code" => 2009900,
                "response_message" => "Successful",
                "data" => $products
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductReqeust $request)
    {
        $product = Product::create($request->all());

        return response()->json([
            [
                "response_code" => 2009900,
                "response_message" => "Successful",
                "data" => $product
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
