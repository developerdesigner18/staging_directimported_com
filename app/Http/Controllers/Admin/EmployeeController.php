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
use Illuminate\Validation\Rule;
use League\HTMLToMarkdown\HtmlConverter;
use Mockery\Exception;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        $sidebarOrder = [
            'dashboard',
            'contact_requests',
            'cars',
            'services',
            'about_us',
            'blogs',
            'users',
            'employee',
            'gallery',
            'slider',
            'manage_information',
            'newsletters',
            'emails',
            'system_settings',
        ];

        $AllPermissions = Permission::where('guard_name', 'employee')
            ->whereIn('name', $sidebarOrder)
            ->get()
            ->sortBy(function ($perm) use ($sidebarOrder) {
                return array_search($perm->name, $sidebarOrder);
            })
            ->values();

        return view('admin.employee.index', compact('AllPermissions'));
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
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Edit Employee"
                               onclick="editEmployee(' . $row->id . ',this)">
                                <i class="bx bx-pencil fs-16"></i>
                            </a>
                        </li>

                        <li class="list-inline-item">
                            <a href="javascript:void(0);"
                               class="btn btn-outline-danger btn-icon"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Delete Employee"
                               onclick="removeEmployee(' . $row->id . ',this)">
                                <i class="bx bx-trash fs-16"></i>
                            </a>
                        </li>

                        <li class="list-inline-item">
                            <a href="javascript:void(0);"
                               class="btn btn-outline-info btn-icon"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Send Login Details"
                               onclick="sendLoginDetail(' . $row->id . ',this)">
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
    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:employees,email',
                'password' => 'required|string|min:6',
                'permission' => 'required',
            ],
            [
                'first_name.required' => 'The name field is required.',
                'last_name.required' => 'The name field is required.',
                'email.required' => 'The email field is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already registered with another employee.',
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least 6 characters.',
                'permission.required' => 'Please select the permission',
            ]
        );

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }
        try {
            DB::beginTransaction();

            $employee = new Employee();

            $employee->first_name = $request->first_name;
            $employee->last_name = $request->last_name;
            $employee->email = $request->email;
            $employee->password = Hash::make($request->password);
            $employee->plain_password = $request->password;

            $employee->save();

            if ($request->has('permission')) {
                $permissions = Permission::whereIn('id', (array) $request->permission)
                    ->where('guard_name', 'employee')
                    ->get();
                $employee->syncPermissions($permissions);
            }
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            DB::commit();
            return $this->sendSuccess('Employee added successfully!');
        } catch (\Illuminate\Database\QueryException $exception) {
            DB::rollBack();
            if (isset($exception->errorInfo[1]) && $exception->errorInfo[1] == 1062) {
                return $this->sendValidationError(['email' => ['This email is already registered with another employee.']]);
            }
            return $this->sendError($exception->getMessage());
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
                    'permissions' => $employee->permissions->pluck('id')->toArray(), // important!
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
        $rules = [
            'edit_first_name' => 'required',
            'edit_last_name' => 'required',
            'edit_email' => [
                'required',
                'email',
                Rule::unique('employees', 'email')->ignore($request->employee_id),
            ],
            'edit_permission' => 'required|array',
        ];

        $messages = [
            'edit_first_name.required' => 'The first name field is required.',
            'edit_last_name.required' => 'The last name field is required.',
            'edit_email.required' => 'The email field is required.',
            'edit_email.email' => 'Please enter a valid email address.',
            'edit_email.unique' => 'This email is already registered with another employee.',
            'edit_permission.required' => 'Please select the permission',
        ];

        if ($request->filled('edit_password')) {
            $rules['edit_password'] = 'string|min:6';
            $messages['edit_password.min'] = 'The password must be at least 6 characters.';
        } elseif ($request->filled('password')) {
            $rules['password'] = 'string|min:6';
            $messages['password.min'] = 'The password must be at least 6 characters.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $employee = Employee::findOrFail($request->employee_id);
            $employee->first_name = $request->edit_first_name;
            $employee->last_name = $request->edit_last_name;
            $employee->email = $request->edit_email;

            $password = $request->filled('edit_password') ? $request->edit_password : ($request->filled('password') ? $request->password : null);
            if (!empty($password)) {
                $employee->password = Hash::make($password);
                $employee->plain_password = $password;
            }

            $employee->save();

            if ($request->has('edit_permission') && is_array($request->edit_permission)) {
                $permissions = Permission::whereIn('id', $request->edit_permission)
                    ->where('guard_name', 'employee')
                    ->get();
                $employee->syncPermissions($permissions);
            } else {
                $employee->syncPermissions([]);
            }

            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

            DB::commit();
            return $this->sendSuccess('Employee updated successfully!');
        } catch (\Illuminate\Database\QueryException $exception) {
            DB::rollBack();
            if (isset($exception->errorInfo[1]) && $exception->errorInfo[1] == 1062) {
                return $this->sendValidationError(['edit_email' => ['This email is already registered with another employee.']]);
            }
            return $this->sendError($exception->getMessage());
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

            $currentPassword = $employee->plain_password;

            if (empty($currentPassword)) {
                return $this->sendError('Current password is not available for this employee. Please edit the employee to set a password first.', 400);
            }

            if (
                sendDynamicEmail($employee->email, 'LoginDetailsMail', [
                    'name' => $employee->first_name,
                    'email' => $employee->email,
                    'tempPassword' => $currentPassword,
                    'password' => $currentPassword,
                    'loginurl' => route('admin.login'),
                ])
            ) {
                return $this->sendSuccess('Login credentials sent successfully');
            }

            return $this->sendError('Email template not found', 404);

        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }



}
