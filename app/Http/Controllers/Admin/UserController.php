<?php

namespace App\Http\Controllers\Admin;

use App\Enum\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Booking;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Mail\Documentverification;

class UserController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        // Fetch full Permission objects, not just key
        $visiblePermissions = UserPermission::all();
        return view('admin.user.index', compact('visiblePermissions'));
    }
    public function listUser_old(Request $request)
    {
        try {
            $response = Booking::with('user')->latest();

            return DataTables::eloquent($response)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {

                    if (!$row->user) {
                        return '<img src="'.asset('assets/admin/images/users/avatar-9.jpg').'" width="50">';
                    }

                    return '<a href="' . $row->user->profile_img . '" target="_blank">
                <img src="' . $row->user->profile_img . '" width="50">
            </a>';
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $query->where('first_name', 'LIKE', "%{$keyword}%");
                })
                ->addColumn('name', function ($row) {
                    $name = $row->first_name ?? '-';
                    return $name.' '.($row->last_name ?? '-');
                })
                ->addColumn('price', function ($row) {
                    return number_format($row->price, 2);
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : '-';
                })

                ->addColumn('action', function ($row) {

                  ;
                    $viewButton = '
        <button type="button" onclick="getDetails(' . $row->user_id . ', this)" class="btn btn-outline-info btn-sm btn-icon waves-effect waves-light material-shadow-none" data-bs-toggle="tooltip" title="View">
            <i class="ri-eye-fill fs-16"></i>
        </button>';

                    $link = route('admin.booking.bookings', ['id' => $row->id]);

                    return '
           <ul class="list-inline mb-0 d-flex justify-content-center text-center">

        <li class="list-inline-item">
            ' . $viewButton . '
        </li>

        <li class="list-inline-item">
            <a href="' . route('admin.booking.bookings', $row->id) . '"
               class="btn btn-info btn-sm waves-effect waves-light material-shadow-none"
               title="View Booking">
                <i class="ri-eye-line"></i>
            </a>
        </li>

        <li class="list-inline-item">
            <button type="button"
                    id="btnSendLoginDetail"
                    class="btn btn-soft-danger btn-sm waves-effect waves-light btnSendLoginDetail"
                    data-booking-id="' . $row->booking_id . '"
                    data-status="' . $row->status->value . '"
                    data-email="' . $row->email . '"
                    data-id="' . $row->id . '"
                    data-fname="' . $row->first_name . '"
                    data-lname="' . $row->last_name . '"
                    title="Send Login Details">

                <i class="bx bx-loader spinner me-2" style="display:none" id="btnSendLoginDetailSpinner"></i>
                <i class="ri-mail-send-line"></i>
            </button>
        </li>

    </ul>';
                })
                ->rawColumns(['image','action'])
                ->make(true);

        } catch (\Exception $exception) {
            return $this->sendDataTableError(ERROR_500, [], 500);
        }
    }
    public function listUser(Request $request)
    {
        try {

            $response = User::with('bookings')->latest();

            return DataTables::eloquent($response)
                ->addIndexColumn()

                // ---------------- IMAGE ----------------
                ->addColumn('image', function ($row) {

                    if (!$row->profile_img) {
                        return '<img src="'.asset('assets/admin/images/users/avatar-9.jpg').'" width="50">';
                    }

                    return '
                    <a href="' . asset($row->profile_img) . '" target="_blank">
                        <img src="' . asset($row->profile_img) . '" width="50">
                    </a>';
                })

                // --------------- NAME -------------------
                ->addColumn('name', function ($row) {
                    $fname = $row->first_name ?? '-';
                    $lname = $row->last_name ?? '-';
                    return "$fname $lname";
                })

                ->filterColumn('name', function ($query, $keyword) {
                    $query->where('first_name', 'LIKE', "%{$keyword}%");
                })

                // ------------- CREATED AT ---------------
                ->addColumn('created_at', function ($row) {
                    return $row->created_at
                        ? $row->created_at->format('d M Y')
                        : '-';
                })

                // ------------- ACTION BUTTONS -----------
                ->addColumn('action', function ($row) {

                    // Get first booking safely
                    $booking = $row->bookings->first();
                    $bookingId = $booking->booking_id ?? '';

                    // View button
                    $viewButton = '
                <button type="button"
                        onclick="getDetails(' . $row->id . ', this)"
                        class="btn btn-outline-info btn-sm btn-icon waves-effect waves-light material-shadow-none"
                        data-bs-toggle="tooltip"
                        title="View Details">
                    <i class="bx bx-show fs-16"></i>
                </button>';

                    return '
                <ul class="list-inline mb-0 d-flex justify-content-center text-center">

                    <li class="list-inline-item">
                        ' . $viewButton . '
                    </li>

                    <li class="list-inline-item">
                        <a href="' . route('admin.booking.bookings', $row->id) . '"
                           class="btn btn-info btn-sm waves-effect waves-light material-shadow-none"
                           data-bs-toggle="tooltip"
                           title="View Booking History">
                            <i class="bx bx-calendar"></i>
                        </a>
                    </li>

                    <li class="list-inline-item">
                        <button type="button"
                                id="btnSendLoginDetail"
                                class="btn btn-soft-danger btn-sm waves-effect waves-light btnSendLoginDetail"
                                data-booking-id="' . $bookingId . '"
                                data-status="' . ($row->status->value ?? '') . '"
                                data-email="' . $row->email . '"
                                data-id="' . $row->id . '"
                                data-fname="' . $row->first_name . '"
                                data-lname="' . $row->last_name . '"
                                data-bs-toggle="tooltip"
                                title="Send Login Details">

                            <i class="bx bx-loader spinner me-2" style="display:none" id="btnSendLoginDetailSpinner"></i>
                            <i class="bx bx-envelope"></i>
                        </button>
                    </li>

                </ul>';
                })

                ->rawColumns(['image', 'action'])
                ->make(true);

        } catch (\Exception $exception) {
            return $this->sendDataTableError(ERROR_500, [], 500);
        }
    }
