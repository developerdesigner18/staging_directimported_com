<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Models\RentalPolicies;
use Yajra\DataTables\Facades\DataTables;

class RentalPoliciesController extends Controller
{
    use ResponseTrait;
    public function index(){
       return view('admin.manage_info.rental_policies.index');
    }
    public function list(Request $request)
    {
        $query = RentalPolicies::latest();


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
           onclick="editPolicie(' . $row->id . ',this)">
            <i class="ri-pencil-fill fs-16"></i>
        </a>
                    </li>
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                          <a href="javascript:void(0);"  onclick="removePolicie(' . $row->id . ',this)"class="btn btn-outline-danger btn-icon waves-effect waves-light material-shadow-none">
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
            'policyTitle' => 'required|unique:rental_policies,key',
            'description' => 'required|string',

        ],
            [
                'policyTitle.required' => 'The policy title is required.',
                'policyTitle.unique' => 'This policy title already exists.',
                'description.required' => 'Please provide a description.',
                'description.string' => 'Description must be a valid string.',
            ]
        );

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }
        try {
            DB::beginTransaction();

            $policies = new RentalPolicies();

            $policies->key=$request->policyTitle;
            $policies->value=$request->description;
            $policies->save();
            DB::commit();
            return $this->sendSuccess('Policies added successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }

    }
    public function delete(Request $request)
    {
        try {

            $remove = RentalPolicies::where('id', $request->id)->delete();

            if ($remove) {
                return $this->sendSuccess("Rental Policies has been removed successfully");
            } else {
                return $this->sendError("Failed to remove Policies");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }
    public function edit(Request $request,$id)
    {

        try {
            $response = RentalPolicies::where('id',$id)->first();

            if ($response) {
                return $this->sendResponse("Rental Policies details", $response);
            } else {
                return $this->sendError("Rental Policies not found");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'policyId' => 'required',
                'editPolicyTitle' => 'required|unique:rental_policies,key,' . $request->policyId,
                'editDescription' =>'required|string',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $update = RentalPolicies::where('id', $request->policyId)->first();
            $update->key = $request->editPolicyTitle;
            $update->value = $request->editDescription;
            $update->save();

            DB::commit();
            return $this->sendSuccess('RentalPolicies updated successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

}
