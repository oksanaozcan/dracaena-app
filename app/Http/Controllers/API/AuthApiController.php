<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid credentials'], 422);
        }

        if (Auth::guard('api')->attempt($credentials)) {
            $customer = Auth::guard('api')->user();
            $accessToken = $customer->createToken('AuthToken')->accessToken;

            return response()->json(['customer' => $customer, 'access_token' => $accessToken]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:customers',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Auth::guard('api')->login($customer);

        $accessToken = $customer->createToken('AuthToken')->accessToken;
        return response()->json(['customer' => $customer, 'access_token' => $accessToken], 201);
    }
}
