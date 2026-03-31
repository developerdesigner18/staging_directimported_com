<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplates;
use App\Http\Traits\ResponseTrait;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EmailTemplateController extends Controller
{    use ResponseTrait;

    public function index()
    {

        return view('admin.email.index');
    }
    public function listEmail()
    {


        try {
            $response = EmailTemplates::latest();

            return DataTables::eloquent($response)
                ->addIndexColumn()
                ->addColumn('email', function ($row) {
                    return $row->key ?? '-';
                })
                ->filterColumn('email', function ($query, $keyword) {
                    $query->where('key', 'LIKE', "%{$keyword}%");
                })
                ->addColumn('subject', function ($row) {
                    return $row->subject ?? '-';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : '-';
                })
                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at ? $row->updated_at->format('d M Y') : '-';
                })
                ->addColumn('action', function ($row) {


                    return '

        <ul class="list-inline mb-0 d-flex justify-content-center text-center">
           <li class="list-inline-item">
                            <a href="javascript:void(0)" class="text text-black btn btn-info btn-sm waves-effect waves-light material-shadow-none btn-view" data-id="'.$row->id.'">
                                    <i class="ri-eye-fill"></i> View
                            </a>
                        </li>
           <li class="list-inline-item">
                            <a href='.route('admin.email.edit',[$row->id]).' class="text text-black btn btn-success btn-sm waves-effect waves-light material-shadow-none">
                                <i class="ri-pencil-line"></i> Edit
                            </a>
                        </li>


        </ul>';
                })
                ->rawColumns(['action'])
                ->make(true);

        } catch (\Exception $exception) {
            return $this->sendDataTableError(ERROR_500, [], 500);
        }
    }
    public function editEmail(Request $request)
    {
        $data=EmailTemplates::find($request->id);
        return view('admin.email.edit',compact('data'));
    }

        public function updateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'subject_name' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $email = EmailTemplates::findOrFail($request->id);

            $email->subject = $request->subject_name;
            $email->body = $request->description;
            $email->updated_at = now();

            $email->save();

            DB::commit();
            return $this->sendSuccess(__($email->key .'  Email updated successfully!'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }
    public function viewEmail(Request $request){
        $data = EmailTemplates::find($request->id);

        return $this->sendResponse("Email Data",$data);

    }

}
