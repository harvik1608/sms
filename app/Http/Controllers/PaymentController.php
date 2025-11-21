<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Razorpay\Api\Api;
use App\Models\Plan;
use App\Models\User;
use App\Models\Subscription;

class PaymentController extends Controller
{
    public function createOrder(Request $request)
    {
        $plan = Plan::findOrFail($request->plan_id);
        $amount = $plan->amount;

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $orderData = [
            'receipt' => 'order_' . uniqid(),
            'amount'  => $amount * 100,
            'currency' => 'INR'
        ];
        $order = $api->order->create($orderData);

        return response()->json(['order_id' => $order['id'],'amount' => $amount,'key' => env('RAZORPAY_KEY'),'plan_id' => $plan->id]);
    }

    public function verify(Request $request)
    {
        $post = $request->all();

        $signatureStatus = false;
        if ($request->razorpay_order_id && $request->razorpay_payment_id) {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

            try {
                $attributes = [
                    'razorpay_order_id'   => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature'  => $request->razorpay_signature
                ];

                $api->utility->verifyPaymentSignature($attributes);
                $signatureStatus = true;

            } catch (\Exception $e) {
                $signatureStatus = false;
            }
        }
        if($signatureStatus == true) {
            $row = new User;
            $row->name = trim($post['name']);
            $row->email = trim($post['email']);
            $row->phone = trim($post['mobile_no']);
            $row->contact_person_name = trim($post['contact_person_name']);
            $row->company_name = trim($post['company_name']);
            $row->username = trim($post['username']);
            $row->service_name = trim($post['service_name']);
            $row->industry = trim($post['industry']);
            $row->zipcode = trim($post['zipcode']);
            $row->state = trim($post['state']);
            $row->city = trim($post['city']);
            $row->address = trim($post['address']);
            $row->password = Hash::make(trim($post['password']));
            $row->is_approved = 0;
            $row->is_active = 1;
            $row->created_at = date("Y-m-d H:i:s");
            $row->save();

            $plan = Plan::select("id","name","amount","duration","whatsapp","is_multiple_file_allow")->where("is_active",1)->where("id",$post["plan_id"])->first();

            $subscription = new Subscription;
            $subscription->user_id = $row->id;
            $subscription->plan_id = $post["plan_id"];
            $subscription->duration = $plan->duration;
            $subscription->amount = $plan->amount;
            $subscription->whatsapp = $plan->whatsapp;
            $subscription->is_multiple_file_allow = $plan->is_multiple_file_allow;
            $subscription->payment_status = 1;
            $subscription->paid_date = date("Y-m-d");
            $subscription->is_active = 1;
            $subscription->created_at = date("Y-m-d H:i:s");
            $subscription->save();
        }
        return response()->json(["status" => $signatureStatus ? "success" : "failed"]);
        // return $signatureStatus ? "Payment Successful!" : "Payment Verification Failed!";
    }
}