<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Client;
use Stripe;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderApiController extends Controller
{
    public function checkout (Request $request)
    {
    //     try {
    //         DB::beginTransaction();

            $client = Client::where(["clerk_id" => $request->input("clientId")]);

            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            // $lineItems = [[
            //     'price' => 4242,
            //     'quantity' => 1,
            // ]];

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                // 'phone_number_collection' => [
                //     'enabled' => true,
                // ],
                'customer_email' => 'mekansa83@gmail.com',
                // 'line_items' => $lineItems,
                // 'mode' => 'subscription',
                // 'subscription_data' => [
                //     'trial_from_plan' => true,
                // ],
                'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}",
                'cancel_url' => route('checkout.cancel', [], true),
            ]);

            // $client->createOrGetStripeCustomer();

    //         $payment = $client->charge(
    //             $request->input('amount'),
    //             $request->input('payment_method'),
    //         );

    //         $payment = $payment->asStripePaymentIntent();

    //         $order = $client->orders()->create([
    //             'transaction_id' => $payment->charges->data[0]->id,
    //             'total_amount' => $payment->charges->data[0]->amount,

    //         ]);

    //         foreach (json_decode($request->input('cart')) as $item) {
    //             $order->products()->attach($item['id'], ['amount' => $item['quantity']]);
    //         }

    //         $order->load('products');

    //         return $order;

    //         DB::commit();

    //     } catch (Exception $exception) {
    //         DB::rollBack();
    //         abort(500, $exception);
    //         return response()->json(['message' => $exception->getMessage()], 500);
    //     }
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

            $order = Order::where('session_id', $session->id)->first();
            if (!$order) {
                // $user = auth()->user();
                // $user->plan_id = null;
                // $user->save();

                throw new NotFoundHttpException();
            }
            if ($order->status === 'unpaid') {
                $order->status = 'paid';
                $order->save();
            }

            // $payment = new Payment();
            // $payment->order_id = $order->id;
            // $payment->st_cus_id = $session->customer;
            // $payment->st_sub_id = $session->subscription;
            // $payment->st_payment_intent_id = $session->payment_intent;
            // $payment->st_payment_method = $session->payment_method_types[0];
            // $payment->st_payment_status = $session->payment_status;
            // $payment->date = $session->created;
            // $payment->save();

            // return redirect()->away('http://localhost:3000/plans/payment/success');
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    public function cancel()
    {
        return redirect()->away('http://localhost:3000/payment/cancellation');
    }
}
