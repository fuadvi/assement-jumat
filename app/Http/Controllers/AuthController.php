<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
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

    public function login(LoginRequest $request)
    {
        $user = User::where("email", $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(
                [
                    "response_code" => 4009901,
                    "response_message" => "Unauthorized"
                ],
                401
            );
        }

        $token = $user->createToken("token")->plainTextToken;

        return response()->json([
            [
                "response_code" => 2009900,
                "response_message" => "Successful",
                "data" => [
                    "token" => $token,
                    "user" => $user
                ]
            ]
        ]);
    }
}
