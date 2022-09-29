<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{
    public function listTransaction(Request $request)
    {
        $transactions = $request->user()->transactions;

        if ($transactions->isEmpty()) {
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
                "data" => $transactions
            ]
        ]);
    }

    public function Transaction(Request $request)
    {

        try {
            $product = Product::findOrFail($request->product_id);

            if ($product->price > $request->price) {
                return response()->json([
                    [
                        "response_code" => 4009901,
                        "response_message" => "uang tidak cukup",
                    ], 400
                ]);
            }

            if ($request->quantity > $product->stock) {
                return response()->json([
                    [
                        "response_code" => 4009901,
                        "response_message" => "jumlah stock product tidak cukup",
                    ], 400
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                [
                    "response_code" => 2009900,
                    "response_message" => "product tidak ada",
                ], 404
            ]);
        }

        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            "quantity" => $request->quantity,
            "price" => $request->price,
            'total_price' => $request->quantity * $request->price,
            "product_id" => $product->id,
            'status' => "pending"
        ]);

        try {

            $client = new \GuzzleHttp\Client();
            $res = $client->request('POST', 'https://sandbox.saebo.id/api/v1/payments', [
                'verify' => false,
                'headers' => [
                    'Accept' => 'application/json',
                    'X-API-KEY' => '123ABC'
                ],
                'body' => json_encode(
                    [
                        'reference_id' => $transaction->id,
                        "amount" => $transaction->quantity,
                        "product" => $product->name
                    ]
                )
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                [
                    "response_code" => 5009901,
                    "response_message" => "Internal Server Error",
                ]
            ]);
        }

        $transaction->reference_number = rand(1, 99);
        $transaction->save();



        return response()->json([
            [
                "response_code" => 2009900,
                "response_message" => "Successful",
                "reference_number" => $transaction->reference_number
            ]
        ]);
    }

    public function show($transaction_id)
    {
        try {
            $transaction = Transaction::findOrfail($transaction_id);
        } catch (\Throwable $th) {
            return response()->json([
                [
                    "response_code" => 2009900,
                    "response_message" => "tidak ada data transaction",
                ], 404
            ]);
        }

        return response()->json([
            [
                "response_code" => 2009900,
                "response_message" => "Successful",
                "data" => $transaction
            ], 404
        ]);
    }

    public function updateTransaction(Request $request)
    {
        try {

            $client = new \GuzzleHttp\Client();
            $res = $client->request('POST', 'https://sandbox.saebo.id/api/v1/payments', [
                'verify' => false,
                'headers' => [
                    'Accept' => 'application/json',
                    'X-API-KEY' => '123ABC'
                ],
                'body' => json_encode(
                    [
                        'reference_id' => $request->reference_id,
                        "amount" => $request->amount,
                        "product" => $request->product
                    ]
                )
            ]);
            return response()->json($res->getBody(), 200);
        } catch (\Throwable $th) {
            return response()->json([
                [
                    "response_code" => 5009901,
                    "response_message" => "Internal Server Error",
                ]
            ]);
        }
    }
}
