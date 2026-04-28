<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\AuctionGrade;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AucationController extends Controller
{
    use ResponseTrait;

    public function auctionGrade()
    {
        return view('admin.car.auction-grade');
    }
    public function auctionGradeList(Request $request)
    {
        try {
            $response = AuctionGrade::latest();

            return DataTables::eloquent($response)
                ->addIndexColumn()
                ->filterColumn('grade', function ($query, $keyword) {
                    $query->where('grade', 'LIKE', "%{$keyword}%");
                })
                ->addColumn('grade', function ($row) {
                    return $row->grade ?? '-';
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks ?? '-';
                })
                ->addColumn('created_at', function ($row) {
                    return dateToHuman($row->created_at, 'd M Y');
                })
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0 d-flex justify-content-center text-center">
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                        <button type="button" onclick="editAuctionGradeMD(' . $row->id . ',this)" class="btn btn-outline-info btn-icon waves-effect waves-light material-shadow-none" >
                            <i class="ri-pencil-fill fs-16"></i>
                        </button>
                    </li>
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                        <button type="button" onclick="removeAuctionGrade(' . $row->id . ',this)" class="btn btn-outline-danger btn-icon waves-effect waves-light material-shadow-none">
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
    public function auctionGradeAdd(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'grade' => [
                'required',
                Rule::unique('auction_grades')->whereNull('deleted_at')
            ],
            'remarks' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();
            $auctionGrade = new AuctionGrade();
            $auctionGrade->grade = $request->grade;
            $auctionGrade->remarks = $request->remarks;
            $auctionGrade->save();
            DB::commit();
            return $this->sendSuccess('Auction grade added successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

    public function auctionGradeEdit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', Rule::exists('auction_grades', 'id')->whereNull('deleted_at')],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $response = AuctionGrade::where('id', $request->id)
                ->whereNull('deleted_at')
                ->first();

            if ($response) {
                return $this->sendResponse("Auction grade details", $response);
            } else {
                return $this->sendError("Auction grade not found");
            }
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
    public function auctionGradeUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'grade' => [
                'required',
                Rule::unique('auction_grades')->whereNull('deleted_at')->ignore($request->id)
            ],
            'remarks' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();
            $auctionGrade = AuctionGrade::where('id', $request->id)->first();
            $auctionGrade->grade = $request->grade;
            $auctionGrade->remarks = $request->remarks;
            $auctionGrade->save();
            DB::commit();
            return $this->sendSuccess('Auction grade updated successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }
    public function auctionGradeDelete(Request $request)
    {
        try {
            $auctionGrade = AuctionGrade::where('id', $request->id)->first();
            if (empty($auctionGrade)) {
                return $this->sendError("Data not found", 404);
            }
            $auctionGrade->delete();
            return $this->sendResponse("Data Deleted Successfully", 200);
        } catch (\Exception $exception) {
            return $this->sendError("Data Deleted Failed", 500);
        }
    }
}