//    function details(Request $request)
//    {
//        try {
//            $validator = Validator::make($request->all(), [
//                'id'  => ['required'],
//            ]);
//
//            if ($validator->fails()) {
//                return $this->sendValidationError($validator->errors());
//            }
//
//            $user_details = UserDetail::where(['user_id'=>$request->id])->first();
//
//            if (!$user_details){
//                $html = '<div class="text-center"><h5>No Document Found!</h5></div>';
//                $btn = '';
//            }else{
//                $html = '<div class="row justify-content-center">
//                    <div class="col-md-6 col-lg-4 text-center remove-image-div mb-3">
//                        <label>Passport</label>
//                        <a href="'.$user_details->passport.'" target="_blank"><img src="'.$user_details->passport.'" width="100%"></a>
//                    </div>';
//                    $html .= '<div class="col-md-6 col-lg-4 text-center remove-image-div mb-3">
//                        <label>International lic</label>
//                        <a href="'.$user_details->international_lic.'" target="_blank"><img src="'.$user_details->international_lic.'" width="100%"></a>
//                    </div>';
//                    $html .= '<div class="col-md-6 col-lg-4 text-center remove-image-div mb-3">
//                        <label>Regular lic</label>
//                        <a href="'.$user_details->regular_lic.'" target="_blank"><img src="'.$user_details->regular_lic.'" width="100%"></a>
//                    </div>
//                </div>';
//                $btn = '';
//                if ($user_details->status->name != 'VERIFIED'){
//                    $btn .= '
//                    <div class="text-center">
//                        <button type="button" onclick="updateStatusVerified(' . $user_details->id . ', this)" class="btn btn-outline-success waves-effect waves-light material-shadow-none">
//                            Verified
//                        </button>
//                        <button type="button" class="btn btn-outline-danger waves-effect waves-light material-shadow-none detailsRejectBtn" data-id="'.$user_details->id.'" data-user_id="'.$user_details->user_id.'">
//                            Rejected
//                        </button>
//                    </div>';
//                }
//            }
//
//            return $this->sendSuccess(['html'=>$html, 'btn'=>$btn]);
//        } catch (\Exception $exception) {
//            return $this->sendError($exception->getMessage(), 500);
//        }
//    }


    public function details(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'  => ['required'],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $user_details = UserDetail::where(['user_id' => $request->id])->first();

            if (!$user_details) {
                $html = '<div class="text-center"><h5>No Document Found!</h5></div>';
                return $this->sendSuccess(['html' => $html, 'btn' => '']);
            }

            $html = '<div class="row justify-content-center">';

            $documents = [
                'passport' => 'Passport',
                'international_lic' => 'International License',
                'regular_lic' => 'Regular License',
                'regular_lic_back' => 'Regular License Back',
                'international_lic_back' => 'International License Back',
            ];

            foreach ($documents as $field => $label) {
                $status_field = $field . '_status';
                $doc_url = $user_details->$field;
                $status = $user_details->$status_field;
                $field_value = match ($field) {
                    'passport' => $user_details->passport_number,
                    'international_lic',
                    'international_lic_back' => $user_details->idp_number,
                    'regular_lic',
                    'regular_lic_back' => $user_details->regular_lic_number,
                    default => '',
                };

                $has_image = $user_details->getRawOriginal($field) ? 1 : 0;

                $html .= '<div class="col-md-6 col-lg-4 text-center mb-3">
                <label>' . $label . '</label>
               <a href="javascript:void(0);"
                   class="openPreview"
                   data-img="'.$doc_url.'"
                   data-docno="'.$field_value.'"
                   data-id="'.$user_details->id.'"
                   data-user_id="'.$user_details->user_id.'"
                   data-field="'.$field.'"
                   data-has-image="'.$has_image.'"
                   data-status="'.$status.'">
                    <img src="' . $doc_url . '"
                         style="height:200px;object-fit:cover;width:100%;cursor:pointer;" alt="document image ">
                </a>
                <div class="mt-2">';

                if ($user_details->getRawOriginal($field)) {
                    if ($status == 'VERIFIED') {
                        $html .= '
                        <button class="btn btn-success btn-sm" disabled>Verified</button>
                        <button type="button"
                            class="btn btn-sm btn-outline-danger detailsRejectBtn"
                            data-id="' .$user_details->id.'"
                            data-user_id="' .$user_details->user_id. '"
                            data-field="'.$field.'">
                            Reject
                        </button>';
                    }
                    elseif ($status == 'REJECTED') {
                        $html .= '
                        <button class="btn btn-danger btn-sm" disabled>Rejected</button>
                        <button type="button" class="btn btn-outline-success btn-sm"
                            onclick="verifyDocument(' . $user_details->id . ', \'' . $field . '\', this)">
                            Verify
                        </button>';
                    }
                    else {
                        $html .= '
                        <button type="button" class="btn btn-outline-success btn-sm"
                            onclick="verifyDocument(' . $user_details->id . ', \'' . $field . '\', this)">
                            Verify
                        </button>
                        <button type="button"
                            class="btn btn-sm btn-outline-danger detailsRejectBtn"
                            data-id="' .$user_details->id.'"
                            data-user_id="' .$user_details->user_id. '"
                            data-field="'.$field.'">
                            Reject
                        </button>';
                    }
                }

                $html .= '</div></div>';
            }

            $html .= '</div>';

            return $this->sendSuccess(['html' => $html, 'btn' => '']);

        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function verifySingleDocument(Request $request)
    {

        $user = UserDetail::findOrFail($request->id);
        $column = $request->field . '_status';

        $user->$column =DocumentStatus::VERIFIED->value;
        $user->save();

        return $this->sendSuccess('Document verified successfully.');
    }
    function statusVerified(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'id'  => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $user_details = UserDetail::find($request->id);
            $user_details->update(['status'=>DocumentStatus::VERIFIED]);

            DB::commit();
            return $this->sendSuccess('User Details Verified successfully!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    function statusRejected(Request $request)
    {

        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'user_id'  => 'required',
                'message'  => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $user = User::find($request->user_id);
            sendDynamicEmail($user->email, 'DocumentRejectedMail', [
                'name' => $user->first_name,
                'reason' => $request->message,
            ]);

            DB::commit();
            return $this->sendSuccess('User Details Rejected successfully!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }
    function rejectedSingleDocument(Request $request)
    {

        try {
            DB::beginTransaction();
            $userDetail = UserDetail::findOrFail($request->id);
            $user = User::findOrFail($userDetail->user_id);

            $column = $request->field . '_status';

            $userDetail->$column =DocumentStatus::REJECTED->value;
            $userDetail->save();
            sendDynamicEmail($user->email, 'DocumentRejectedMail', [
                'name' => $user->first_name,
                'reason' => $request->message,
            ]);

            DB::commit();
            return $this->sendSuccess('User Details Rejected successfully!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }


}
