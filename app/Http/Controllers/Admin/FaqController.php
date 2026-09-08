<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class FaqController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $categories = FaqCategory::orderBy('name', 'asc')->get();
        return view('admin.manage_info.faq.index', compact('categories'));
    }

    public function list(Request $request)
    {
        $query = Faq::with('category')->latest();

        if ($request->ajax()) {
            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('Key', function ($row) {
                    return $row->key;
                })
                ->addColumn('category', function ($row) {
                    return $row->category ? $row->category->name : '-';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : '-';
                })
                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at ? $row->updated_at->format('d M Y') : '-';
                })
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0 d-flex justify-content-center text-center">
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                        <a href="javascript:void(0);" class="btn btn-outline-info btn-icon waves-effect waves-light material-shadow-none"
                           onclick="editFaq(' . $row->id . ',this)">
                            <i class="ri-pencil-fill fs-16"></i>
                        </a>
                    </li>
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                        <a href="javascript:void(0);" onclick="removeFaq(' . $row->id . ',this)" class="btn btn-outline-danger btn-icon waves-effect waves-light material-shadow-none">
                            <i class="ri-delete-bin-fill fs-16"></i>
                        </a>
                    </li>
                </ul>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'faqTitle' => 'required',
                'faqDescription' => 'required|string',
                'faq_category_id' => 'nullable|exists:faq_categories,id',
            ],
            [
                'faqTitle.required' => 'The title is required.',
                'faqDescription.required' => 'Please provide a description.',
                'faqDescription.string' => 'Description must be a valid string.',
                'faq_category_id.exists' => 'Selected category is invalid.',
            ]
        );

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $faq = new Faq();
            $faq->key = $request->faqTitle;
            $faq->value = $request->faqDescription;
            $faq->faq_category_id = $request->faq_category_id ?: null;
            $faq->save();

            DB::commit();
            return $this->sendSuccess('FAQ added successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $response = Faq::with('category')->where('id', $id)->first();

            if ($response) {
                return $this->sendResponse("FAQ details", $response);
            } else {
                return $this->sendError("FAQ not found");
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
                'editFaqDescription' => 'required|string',
                'faq_category_id' => 'nullable|exists:faq_categories,id',
            ], [
                'editFaqTitle.required' => 'The title field is required.',
                'editFaqDescription.required' => 'The description field is required.',
                'faq_category_id.exists' => 'Selected category is invalid.',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            DB::beginTransaction();
            $update = Faq::where('id', $request->faqId)->first();
            if ($update) {
                $update->key = $request->editFaqTitle;
                $update->value = $request->editFaqDescription;
                $update->faq_category_id = $request->faq_category_id ?: null;
                $update->save();
            }

            DB::commit();
            return $this->sendSuccess('FAQ updated successfully!');
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
                return $this->sendSuccess("FAQ has been removed successfully");
            } else {
                return $this->sendError("Failed to remove FAQ");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }

    /* -------------------------------------------------------------------------- */
    /*                            FAQ Category Methods                            */
    /* -------------------------------------------------------------------------- */

    public function categoryList()
    {
        try {
            $categories = FaqCategory::orderBy('name', 'asc')->get();
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function categoryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'The category name field is required.',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $category = FaqCategory::create([
                'name' => $request->name,
            ]);

            return $this->sendSuccess('FAQ Category added successfully!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function categoryEdit($id)
    {
        try {
            $category = FaqCategory::find($id);
            if ($category) {
                return $this->sendResponse("Category details", $category);
            }
            return $this->sendError("Category not found");
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function categoryUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:faq_categories,id',
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'The category name field is required.',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $category = FaqCategory::find($request->id);
            if ($category) {
                $category->name = $request->name;
                $category->save();
                return $this->sendSuccess('FAQ Category updated successfully!');
            }
            return $this->sendError("Category not found");
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function categoryDelete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:faq_categories,id',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $category = FaqCategory::find($request->id);
            if ($category) {
                $category->delete();
                return $this->sendSuccess("FAQ Category removed successfully");
            }
            return $this->sendError("Failed to remove FAQ Category");
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }
}
