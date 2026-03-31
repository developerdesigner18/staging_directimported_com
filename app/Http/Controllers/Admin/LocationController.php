<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
class LocationController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return view('admin.location.index');
    }
    public function listLocation(Request $request)
    {
        try {


            $response = Location::latest();

            return DataTables::eloquent($response)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name ?? '-';
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%");
                })
                ->addColumn('created_at', function ($row) {
                    return dateToHuman($row->created_at, 'd M Y');
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.location.edit', $row->id);
                    $deleteUrl = route('admin.location.delete', $row->id);
                    return '<ul class="list-inline mb-0 d-flex justify-content-center text-center">
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                <a href="javascript:void(0);" class="btn btn-outline-info btn-icon waves-effect waves-light material-shadow-none"
           onclick="editLocation(' . $row->id . ',this)">
            <i class="ri-pencil-fill fs-16"></i>
        </a>
                    </li>
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                          <a href="javascript:void(0);"  onclick="removeLocation(' . $row->id . ',this)"class="btn btn-outline-danger btn-icon waves-effect waves-light material-shadow-none">
            <i class="ri-delete-bin-fill fs-16"></i>
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
    public function add(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required'],
                'locationcode' => ['required']
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $insert = new Location();
            $insert->name = $request->name;
            $insert->google_map_link = $request->locationcode;
            $insert->save();

            if ($insert) {
                return $this->sendSuccess("Location has been added successfully");
            } else {
                return $this->sendError("Failed to add Location");
            }
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function edit($id)
    {

        try {



            $response = Location::where('id',$id)->first();
//            dd($response);
            if ($response) {
                return $this->sendResponse("Location details", $response);
            } else {
                return $this->sendError("Location not found");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }

    public function update(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required'],
                'edit_name' => ['required'],
                'edit_locationcode' => ['required'],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $update = Location::where('id', $request->id)->first();
                $update->name = $request->edit_name;
                            $update->google_map_link = $request->edit_locationcode;

$update->save();

            DB::commit();
            return $this->sendSuccess('Location updated successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }
    public function deleteLocation(Request $request)
    {
        try {



            $remove = Location::where('id', $request->id)->delete();

            if ($remove) {
                return $this->sendSuccess("Location has been removed successfully");
            } else {
                return $this->sendError("Failed to remove Location");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }

}
