<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Mail\ApprovedMail;
use App\Mail\SendLoginDetail;
use App\Models\Booking;
use App\Models\EmailTemplates;
use App\Models\Employee;
use App\Models\Location;
use App\Models\RentalPolicies;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use League\HTMLToMarkdown\HtmlConverter;
use Mockery\Exception;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        $AllPermissions=Permission::where('guard_name','employee')->get();
        return view('admin.employee.index',compact('AllPermissions'));
    }
    public function list(Request $request)
    {
        try {

            $response = Employee::latest();

            // DataTable request
            if ($request->ajax()) {

                return DataTables::eloquent($response)
                    ->addIndexColumn()

                    ->addColumn('first_name', function ($row) {
                        return $row->first_name;
                    })

                    ->filterColumn('first_name', function ($query, $keyword) {
                        $query->whereRaw("first_name like ?", ["%{$keyword}%"]);
                    })

                    ->addColumn('last_name', function ($row) {
                        return $row->last_name;
                    })

                    ->addColumn('email', function ($row) {
                        return $row->email;
                    })

                    ->addColumn('created_at', function ($row) {
                        return $row->created_at
                            ? $row->created_at->format('d M Y')
                            : '-';
                    })

                    ->addColumn('action', function ($row) {

                        return '<ul class="list-inline mb-0 d-flex justify-content-center text-center">

                        <li class="list-inline-item">
                            <a href="javascript:void(0);"
                               class="btn btn-outline-info btn-icon"
                               onclick="editEmployee('.$row->id.',this)">
                                <i class="bx bx-pencil fs-16"></i>
                            </a>
                        </li>

                        <li class="list-inline-item">
                            <a href="javascript:void(0);"
                               class="btn btn-outline-danger btn-icon"
                               onclick="removeEmployee('.$row->id.',this)">
                                <i class="bx bx-trash fs-16"></i>
                            </a>
                        </li>

                        <li class="list-inline-item">
                            <a href="javascript:void(0);"
                               class="btn btn-outline-info btn-icon"
                               onclick="sendLoginDetail('.$row->id.',this)">
                                <i class="bx bx-envelope"></i>
                            </a>
                        </li>

                    </ul>';
                    })

                    ->rawColumns(['action'])
                    ->make(true);
            }

            // Grid view request
            $employees = $response->paginate(8);

            return view('admin.employee.index', compact('employees'));

        } catch (\Exception $exception) {
            return $this->sendDataTableError(ERROR_500, [], 500);
        }
    }
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'permission' => 'required',
        ],
            [
                'first_name.required' => 'The name field is required.',
                'last_name.required' => 'The name field is required.',

                'email.required' => 'The email field is required.',
                'permission.required' => 'Please select the permission',

            ]
        );

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }
        try {
            DB::beginTransaction();

            $employee = new Employee();

            $employee->first_name=$request->first_name;
            $employee->last_name=$request->last_name;
            $employee->email=$request->email;


            $employee->save();

            if($request->has('permission')){
                $permissions = Permission::whereIn('id', $request->permission)->get();
                $employee->syncPermissions($permissions);            }
            DB::commit();
            return $this->sendSuccess('Permission added successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }

    }
    public function edit($id)
    {
        try {
            $employee = Employee::with('permissions')->find($id); // Load permissions

            if ($employee) {
                return $this->sendResponse("Employee details", [
                    'id' => $employee->id,
                    'first_name' => $employee->first_name,
                    'last_name' => $employee->last_name,
                    'email' => $employee->email,
                    'permissions' => $employee->permissions->pluck('id'), // important!
                ]);
            } else {
                return $this->sendError("Employee not found");
            }
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'edit_first_name' => 'required',
            'edit_last_name' => 'required',
            'edit_email' => 'required|email',
            'edit_permission' => 'required|array',
        ], [
            'edit_first_name.required' => 'The first name field is required.',
            'edit_last_name.required' => 'The last name field is required.',
            'edit_email.required' => 'The email field is required.',
            'edit_permission.required' => 'Please select the permission',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $employee = Employee::findOrFail($request->employee_id);
            $employee->first_name = $request->edit_first_name;
            $employee->last_name = $request->edit_last_name;
            $employee->email = $request->edit_email;
            $employee->save();

            if ($request->has('edit_permission')) {
                $permissions = Permission::whereIn('id', $request->edit_permission)->get();
                $employee->syncPermissions($permissions);
            }

            DB::commit();
            return $this->sendSuccess('Employee updated successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }
    public function delete(Request $request)
    {

        try {
            $employee = Employee::find($request->id);

            if (!$employee) {
                return $this->sendError("Employee not found");
            }

            $employee->delete(); // deletes employee and their permissions if cascade is set

            return $this->sendSuccess("Employee has been removed successfully");
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }
    public function sendLoginDetail(Request $request)
    {

        try {
            $employee = Employee::find($request->id);

            if (!$employee) {
                return $this->sendError('Employee not found', 404);
            }

            $temp_pwd = generateTemporaryPassword($employee->first_name, $employee->last_name, '');
            $employee->password = Hash::make($temp_pwd);
            $employee->save();

            if (sendDynamicEmail($employee->email, 'LoginDetailsMail', [
                'name' => $employee->first_name,
                'email' => $employee->email,
                'tempPassword' => $temp_pwd,
                'loginurl' => route('admin.login'),
            ])) {
                return $this->sendSuccess('Login credentials sent successfully');
            }

            return $this->sendError('Email template not found', 404);

        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }



}
