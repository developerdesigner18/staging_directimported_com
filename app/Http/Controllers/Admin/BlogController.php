<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    use ResponseTrait;

    public function general()
    {
        $category = 'general';
        $pageTitle = 'General Blogs Management';
        return view('admin.blog.index', compact('category', 'pageTitle'));
    }

    public function importRegulation()
    {
        $category = 'import_regulation';
        $pageTitle = 'Import Regulation Blogs Management';
        return view('admin.blog.index', compact('category', 'pageTitle'));
    }

    public function list(Request $request)
    {
        $category = $request->input('category', 'general');

        if (in_array(strtolower($category), ['general', ''])) {
            $query = BlogPost::where(function ($q) {
                $q->whereIn('category', ['general', 'General', ''])
                    ->orWhereNull('category');
            })->latest();
        } else {
            $query = BlogPost::whereIn('category', ['import_regulation', 'Import Regulation', 'import regulation'])->latest();
        }

        if ($request->ajax()) {
            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    if (!empty($row->featured_image)) {
                        return '<img src="' . e($row->featured_image) . '" alt="' . e($row->title) . '" class="rounded shadow-sm" style="max-width: 80px; max-height: 50px; object-fit: cover;">';
                    }
                    return '<span class="badge bg-soft-secondary text-secondary">No Image</span>';
                })
                ->addColumn('title', function ($row) {
                    return Str::limit(e($row->title), 60);
                })
                ->addColumn('published_at', function ($row) {
                    return $row->published_at ? $row->published_at->format('d M Y, H:i') : '-';
                })
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0 d-flex justify-content-center text-center">
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                        <a href="javascript:void(0);" class="btn btn-outline-info btn-icon waves-effect waves-light material-shadow-none" onclick="editBlog(' . $row->id . ', this)">
                            <i class="ri-pencil-fill fs-16"></i>
                        </a>
                    </li>
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                        <a href="javascript:void(0);" onclick="removeBlog(' . $row->id . ', this)" class="btn btn-outline-danger btn-icon waves-effect waves-light material-shadow-none">
                            <i class="ri-delete-bin-fill fs-16"></i>
                        </a>
                    </li>
                </ul>';
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }
    }

    public function edit($id)
    {
        try {
            $blog = BlogPost::find($id);

            if ($blog) {
                return $this->sendResponse("Blog details fetched successfully", $blog);
            } else {
                return $this->sendError("Blog post not found", 404);
            }
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:blog_posts,id',
            'title' => 'required|string|max:255',
            'category' => 'required|in:general,import_regulation,General,Import Regulation',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
        ], [
            'title.required' => 'The blog title is required.',
            'category.required' => 'The blog category is required.',
            'category.in' => 'Selected category is invalid.',
            'content.required' => 'The blog content is required.',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $blog = BlogPost::findOrFail($request->id);
            $blog->title = $request->title;
            $blog->slug = Str::slug($request->title);
            $blog->category = $request->category;
            $blog->description = $request->description;
            $blog->content = $request->content;
            if ($request->filled('published_at')) {
                $blog->published_at = $request->published_at;
            }
            $blog->save();

            DB::commit();
            return $this->sendSuccess('Blog post updated successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:blog_posts,id',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $deleted = BlogPost::where('id', $request->id)->delete();

            if ($deleted) {
                return $this->sendSuccess("Blog post removed successfully");
            } else {
                return $this->sendError("Failed to remove blog post");
            }
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }
}
