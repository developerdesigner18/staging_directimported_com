<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\AdminLabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LabelController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return view('admin.labels.index');
    }

    public function list(Request $request)
    {
        $query = AdminLabel::query();

        if ($request->ajax()) {
            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('page', function ($row) {
                    return ucwords(str_replace('_', ' ', $row->page));
                })
                ->editColumn('key', function ($row) {
                    return ucwords(str_replace('_', ' ', $row->key));
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y H:i') : '-';
                })
                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at ? $row->updated_at->format('d M Y H:i') : '-';
                })
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0 d-flex justify-content-center text-center">
                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                            <a href="javascript:void(0);" class="btn btn-outline-info btn-icon waves-effect waves-light material-shadow-none"
                               onclick="editLabel(' . $row->id . ')">
                                <i class="ri-pencil-fill fs-16"></i>
                            </a>
                        </li>
                    </ul>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function edit($id)
    {
        try {
            $label = AdminLabel::find($id);

            if ($label) {
                $label->page = ucwords(str_replace('_', ' ', $label->page));
                $label->key = ucwords(str_replace('_', ' ', $label->key));
                return $this->sendResponse("Label details", $label);
            } else {
                return $this->sendError("Label not found");
            }
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'labelId' => 'required|exists:admin_labels,id',
                'labelValue' => 'required|string',
            ], [
                'labelValue.required' => 'The value field is required.',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            DB::beginTransaction();

            $label = AdminLabel::find($request->labelId);
            $label->value = $request->labelValue;
            $label->save();

            DB::commit();
            return $this->sendSuccess('Label updated successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }
}
