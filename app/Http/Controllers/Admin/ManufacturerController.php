<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ManufacturerController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return view('admin.manufacturer.index');
    }

    public function list(Request $request)
    {
        try {
            $response = Manufacturer::latest();

            return DataTables::eloquent($response)
                ->addIndexColumn()
                ->filterColumn('name', function ($query, $keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%");
                })
                ->addColumn('name', function ($row) {
                    return $row->name ?? '-';
                })
                ->addColumn('created_at', function ($row) {
                    return dateToHuman($row->created_at, 'd M Y');
                })
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0 d-flex justify-content-center text-center">
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                        <button type="button" onclick="getManufacturer(' . $row->id . ',this)" class="btn btn-outline-info btn-icon waves-effect waves-light material-shadow-none" >
                            <i class="ri-pencil-fill fs-16"></i>
                        </button>
                    </li>
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                        <button type="button" onclick="removeManufacturer(' . $row->id . ',this)" class="btn btn-outline-danger btn-icon waves-effect waves-light material-shadow-none">
                            <i class="ri-delete-bin-5-fill fs-16"></i>
                        </button>
                    </li>
                </ul>';
                })
                ->rawColumns(['action'])
                ->make(true);

        } catch (\Exception $exception) {
            return $this->sendDataTableError(ERROR_500, [], 500);
        }
    }

    public function add(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('manufacturers')->whereNull('deleted_at')
                ],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $insert = new Manufacturer();
            $insert->name = $request->name;
            $insert->save();

            if ($insert) {
                return $this->sendSuccess("Manufacturer has been added successfully");
            } else {
                return $this->sendError("Failed to add Manufacturer");
            }
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function edit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', Rule::exists('manufacturers', 'id')->whereNull('deleted_at')],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $response = Manufacturer::where('id', $request->id)
                ->whereNull('deleted_at')
                ->first();

            if ($response) {
                return $this->sendResponse("Manufacturer details", $response);
            } else {
                return $this->sendError("Manufacturer not found");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', Rule::exists('manufacturers', 'id')->whereNull('deleted_at')],
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('manufacturers')->whereNull('deleted_at')->ignore($request->id)
                ],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $update = Manufacturer::where('id', $request->id)
                ->whereNull('deleted_at')
                ->update([
                    'name' => $request->name,
                ]);

            if ($update) {
                return $this->sendSuccess("Manufacturer has been updated successfully");
            } else {
                return $this->sendError("Failed to update Manufacturer");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', Rule::exists('manufacturers', 'id')->whereNull('deleted_at')],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $remove = Manufacturer::where('id', $request->id)->delete();

            if ($remove) {
                return $this->sendSuccess("Manufacturer has been removed successfully");
            } else {
                return $this->sendError("Failed to remove Manufacturer");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }
}
