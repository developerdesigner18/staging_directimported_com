<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Accessories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use App\Enum\AccessoryType;

class AccessoryController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return view('admin.accessory.index');
    }

    public function listAccessory(Request $request)
    {
        try {
            $response = Accessories::orderBy('position', 'ASC');
            if ($request->type) {
                $response->where('type', $request->type);
            }
            return DataTables::eloquent($response)
                ->addIndexColumn()
                ->filterColumn('name', function ($query, $keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%");
                })
                ->addColumn('name', function ($row) {
                    return $row->name ?? '-';
                })
                ->addColumn('price', function ($row) {
                    return number_format($row->price, 2);
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : '-';
                })
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0 d-flex justify-content-center text-center">
                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                            <button type="button" onclick="getAccessory(' . $row->id . ', this)" class="btn btn-outline-info btn-icon waves-effect waves-light material-shadow-none">
                                <i class="ri-pencil-fill fs-16"></i>
                            </button>
                        </li>
                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove">
                            <button type="button" onclick="removeAccessory(' . $row->id . ', this)" class="btn btn-outline-danger btn-icon waves-effect waves-light material-shadow-none">
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
    public function reorderAccessories(Request $request)
    {
        foreach ($request->order as $item) {
            Accessories::where('id', $item['id'])->update(['position' => $item['position']]);
        }
        return $this->sendSuccess('success');
    }
    public function addAccessory(Request $request)
    {
//        dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'name'  => ['required', 'string', 'max:255', 'unique:accessories,name'],
                'price' => ['required', 'numeric', 'min:0'],
                'additional_day_price' => ['required', 'numeric', 'min:0'],
                'type' => ['required', Rule::enum(AccessoryType::class)],
                'icon' =>['required','string','max:255'],

            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }
            $maxOrder = Accessories::max('position') ?? 0;

            $insert = new Accessories();
            $insert->name = $request->name;
            $insert->price = $request->price;
            $insert->additional_day_price = $request->additional_day_price;
            $insert->type = $request->type;
            $insert->icon =$request->icon;
            $insert->position = $maxOrder + 1;
            $insert->save();

            if ($insert) {
                return $this->sendSuccess("Accessory has been added successfully");
            } else {
                return $this->sendError("Failed to add Accessory");
            }
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function editAccessory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', Rule::exists('accessories', 'id')],

            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $response = Accessories::find($request->id);

            if ($response) {
                return $this->sendResponse("Accessory details", $response);
            } else {
                return $this->sendError("Accessory not found");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }

    public function updateAccessory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'    => ['required', Rule::exists('accessories', 'id')],
                'name'  => ['required', 'string', 'max:255', Rule::unique('accessories', 'name')->ignore($request->id)],
                'editType' => ['required', Rule::enum(AccessoryType::class)],
                'price' => ['required', 'numeric', 'min:0'],
                'additional_day_price' => ['required', 'numeric', 'min:0'],
                'icon' =>['required','string','max:255'],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $update = Accessories::where('id', $request->id)
                ->update([
                    'name' => $request->name,
                    'price' => $request->price,
                    'additional_day_price' => $request->additional_day_price,
                    'type' => $request->editType,
                    'icon' => $request->icon,
                ]);

            if ($update) {
                return $this->sendSuccess("Accessory has been updated successfully");
            } else {
                return $this->sendError("Failed to update Accessory");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }

    public function deleteAccessory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', Rule::exists('accessories', 'id')],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $remove = Accessories::where('id', $request->id)->delete();

            if ($remove) {
                return $this->sendSuccess("Accessory has been removed successfully");
            } else {
                return $this->sendError("Failed to remove Accessory");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }
}
