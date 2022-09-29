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
    public function index(Request $request)
    {
        $products = Product::query();
        if ($products->get()->isEmpty()) {
            return response()->json([
                [
                    "response_code" => 2009900,
                    "response_message" => "list kosong",
                    "data" => []
                ]
            ]);
        }

        $products->when($request->query("cari"), function ($q) use ($request) {
            return $q->where('name', "LIKE", "%$request->name%");
        });

        return response()->json([
            [
                "response_code" => 2009900,
                "response_message" => "Successful",
                "data" => $products->paginate(10)
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductReqeust $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->update($request->all());
        } catch (\Throwable $th) {
            return response()->json([
                [
                    "response_code" => 4009901,
                    "response_message" => "Invalid Field Format",

                ]
            ]);
        }

        return response()->json([
            [
                "response_code" => 2009900,
                "response_message" => "Successful",
            ]
        ]);
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
