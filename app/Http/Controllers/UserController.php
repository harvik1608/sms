<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Razorpay\Api\Api;

class UserController extends Controller
{
    public function index()
    {
        return view('user.list');
    }

    public function load(Request $request)
    {
        try {
            $draw = intval($request->get('draw', 0));
            $start = intval($request->get('start', 0));
            $length = intval($request->get('length', 10));
            $searchValue = $request->input('search.value', '');

            $query = User::query();
            $query->where("role",2);
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('name', 'like', "%{$searchValue}%");
                    $q->where('email', 'like', "%{$searchValue}%");
                });
            }
            $recordsTotal = User::count();
            $recordsFiltered = $query->count();
            $rows = $query->offset($start)->limit($length)->orderBy('id', 'desc')->get();

            $formattedData = [];
            foreach ($rows as $index => $row) {
                $file = "-";
                if($row->file != "" && file_exists(public_path('uploads/download/'.$row->file))) {
                    $file = asset('uploads/download/'.$row->file); 
                }
                $actions = '<div class="edit-delete-action">';
                    $actions .= '<a href="' . url('users/'.$row->id.'/edit/') . '" class="me-2 edit-icon p-2 text-success" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </a>';
                    $actions .= '<a href="javascript:;" onclick="remove_row(\'' . url('users/' . $row->id) . '\')" data-bs-toggle="modal" data-bs-target="#delete-modal" class="p-2" title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </a>';
                $actions .= '</div>';
                $formattedData[] = [
                    'id' => $start + $index + 1,
                    'name' => $row->name,
                    'email' => $row->email,
                    'phone' => $row->phone,
                    'city' => $row->city,
                    'is_approved' => $row->is_approved
                        ? '<span class="badge badge-success badge-xs d-inline-flex align-items-center">Approved</span>'
                        : '<span class="badge badge-danger badge-xs d-inline-flex align-items-center">Not Approved</span>',
                    'status' => $row->is_active
                        ? '<span class="badge badge-success badge-xs d-inline-flex align-items-center">Active</span>'
                        : '<span class="badge badge-danger badge-xs d-inline-flex align-items-center">Inactive</span>',
                    'actions' => $actions
                ];
            }
            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $formattedData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function create()
    {
        $user = null;
        $plans = Plan::select("id","name","duration","whatsapp")->where("is_active",1)->get();

        // $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        // $orderData = [
        //     'receipt'         => 'order_'.time(),
        //     'amount'          => 500 * 100, // Rs 500
        //     'currency'        => 'INR'
        // ];
        // $order = $api->order->create($orderData);
        return view('user.add_edit',compact('user','plans'));
    }

    public function store(Request $request)
    {
        try {
            $post = $request->all();

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
            $row->is_approved = 1;
            $row->is_active = 1;
            $row->created_at = date("Y-m-d H:i:s");
            $row->save();

            $plan = Plan::select("id","name","amount","duration","whatsapp")->where("is_active",1)->where("id",$post["plan_id"])->first();

            $subscription = new Subscription;
            $subscription->user_id = $row->id;
            $subscription->plan_id = $post["plan_id"];
            $subscription->duration = $plan->duration;
            $subscription->amount = $plan->amount;
            $subscription->whatsapp = $plan->whatsapp;
            $subscription->payment_status = 0;
            $subscription->is_active = 1;
            $subscription->created_at = date("Y-m-d H:i:s");
            $subscription->save();

            return response()->json(['success' => true,'message' => "User added successfully."], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false,'message' => $e->getMessage()], 200);
        }
    }

    public function edit($id)
    {
        $user = User::find($id);
        if(!$user) {
            return redirect()->route("admin.dashboard");
        }
        return view('user.add_edit',compact('user'));   
    }

    public function update(Request $request,$id)
    {
        try {
            $post = $request->all();

            $row = User::find($id);
            $row->name = trim($post['name']);
            $row->email = trim($post['email']);
            $row->phone = trim($post['mobile_no']);
            $row->state = trim($post['state']);
            $row->city = trim($post['city']);
            if(trim($post['password']) != "") {
                $row->password = Hash::make(trim($post['password']));
            }   
            $row->is_approved = $post['is_approved'];
            $row->is_active = $post['is_active'];
            $row->updated_at = date("Y-m-d H:i:s");
            $row->save();

            return response()->json(['success' => true,'message' => "User edited successfully."], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false,'message' => $e->getMessage()], 200);
        }
    }

    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(['success' => true,'message' => "User removed successfully."], 200);
    }
}
