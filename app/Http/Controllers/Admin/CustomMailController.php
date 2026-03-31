<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomMail;
use App\Mail\CustomMails;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
class CustomMailController extends Controller
{
    use ResponseTrait;

    public function listCustomMail(){
        try {

            $query = CustomMail::latest();

            return DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('to', function ($row) {
                    return $row->to ?? '-';
                })
                ->filterColumn('to',function ($query,$keyword){
                    $query->where('to','LIKE',"%{$keyword}%");
                })
                ->addColumn('subject', function ($row) {
                    return $row->subject ?? '-';
                })

                ->addColumn('body', function ($row) {
                    return Str::limit(strip_tags($row->body), 100);
                })

                ->addColumn('sent_at', function ($row) {
                    return $row->sent_at
                        ? $row->sent_at->format('d M Y, h:i A')
                        : '-';
                })



                ->rawColumns(['action'])
                ->make(true);

        } catch (\Exception $exception) {
            return $this->sendDataTableError(ERROR_500, [], 500);
        }
    }
    public function index()
    {

        return view('admin.custom_mail.index');
    }

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'to' => 'required|email|max:255',
            'subject' => 'required|string',
            'body' => 'required',
        ],
            [
                'to.required' => 'Recipient email address is required.',
                'to.email' => 'Please enter a valid email address for the recipient.',
                'subject.required' => 'Please enter a subject for the email.',
                'body.required' => 'The message body cannot be empty.',
            ]
        );
        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $custom_mail = new CustomMail();
            $custom_mail->to = $request->to;
            $custom_mail->subject = $request->subject;
            $custom_mail->sent_at = Carbon::now();;
            $custom_mail->body = $request->body;
            $custom_mail->save();

            DB::commit();

            $mailData = [
                'subject' =>  $request->subject,
                'body' => $request->body
            ];

            Mail::to($request->to)->send(new CustomMails($mailData));
            return $this->sendSuccess(__('Mail send successfully'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }
}
