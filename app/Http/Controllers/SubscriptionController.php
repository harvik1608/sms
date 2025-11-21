<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Subscription;
use Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        return view('subscription.list');
    }

    public function load(Request $request)
    {
        try {
            $draw = intval($request->get('draw', 0));
            $start = intval($request->get('start', 0));
            $length = intval($request->get('length', 10));
            $searchValue = $request->input('search.value', '');

            $query = Subscription::with('customer');
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('name', 'like', "%{$searchValue}%");
                    $q->where('amount', 'like', "%{$searchValue}%");
                    $q->where('duration', 'like', "%{$searchValue}%");
                });
            }
            $recordsTotal = Subscription::count();
            $recordsFiltered = $query->count();
            $rows = $query->offset($start)->limit($length)->orderBy('id', 'desc')->get();

            $formattedData = [];
            foreach ($rows as $index => $row) {
                $file = "-";
                if($row->file != "" && file_exists(public_path('uploads/download/'.$row->file))) {
                    $file = asset('uploads/download/'.$row->file); 
                }
                $actions = '<div class="edit-delete-action">';
                    $actions .= '<a href="' . url('plans/'.$row->id.'/edit/') . '" class="me-2 edit-icon p-2 text-success" title="View"><i class="fa fa-eye"></i></a>';
                $actions .= '</div>';
                $formattedData[] = [
                    'id' => $start + $index + 1,
                    'customer_name' => $row->customer?->name."&nbsp;<small>(".$row->customer?->phone.")</small>",
                    'plan_name' => $row->plan?->name,
                    'amount' => $row->amount,
                    'paid_date' => $row->paid_date,
                    'payment_status' => $row->payment_status
                        ? '<span class="badge badge-success badge-xs d-inline-flex align-items-center">Paid</span>'
                        : '<span class="badge badge-danger badge-xs d-inline-flex align-items-center">PENDING</span>',
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

    public function subscriptions()
    {
        return view('subscription.my_list');
    }

    public function my_subscription(Request $request)
    {
        try {
            $draw = intval($request->get('draw', 0));
            $start = intval($request->get('start', 0));
            $length = intval($request->get('length', 10));
            $searchValue = $request->input('search.value', '');

            $query = Subscription::query();
            $query->where("user_id",Auth::user()->id);
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('amount', 'like', "%{$searchValue}%");
                });
            }
            $recordsTotal = Subscription::count();
            $recordsFiltered = $query->count();
            $rows = $query->offset($start)->limit($length)->orderBy('id', 'desc')->get();

            $formattedData = [];
            foreach ($rows as $index => $row) {
                $file = "-";
                if($row->file != "" && file_exists(public_path('uploads/download/'.$row->file))) {
                    $file = asset('uploads/download/'.$row->file); 
                }
                $actions = '<div class="edit-delete-action">';
                    $actions .= '<a href="' . url('plans/'.$row->id.'/edit/') . '" class="me-2 edit-icon p-2 text-success" title="View"><i class="fa fa-eye"></i></a>';
                $actions .= '</div>';
                $formattedData[] = [
                    'id' => $start + $index + 1,
                    'customer_name' => $row->customer?->name."&nbsp;<small>(".$row->customer?->phone.")</small>",
                    'plan_name' => $row->plan?->name,
                    'amount' => currency()." ".$row->amount,
                    'whatsapp' => $row->whatsapp,
                    'paid_date' => format_date($row->paid_date),
                    'payment_status' => $row->payment_status
                        ? '<span class="badge badge-success badge-xs d-inline-flex align-items-center">Paid</span>'
                        : '<span class="badge badge-danger badge-xs d-inline-flex align-items-center">PENDING</span>',
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
}
