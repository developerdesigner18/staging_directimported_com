<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Category;
use App\Enum\CategoryType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    use ResponseTrait;

    protected $categoryType;

    public function __construct(Request $request)
    {
        $this->categoryType = strtoupper($request->route('type'));
        if (!in_array($this->categoryType, [CategoryType::CAR->value, CategoryType::TOUR->value, CategoryType::GALLERY->value])) {
            abort(404, 'Invalid category type');
        }
    }

    public function index($type)
    {
        return view('admin.category.index', compact('type'));
    }

    public function listCategory(Request $request, $type)
    {
        try {
            $response = Category::where('type', $this->categoryType)->latest();

            return DataTables::eloquent($response)
                ->addIndexColumn()
                ->filterColumn('title', function ($query, $keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%");
                })
                ->addColumn('title', function ($row) {
                    return $row->name ?? '-';
                })
                ->addColumn('created_at', function ($row) {
                    return dateToHuman($row->created_at, 'd M Y');
                })
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0 d-flex justify-content-center text-center">
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                        <button type="button" onclick="getCategory(' . $row->id . ',this)" class="btn btn-outline-info btn-icon waves-effect waves-light material-shadow-none" >
                            <i class="ri-pencil-fill fs-16"></i>
                        </button>
                    </li>
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                        <button type="button" onclick="removeCategory(' . $row->id . ',this)" class="btn btn-outline-danger btn-icon waves-effect waves-light material-shadow-none">
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

    public function addCategory(Request $request, $type)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $insert = new Category();
            $insert->name = $request->title;
            $insert->type = $this->categoryType;
            $insert->save();

            if ($insert) {
                return $this->sendSuccess("Category has been added successfully");
            } else {
                return $this->sendError("Failed to add Category");
            }
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function editCategory(Request $request, $type)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', Rule::exists('categories', 'id')->where('type', $this->categoryType)],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $response = Category::where('id', $request->id)
                ->where('type', $this->categoryType)
                ->first();

            if ($response) {
                return $this->sendResponse("Category details", $response);
            } else {
                return $this->sendError("Category not found");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }

    public function updateCategory(Request $request, $type)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', Rule::exists('categories', 'id')->where('type', $this->categoryType)],
                'title' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $update = Category::where('id', $request->id)
                ->where('type', $this->categoryType)
                ->update([
                    'name' => $request->title,
                ]);

            if ($update) {
                return $this->sendSuccess("Category has been updated successfully");
            } else {
                return $this->sendError("Failed to update Category");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }

    public function deleteCategory(Request $request, $type)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', Rule::exists('categories', 'id')->where('type', $this->categoryType)],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $remove = Category::where('id', $request->id)
                ->where('type', $this->categoryType)
                ->delete();

            if ($remove) {
                return $this->sendSuccess("Category has been removed successfully");
            } else {
                return $this->sendError("Failed to remove Category");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }
}