<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        User::create($data);

        return response()->json(
            [
                "response_code" => 2009900,
                "response_message" => "Successful"
            ]
        );
    }
}
