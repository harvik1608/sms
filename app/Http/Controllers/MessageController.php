<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Models\Message;
use App\Models\Contact;
use Auth;
use DB;

class MessageController extends Controller
{
    public function index()
    {
        $contacts = Contact::select("id","name")->where("is_active",1)->where("created_by",Auth::user()->id)->orderBy("name","asc")->get();
        return view('message.list',compact('contacts'));
    }

    public function load(Request $request)
    {
        try {
            $draw = intval($request->get('draw', 0));
            $start = intval($request->get('start', 0));
            $length = intval($request->get('length', 10));
            $searchValue = $request->input('search.value', '');

            $query = Message::query()->with('contact');
            $query->where("created_by",Auth::user()->id);
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('content', 'like', "%{$searchValue}%");
                });
            }
            $recordsTotal = Message::count();
            $recordsFiltered = $query->count();
            $rows = $query->offset($start)->limit($length)->orderBy('id', 'desc')->get();

            $formattedData = [];
            foreach ($rows as $index => $row) {
                $actions = '<div class="edit-delete-action">';
                    $actions .= '<a href="javascript:;" onclick="remove_row(\'' . url('messages/' . $row->id) . '\')" data-bs-toggle="modal" data-bs-target="#delete-modal" class="p-2" title="Delete">
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
                    'name' => $row->contact?->name,
                    'message_type' => $row->message_type == 1 ? "Email" : "Whatsapp",
                    'is_sent' => $row->is_sent
                        ? '<span class="badge badge-success badge-xs d-inline-flex align-items-center">Sent</span>'
                        : '<span class="badge badge-danger badge-xs d-inline-flex align-items-center">Not Sent</span>',
                    'sent_on' => \Carbon\Carbon::parse($row->created_at)->setTimezone('Asia/Kolkata')->format('d M, Y h:i A'),
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

    public function send(Request $request)
    {
        $post = $request->all();
        if(isset($post["send_to"]) && !empty($post["send_to"])) {
            $contacts = Contact::select('id','name',DB::raw("CONCAT('91', phone) as phone"),'email')->whereIn('id', $post['send_to'])->get();
            if(!$contacts->isEmpty()) {
                foreach($contacts as $contact) {
                    Mail::to($contact->email)->send(
                        new WelcomeMail(
                            subjectText: $post["subject"],
                            viewFile: 'email_templates.message',
                            data: ['content' => $post["content"]]
                        )
                    );
                    $row = new Message;
                    $row->contact_id = $contact->id;
                    $row->message_type = $post["message_type"];
                    $row->subject = $post["subject"];
                    $row->content = $post["content"];
                    $row->is_sent = 1;
                    $row->created_by = Auth::user()->id;
                    $row->created_at = date("Y-m-d H:i:s");
                    $row->save();
                }
            }
        }
        return redirect()->to("messages");
    }

    public function destroy($id)
    {
        Message::destroy($id);
        return response()->json(['success' => true,'message' => "Message removed successfully."], 200);
    }
}