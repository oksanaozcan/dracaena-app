<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Stripe;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Resolvers\PaymentPlatformResolver;
use Debugbar;
use App\Services\StripeService;
use Illuminate\Support\Facades\Auth;
use App\Events\ProductOutOfStock;

class OrderApiController extends Controller
{
    protected $paymentPlatformResolver;
    protected $paymentService;

    public function __construct(PaymentPlatformResolver $paymentPlatformResolver)
    {
        $this->paymentPlatformResolver = $paymentPlatformResolver;
    }

    public function checkout(Request $request)
    {
        $token = $request->bearerToken();

        if ($token) {
            $customer = Auth::guard('api')->user();
            if ($customer) {
                $products = Product::whereIn('id', $request->input("productIds"))->get();

                $lineItems = [];
                $total_price = 0;
                foreach ($products as $pr) {
                    $total_price += $pr->price;
                    $lineItems[] = [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $pr->title,
                            ],
                            'unit_amount' => $pr->price * 100,
                        ],
                        'quantity' => 1,
                    ];
                }

                $this->paymentService = $this->paymentPlatformResolver->resolveService($request->payment_platform);

                session()->put('paymentPlatformId', $request->payment_platform);

                if ($this->paymentService) {
                    $session = $this->paymentService->createCheckoutSession(
                        $lineItems,
                        route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}",
                        route('checkout.cancel', [], true)
                    );

                    // Save session id to the order
                    $order = new Order();
                    $order->customer_id = $customer->id;
                    $order->session_id = $session->id;
                    $order->payment_status = 0;
                    $order->total_amount = $total_price;
                    $order->payment_method = 'credit card';
                    $order->save();

                    // Attach products to order
                    foreach ($request->input('productIds') as $pr) {
                        $order->products()->attach($pr);
                    }

                    return response()->json([
                        'url' => $session->url,
                    ]);
                }
            } else {
                return response()->json(['message' => 'Customer not found'], 404);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }

        return response()->json(['error' => 'Failed to create checkout session'], 500);
    }


    public function success(Request $request)
    {

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $sessionId = $request->get('session_id');

        try {

            $session = $stripe->checkout->sessions->retrieve($sessionId);

            if (!$session) {
                throw new NotFoundHttpException();
            }

            $order = Order::where('session_id', $sessionId)->first();

            if (!$order) {
                throw new NotFoundHttpException();
            }

            if ($order->payment_status == 0) {

                $sessionArray = $session->toArray();
                $customerName = $sessionArray['customer_details']['name'];
                $customerEmail = $sessionArray['customer_details']['email'];

                $order->customer_name = $customerName;
                $order->customer_email = $customerEmail;
                $order->payment_status = 1;
                $order->save();

                $orderProducts = OrderProduct::where('order_id', $order->id)->get();
                foreach ($orderProducts as $orderProduct) {
                    $prd= Product::find($orderProduct->product_id);
                    if ($prd->amount > 0) {
                        $prd->amount = $prd->amount - 1;
                        $prd->save();
                        // if ($prd->amount == 0) {
                        //     event(new ProductOutOfStock($prd));
                        // }
                    } else {
                        //TODO: logic if product amount == 0;
                    }

                    \App\Models\Cart::where('product_id', $orderProduct->product_id)
                        ->where('customer_id', $order->customer_id)
                        ->delete();
                }
            }

            return redirect()->away('http://localhost:3000/dashboard');

        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }

    }

    public function cancel(Request $request)
    {
        $order = Order::where('session_id', $sessionId)->first();

        $order->products()->detach();
        $order->delete();

        return redirect()->away('http://localhost:3000//dashboard');
    }
}
