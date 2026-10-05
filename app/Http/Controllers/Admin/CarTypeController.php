<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\CarType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class CarTypeController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return view('admin.car_type.index');
    }

    public function list(Request $request)
    {
        try {
            $response = CarType::latest();

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
                        <button type="button" onclick="getCarType(' . $row->id . ',this)" class="btn btn-outline-info btn-icon waves-effect waves-light material-shadow-none" >
                            <i class="ri-pencil-fill fs-16"></i>
                        </button>
                    </li>
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                        <button type="button" onclick="removeCarType(' . $row->id . ',this)" class="btn btn-outline-danger btn-icon waves-effect waves-light material-shadow-none">
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
                    Rule::unique('car_types')->whereNull('deleted_at')
                ],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $insert = new CarType();
            $insert->name = $request->name;
            $insert->save();

            if ($insert) {
                return $this->sendSuccess("Car Type has been added successfully");
            } else {
                return $this->sendError("Failed to add Car Type");
            }
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function edit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', Rule::exists('car_types', 'id')->whereNull('deleted_at')],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $response = CarType::where('id', $request->id)
                ->whereNull('deleted_at')
                ->first();

            if ($response) {
                return $this->sendResponse("Car Type details", $response);
            } else {
                return $this->sendError("Car Type not found");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', Rule::exists('car_types', 'id')->whereNull('deleted_at')],
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('car_types')->whereNull('deleted_at')->ignore($request->id)
                ],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $update = CarType::where('id', $request->id)
                ->whereNull('deleted_at')
                ->update([
                    'name' => $request->name,
                ]);

            if ($update) {
                return $this->sendSuccess("Car Type has been updated successfully");
            } else {
                return $this->sendError("Failed to update Car Type");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', Rule::exists('car_types', 'id')->whereNull('deleted_at')],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $remove = CarType::where('id', $request->id)->delete();

            if ($remove) {
                return $this->sendSuccess("Car Type has been removed successfully");
            } else {
                return $this->sendError("Failed to remove Car Type");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }
}
