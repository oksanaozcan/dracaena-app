<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class CustomerApiController extends Controller
{
    public function getCustomer(Request $request): JsonResponse
    {
        $token = $request->bearerToken();
        if ($token) {
            $customer = Auth::guard('api')->user();
        } else {
            return response()->json(['authenticated' => false], 401);
        }

        return response()->json(['customer' => $customer], 200);
    }

    public function updatePersonalDetails(Request $request)
    {
        $token = $request->bearerToken();

        if ($token) {
            $customer = Auth::guard('api')->user();
            if ($customer) {
                try {
                    DB::beginTransaction();

                    $validator = Validator::make($request->all(), [
                        'birthday' => 'nullable',
                        'newsletter_confirmed' => 'nullable',
                    ]);

                    if ($validator->fails()) {
                        return response()->json(['error' => $validator->errors()], 422);
                    }

                    $customer->update([
                        'birthday' => $request->birthday,
                        'newsletter_confirmed' => $request->newsletter_confirmed,
                    ]);

                    DB::commit();

                } catch (Exception $exception) {
                    DB::rollBack();
                    abort(500, $exception);
                }
            } else {
                return response()->json(['message' => 'Customer not found'], 404);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }

        return response()->json(['message' => 'Personal details updated successfully'], 200);
    }

    public function updateShippingAddress(Request $request)
    {
        $token = $request->bearerToken();

        if ($token) {
            $customer = Auth::guard('api')->user();
            if ($customer) {
                try {
                    DB::beginTransaction();

                    $validator = Validator::make($request->all(), [
                        'address_line' => 'required|max:200',
                        'city' => 'nullable|string|max:100',
                        'state' => 'nullable|string|max:100',
                        'postal_code' => 'nullable|string|max:20',
                        'country' => 'nullable|string|max:100',
                        'specified_in_order' => 'required|boolean',
                    ]);

                    if ($validator->fails()) {
                        return response()->json(['error' => $validator->errors()], 422);
                    }

                    $specifiedInOrder = $request->specified_in_order;

                    if ($specifiedInOrder == false) {
                        $shippingAddress = $customer->shippingAddress()->first();

                        if ($shippingAddress) {
                            $shippingAddress->update([
                                'address_line' => $request->address_line,
                                'city' => $request->city,
                                'state' => $request->state,
                                'postal_code' => $request->postal_code,
                                'country' => $request->country,
                            ]);
                        } else {
                            $customer->addresses()->create([
                                'address_line' => $request->address_line,
                                'city' => $request->city,
                                'state' => $request->state,
                                'postal_code' => $request->postal_code,
                                'country' => $request->country,
                                'type' => 'shipping',
                                'specified_in_order' => false,
                            ]);
                        }
                    } else {
                        //TODO: when i will implement orders for customer model
                        $customer->addresses()->create([
                            'address_line' => $request->address_line,
                            'city' => $request->city,
                            'state' => $request->state,
                            'postal_code' => $request->postal_code,
                            'country' => $request->country,
                            'type' => 'shipping',
                            'specified_in_order' => true,
                        ]);
                    }

                    DB::commit();

                    return response()->json(['message' => 'Shipping address updated successfully'], 200);

                } catch (Exception $exception) {
                    DB::rollBack();
                    return response()->json(['error' => $exception->getMessage()], 500);
                }
            } else {
                return response()->json(['message' => 'Customer not found'], 404);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }

    public function updateBillingAddress(Request $request)
    {
        $token = $request->bearerToken();

        if ($token) {
            $customer = Auth::guard('api')->user();
            if ($customer) {
                try {
                    DB::beginTransaction();

                    $validator = Validator::make($request->all(), [
                        'address_line' => 'required|max:200',
                        'city' => 'nullable|string|max:100',
                        'state' => 'nullable|string|max:100',
                        'postal_code' => 'required|string|max:20',
                        'country' => 'required|string|max:100',
                        'specified_in_order' => 'required|boolean',
                    ]);

                    if ($validator->fails()) {
                        return response()->json(['error' => $validator->errors()], 422);
                    }

                    $specifiedInOrder = $request->specified_in_order;

                    if ($specifiedInOrder == false) {
                        $billingAddress = $customer->billingAddress()->first();

                        if ($billingAddress) {
                            $billingAddress->update([
                                'address_line' => $request->address_line,
                                'city' => $request->city,
                                'state' => $request->state,
                                'postal_code' => $request->postal_code,
                                'country' => $request->country,
                            ]);
                        } else {
                            $customer->addresses()->create([
                                'address_line' => $request->address_line,
                                'city' => $request->city,
                                'state' => $request->state,
                                'postal_code' => $request->postal_code,
                                'country' => $request->country,
                                'type' => 'billing',
                                'specified_in_order' => false,
                            ]);
                        }
                    } else {
                        //TODO: when i will implement orders for customer model
                        $customer->addresses()->create([
                            'address_line' => $request->address_line,
                            'city' => $request->city,
                            'state' => $request->state,
                            'postal_code' => $request->postal_code,
                            'country' => $request->country,
                            'type' => 'billing',
                            'specified_in_order' => true,
                        ]);
                    }

                    DB::commit();

                    return response()->json(['message' => 'Billing address updated successfully'], 200);

                } catch (Exception $exception) {
                    DB::rollBack();
                    return response()->json(['error' => $exception->getMessage()], 500);
                }
            } else {
                return response()->json(['message' => 'Customer not found'], 404);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }

}
