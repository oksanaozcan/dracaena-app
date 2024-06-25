<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Address;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\TokenRepository;

class AuthApiController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:30',
            'email' => 'required|email|unique:customers',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password|min:8',
            'birthday' => 'nullable',
            'newsletter_confirmed' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'birthday' => $request->birthday,
            'newsletter_confirmed' => $request->newsletter_confirmed,
        ]);

        return response()->json(['customer' => $customer], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid credentials'], 422);
        }

        try {
            $customer = Customer::whereEmail($request->email)->first();

            if (!$customer || !Hash::check($request->password, $customer->password)) {
                $data = 'Invalid Login (email or password) Credentials';
                $code = 401;
            } else {
                $token = $customer->createToken('authToken')->accessToken;
                $code = 200;
                $data = [
                    'customer' => $customer,
                    'access_token' => $token,
                ];
            }
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage()];
        }
        return response()->json($data, $code);
    }

    public function authCheck(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if ($token) {
            try {
                $user = Auth::guard('api')->user();

                if ($user) {
                    $shippingAddress = $user->shippingAddress()->first();

                    return response()->json([
                        'authenticated' => true,
                        'user' => $user,
                        'shipping_address' => $shippingAddress ? $shippingAddress : null,
                ], 200);
                } else {
                    return response()->json(['authenticated' => false], 401);
                }
            } catch (\Exception $e) {
                return response()->json(['authenticated' => false, 'error' => $e->getMessage()], 401);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $tokenRepository = app(TokenRepository::class);
        $tokenId = $request->user()->token()->id;

        $tokenRepository->revokeAccessToken($tokenId);

        return response()->json([
            "status" => 200,
            "message" => "Customer logged out"
        ]);
    }
}
