<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Faq;
use App\Models\RentalPolicies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class FaqController extends Controller
{
    use ResponseTrait;
    public function index(){
        return view('admin.manage_info.faq.index');
    }
    public function list(Request $request)
    {
        $query = Faq::latest();


        if ($request->ajax()) {
            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('Key', function ($row) {
                    return $row->key;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : '-';
                })
                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at ? $row->updated_at->format('d M Y') : '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.rental-policies.edit', $row->id);
                    $deleteUrl = route('admin.rental-policies.delete', $row->id);
                    return '<ul class="list-inline mb-0 d-flex justify-content-center text-center">
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                <a href="javascript:void(0);" class="btn btn-outline-info btn-icon waves-effect waves-light material-shadow-none"
           onclick="editFaq(' . $row->id . ',this)">
            <i class="ri-pencil-fill fs-16"></i>
        </a>
                    </li>
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                          <a href="javascript:void(0);"  onclick="removeFaq(' . $row->id . ',this)"class="btn btn-outline-danger btn-icon waves-effect waves-light material-shadow-none">
            <i class="ri-delete-bin-fill fs-16"></i>
        </a>
                    </li>
                </ul>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'faqTitle' => 'required',
            'faqDescription' => 'required|string',

        ],
            [
                'faqTitle.required' => 'The title is required.',
                'faqDescription.required' => 'Please provide a description.',
                'faqDescription.string' => 'Description must be a valid string.',
            ]
        );

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }
        try {
            DB::beginTransaction();

            $faq = new Faq();

            $faq->key=$request->faqTitle;
            $faq->value=$request->faqDescription;
            $faq->save();
            DB::commit();
            return $this->sendSuccess('Faq added successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }

    }
    public function edit($id)
    {


        try {
            $response = faq::where('id',$id)->first();

            if ($response) {
                return $this->sendResponse("Faq details", $response);
            } else {
                return $this->sendError("Faq not found");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'faqId' => 'required',
                'editFaqTitle' => 'required',
                'editFaqDescription' =>'required|string',
            ], [
                'editFaqTitle.required' => 'The title field is required.',
                'editFaqDescription.required' => 'The description field is required.',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $update = Faq::where('id', $request->faqId)->first();
            $update->key = $request->editFaqTitle;
            $update->value = $request->editFaqDescription;
            $update->save();

            DB::commit();
            return $this->sendSuccess('Faq updated successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }
    public function delete(Request $request)
    {
        try {

            $remove = Faq::where('id', $request->id)->delete();

            if ($remove) {
                return $this->sendSuccess("Faq has been removed successfully");
            } else {
                return $this->sendError("Failed to remove Faq");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }

}
