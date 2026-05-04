<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\ContactRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ContactRequestController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return view('admin.contact_requests.index');
    }

    public function list(Request $request)
    {
        try {
            $response = ContactRequest::latest();

            return DataTables::eloquent($response)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return dateToHuman($row->created_at, 'd M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0 d-flex justify-content-center text-center">
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="View">
                        <button type="button" onclick="viewRequest(' . $row->id . ',this)" class="btn btn-outline-info btn-icon waves-effect waves-light material-shadow-none" >
                            <i class="ri-eye-fill fs-16"></i>
                        </button>
                    </li>
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                        <button type="button" onclick="removeRequest(' . $row->id . ',this)" class="btn btn-outline-danger btn-icon waves-effect waves-light material-shadow-none">
                            <i class="ri-delete-bin-5-fill fs-16"></i>
                        </button>
                    </li>
                </ul>';
                })
                ->rawColumns(['action'])
                ->make(true);

        } catch (\Exception $exception) {
            return $this->sendDataTableError($exception->getMessage(), [], 500);
        }
    }

    public function show(Request $request)
    {
        try {
            $contactRequest = ContactRequest::find($request->id);
            if ($contactRequest) {
                return $this->sendResponse("Contact request details", $contactRequest);
            }
            return $this->sendError("Contact request not found");
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $contactRequest = ContactRequest::find($request->id);
            if ($contactRequest && $contactRequest->delete()) {
                return $this->sendSuccess("Contact request deleted successfully");
            }
            return $this->sendError("Failed to delete contact request");
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }
}
