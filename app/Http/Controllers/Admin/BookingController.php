<?php

namespace App\Http\Controllers\Admin;

use App\Enum\BookingStatus;
use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Mail\ApprovedMail;
use App\Mail\BookingMail;
use App\Mail\Documentverification;
use App\Mail\PaymentEmail;
use App\Mail\SendLoginDetail;
use App\Models\Accessories;
use App\Models\Bike;
use App\Models\Booking;
use App\Models\EmailTemplates;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use League\HTMLToMarkdown\HtmlConverter;
use Mockery\Exception;
use Yajra\DataTables\Facades\DataTables;

class BookingController extends Controller
{
    use ResponseTrait;

    public function listBookingUser(Request $request)
    {
        // dd('dd');
        try {
            $response = Booking::with(['user', 'bike'])->where('user_id', $request->id);

            return DataTables::eloquent($response)
                ->addIndexColumn()
                ->filterColumn('name', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->whereRaw("CONCAT_WS(' ', first_name, last_name) like ?", ["%{$keyword}%"]);
                    });
                })
                ->addColumn('email', function ($row) {
                    return $row->email ?? '-';
                })
                ->addColumn('name', function ($row) {
                    $firstName = $row->user->first_name ?? $row->first_name;
                    $lastName = $row->user->last_name ?? $row->last_name;

                    return trim($firstName.' '.$lastName);
                })
              ->addColumn('status', function ($row) {
    $value = $row->status?->value ?? $row->status;
    $label = $row->status?->label() ?? '';

    return "{$value} ({$label})";
})

                ->filterColumn('start_date', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(start_date, '%d %M %Y') LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('start_time', function ($query, $keyword) {
                    $query->where('start_time', 'LIKE', "%{$keyword}%");
                })
                ->filterColumn('end_date', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(end_date, '%d %M %Y') LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('end_time', function ($query, $keyword) {
                    $query->where('end_time', 'LIKE', "%{$keyword}%");
                })
                ->addColumn('start_date', function ($row) {
                    return $row->start_date->format('d M Y').' '.$row->start_time->format('H:i A');
                })
                ->addColumn('end_date', function ($row) {
                    return $row->end_date->format('d M Y').' '.$row->end_time->format('H:i A');
                })
                ->addColumn('price', function ($row) {
                    return number_format($row->price);
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : '-';
                })
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0 d-flex justify-content-center text-center">

                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                            <button type="button" data-id="'.$row->id.'" class="btn btn-outline-danger btn-icon btn-md waves-effect waves-light material-shadow-none deleteBtn">
                                <i class="ri-delete-bin-5-fill fs-16"></i>
                            </button>
                        </li>
                    </ul>';
                })
                ->rawColumns(['status', 'start_date', 'end_date', 'image'])
                ->make(true);

        } catch (\Exception $exception) {
            return $this->sendDataTableError(ERROR_500, [], 500);
        }
    }

    public function index()
    {
        return view('admin.booking.index');
    }

    public function bookings(Request $request)
    {

        $id = $request->id;

        return view('admin.booking.user-booking-details', compact('id'));
    }

    public function list(Request $request)
    {

        try {
            $response = Booking::latest();

            return DataTables::eloquent($response)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->status) {
                        $query->where('status', $request->status);
                    }
                }, true)
                ->filterColumn('name', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->whereRaw("CONCAT_WS(' ', first_name, last_name) like ?", ["%{$keyword}%"]);
                    });
                })
                ->addColumn('email', function ($row) {
                    return $row->email ?? '-';
                })
                ->addColumn('name', function ($row) {
                    $firstName = $row->user->first_name ?? $row->first_name;
                    $lastName = $row->user->last_name ?? $row->last_name;

                    return trim($firstName.' '.$lastName);
                })
                ->addColumn('status', function ($row) {
                    return $row->status?->label() ?? '';
                })

                ->filterColumn('start_date', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(start_date, '%d %M %Y') LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('start_time', function ($query, $keyword) {
                    $query->where('start_time', 'LIKE', "%{$keyword}%");
                })
                ->filterColumn('end_date', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(end_date, '%d %M %Y') LIKE ?", ["%{$keyword}%"]);
                })
                ->filterColumn('end_time', function ($query, $keyword) {
                    $query->where('end_time', 'LIKE', "%{$keyword}%");
                })
                ->addColumn('start_date', function ($row) {
                    return $row->start_date->format('d M Y').' '.$row->start_time->format('H:i A');
                })
                ->addColumn('end_date', function ($row) {
                    return $row->end_date->format('d M Y').' '.$row->end_time->format('H:i A');
                })
                ->addColumn('price', function ($row) {
                    return number_format($row->price);
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : '-';
                })
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0 d-flex justify-content-center text-center">
                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Accessories">
                            <button type="button" onclick="getAccessories('.$row->id.', this)" class="btn btn-success btn-md waves-effect waves-light material-shadow-none text-black">
                                Accessories
                            </button>
                        </li>
                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Table">
                            <button type="button" onclick="getTable('.$row->id.', this)" class="btn btn-warning btn-md waves-effect waves-light material-shadow-none text-black">
                                Quote
                            </button>
                        </li>
                        <li class="list-inline-item">
                            <a href="'.route('admin.booking.view', $row->id).'" class="btn btn-info btn-md waves-effect waves-light material-shadow-none text-black">
                                View
                            </a>
                        </li>
                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                            <button type="button" data-id="'.$row->id.'" class="btn btn-outline-danger btn-icon btn-md waves-effect waves-light material-shadow-none deleteBtn">
                                <i class="ri-delete-bin-5-fill fs-16"></i>
                            </button>
                        </li>
                    </ul>';
                })
                ->rawColumns(['status', 'start_date', 'end_date', 'image', 'action'])
                ->make(true);

        } catch (\Exception $exception) {
            return $this->sendDataTableError(ERROR_500, [], 500);
        }
    }

    public function calculateQuote(Request $request)
    {
        try {
            $bike = Bike::find($request->bike_id);
            if (! $bike) {
                return $this->sendError('Bike not found', 404);
            }

            $totalDays = totalBookingDays($request->start_date, $request->end_date, $request->end_time);

            $pricePerDay = $bike->getTieredPrice($totalDays);
            $price = $pricePerDay * $totalDays;

            // Form sends insurance boolean
            $insurance_price = ($request->has('insurance') && $request->insurance == 1) ? ($bike->insurance_price * $totalDays) : 0;

            $subtotal = $price + $insurance_price;

            if ($request->selected_accessories) {
                foreach ($request->selected_accessories as $acc_id) {
                    $accData = Accessories::find($acc_id);
                    if (! $accData) {
                        continue;
                    }

                    if ($totalDays > 1 && $accData->additional_day_price) {
                        $oneDayPrice = $accData->price;
                        $oneDayLaterPrice = $accData->additional_day_price;
                        $accPrice = $oneDayPrice + ($oneDayLaterPrice * ($totalDays - 1));
                    } else {
                        $accPrice = $accData->price * ($totalDays > 0 ? $totalDays : 1);
                    }

                    if (\Illuminate\Support\Str::contains(strtolower($accData->name), 'helmet') && $accPrice >= 6500) {
                        $accPrice = 6500;
                    }
                    $subtotal += $accPrice;
                }
            }

            $tax = round($subtotal * 0.10);
            $cardFee = round(($subtotal + $tax) * 0.0365);
            $totalPrice = $subtotal + $tax + $cardFee;

            return $this->sendSuccess(['price' => $totalPrice, 'insurance_price' => $insurance_price]);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function tableData(Request $request)
    {
        try {
            $booking = Booking::find($request->id);
            $table_data = $booking->table_data ?? '';

            // dd($booking);
            return $this->sendSuccess($table_data);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }
    //    function sendDocumentVerifiedMail(Request $request)
    //    {
    //
    //        try {
    //            $booking = Booking::where('booking_id',$request->booking_id)->first();
    //        } catch (\Exception $exception){
    //            return $this->sendError($exception->getMessage(), 500);
    //        }
    //    }

    public function accessories(Request $request)
    {
        try {
            $booking = Booking::find($request->id);
            if (! $booking) {
                return $this->sendError('Booking not found!', 404);
            }

            $accessories = $booking->selectedAccessoriesList();
            $totalAccessoriesPrice = 0;
            $html = '';

            if ($accessories->isNotEmpty()) {
                $html .= '<div class="">
                    <table class="table">';
                $html .= '<thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th class="text-end">Total Price</th>
                            </tr>
                        </thead>
                        <tbody>';
                foreach ($accessories as $key => $acc) {
                    $html .= '<tr>
                                <td>'.($key + 1).'</td>
                                <td>'.$acc->name.'</td>
                                <td class="text-end">¥'.number_format($acc->computed_price).'</td>
                            </tr>';
                    $totalAccessoriesPrice += $acc->computed_price;
                }

                // Also add insurance if selected
                if ($booking->insurance && $booking->bike) {
                    $insPrice = $booking->bike->insurance_price * $booking->totalDays();
                    $html .= '<tr class="text-muted">
                                <td>-</td>
                                <td>Optional Insurance</td>
                                <td class="text-end">¥'.number_format($insPrice).'</td>
                            </tr>';
                    $totalAccessoriesPrice += $insPrice;
                }

                $html .= '</tbody>
                        <tfoot>
                            <tr class="fw-bold fs-5">
                                <td colspan="2" class="text-end">Total</td>
                                <td class="text-end">¥'.number_format($totalAccessoriesPrice).'</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>';
            } else {
                $html .= '<div class="text-center"><h5>No Accessories Found!</h5></div>';
            }

            return $this->sendSuccess($html);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $booking = Booking::with('user')->find($request->id);
            if (! $booking) {
                return $this->sendError('Booking not found', 404);
            }

            $booking->status = $request->status;

            if ($booking->status === BookingStatus::APPROVED) {
                $user = User::where('email', $booking->email)->first();

                if (! $user) {
                    $user = new User;
                    $user->first_name = $booking->first_name ?? '';
                    $user->last_name = $booking->last_name ?? '';
                    $user->email = $booking->email;
                    $user->mobile = $booking->mobile ?? '';
                    $user->password = Hash::make($booking->booking_id);
                    $user->save();

                    $booking->user_id = $user->id;
                    sendDynamicEmail($user->email, 'ApprovedMail', [
                        'name' => $user->first_name,
                        'email' => $user->email,
                        'booking_id' => $booking->booking_id,
                    ]);
                } else {
                    $booking->user_id = $user->id;
                }
            }

            $booking->save();

            return $this->sendSuccess('Booking status updated successfully');
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', Rule::exists('bookings', 'id')],
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            Booking::find($request->id)->delete();

            DB::commit();

            return $this->sendSuccess('Booking deleted successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->sendError($exception->getMessage());

        }
    }

    public function sendLoginDetail(Request $request)
    {

        try {
            $booking = Booking::where('booking_id', $request->booking_id)->first();

            // If booking is not found, return error
            if (! $booking) {
                return $this->sendError('Booking not found', 404);
            }

            // Update the booking status
            if ($booking->status === \App\Enum\BookingStatus::PROCESSING->value) {
                $booking->status = \App\Enum\BookingStatus::APPROVED->value;
            }
            // Find the user associated with the booking's email
            $user = User::where('email', $booking->email)->first();

            // Generate temporary password using helper
            $tempPassword = generateTemporaryPassword($booking->first_name, $booking->last_name, $booking->booking_id);

            if (! $user) {
                // If the user doesn't exist, create a new one
                $user = new User;
                $user->first_name = $booking->first_name ?? '';
                $user->last_name = $booking->last_name ?? '';
                $user->email = $booking->email;
                $user->mobile = $booking->mobile ?? '';
                $user->password = Hash::make($tempPassword);
                $user->password_set_at = now();
                $user->save();

                // Associate the new user with the booking
                $booking->user_id = $user->id;
                $booking->save();

                sendDynamicEmail($user->email, 'ApprovedMail', [
                    'name' => $user->first_name,
                    'email' => $user->email,
                    'booking_id' => $booking->booking_id,
                ]);

                sendDynamicEmail($user->email, 'LoginDetailsMail', [
                    'name' => $user->first_name,
                    'email' => $user->email,
                    'tempPassword' => $tempPassword,
                    'loginurl' => route('login'),
                ]);

                return $this->sendSuccess('Emails sent successfully with approval and login credentials');

            } else {
                // If the user exists, update their password and send the login credentials email
                $user->update([
                    'password' => Hash::make($tempPassword),
                    'password_set_at' => now(),
                ]);

                // Associate the user with the booking
                $booking->user_id = $user->id;

                // Update booking status to APPROVED if it's PROCESSING
                if ($booking->status === \App\Enum\BookingStatus::PROCESSING->value) {
                    $booking->status = \App\Enum\BookingStatus::APPROVED->value;
                }
                $booking->save();

                sendDynamicEmail($user->email, 'LoginDetailsMail', [
                    'name' => $user->first_name,
                    'email' => $user->email,
                    'tempPassword' => $tempPassword,
                    'loginurl' => route('login'),
                ]);

                return $this->sendSuccess('Login credentials sent successfully');
            }

        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function paymentMail(Request $request)
    {
        try {

            $request->validate([
                'booking_id' => 'required|string',
            ]);

            $booking = Booking::where('booking_id', $request->booking_id)->first();

            if (! $booking) {
                return $this->sendError('Booking not found.', 404);
            }

            $user = User::where('email', $booking->email)->first();

            if (! $user) {
                return $this->sendError('User not found for this booking.', 404);
            }

            sendDynamicEmail($user->email, 'PaymentRequestMail', [
                'name' => $user->first_name,
                'payment_url' => route('login'), // Or a specific payment URL if available
            ]);

            return $this->sendSuccess('Payment mail sent successfully.');

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function DocumentVerifiedMail(Request $request)
    {
        try {

            $request->validate([
                'booking_id' => 'required|string',
            ]);

            $booking = Booking::where('booking_id', $request->booking_id)->first();

            if (! $booking) {
                return $this->sendError('Booking not found.', 404);
            }

            $user = User::where('email', $booking->email)->first();

            if (! $user) {
                return $this->sendError('User not found for this booking.', 404);
            }

            sendDynamicEmail($user->email, 'DocumentRejectedMail', [
                'name' => $user->first_name,
                'reason' => 'Please check your submitted documents and try again.',
            ]);

            return $this->sendSuccess('Payment mail sent successfully.');

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->sendError($exception->getMessage(), 500);
        }
    }

    //    public function view($id)
    //    {
    //        $rowData = Booking::with('user')->find($id);
    //   $bikes = Bike::all();
    //        $accessories = Accessories::all();
    //        return view('admin.booking.view',compact('rowData','bikes','accessories'));
    //    }
    public function view(Request $request, $id)
    {
        $rowData = Booking::with('user', 'bike')->find($id);
        $bikes = Bike::all();
        $accessories = Accessories::all();

        $bike = $rowData->bike;

        // IDs stored in bike table (JSON)
        $freeAccessoryIds = $rowData->included_accessories ?? [];
        $extraAccessoryIds = $rowData->selected_accessories ?? [];
        $freeList = Accessories::where('type', 'FREE')->get();
        $extraList = Accessories::where('type', 'EXTRA')->get();

        return view('admin.booking.view', compact(
            'rowData', 'bikes',
            'freeList', 'extraList',
            'freeAccessoryIds', 'extraAccessoryIds'
        ));
    }

    public function viewAction(Request $request, $id)
    {

        //        dd($request->included_accessories, $request->selected_accessories);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'nullable',
            'comment' => 'nullable',
            'status' => 'required',
            'bike_id' => 'required',
            'included_accessories' => 'nullable',
            'selected_accessories' => 'nullable',
            'system_comment' => 'nullable',
            'email_comment' => 'nullable',
            'price' => 'nullable',
            'invoice' => 'nullable',
            'bookingMail' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();
            $booking = Booking::find($id);

            $bike = Bike::find($request->bike_id);
            $totalDays = totalBookingDays($request->start_date, $request->end_date, $request->end_time);

            $pricePerDay = $bike->getTieredPrice($totalDays);
            $price = $pricePerDay * $totalDays;

            $insurance_price = ($request->has('insurance') && $request->insurance == 1) ? ($bike->insurance_price * $totalDays) : 0;
            $subtotal = $price + $insurance_price;

            $details = '<table class="table table-bordered mb-4 border-0">
                    <tr>
                        <td colspan="2"><h5 class="text-center fw-bold m-0">BOOKING QUOTE</h5></td>
                    </tr>
                    <tbody>
                        <tr>
                            <td>Full name</td>
                            <td>'.trim(($booking->first_name ?? '').' '.($booking->last_name ?? '')).'</td>
                        </tr>
                        <tr>
                            <td>E-mail</td>
                            <td>'.($booking->email ?? '').'</td>
                        </tr>
                        <tr>
                            <td>Mobile</td>
                            <td>'.(isset($booking->user) ? $booking->user->mobile : '').'</td>
                        </tr>
                        <tr>
                            <td>Bike Name</td>
                            <td>'.$bike->name.'</td>
                        </tr>
                        <tr>
                            <td>Total Days</td>
                            <td>'.$totalDays.'</td>
                        </tr>
                        <tr>
                            <td>Start Date</td>
                            <td>'.date('d/m/Y', strtotime($request->start_date)).' Pickup '.date('h:i A', strtotime($request->start_time)).'</td>
                        </tr>
                        <tr>
                            <td>End Date</td>
                            <td>'.date('d/m/Y', strtotime($request->end_date)).' Drop off '.date('h:i A', strtotime($request->end_time)).'</td>
                        </tr>
                        <tr>
                            <td>Total Bike Price</td>
                            <td>¥'.$price.'</td>
                        </tr>
                    </tbody>
                    <tr>
                        <td colspan="2"><h6 class="m-0 fw-bold">Accessories & Insurance</h6></td>
                    </tr>
                    <tbody>
                        <tr>
                            <td>Insurance</td>
                            <td>'.(($insurance_price > 0) ? '¥'.$insurance_price : 'NA').'</td>
                        </tr>';
            if ($request->selected_accessories) {
                foreach ($request->selected_accessories as $acc_id) {
                    $accData = Accessories::find($acc_id);
                    if (! $accData) {
                        continue;
                    }

                    if ($totalDays > 1 && $accData->additional_day_price) {
                        $oneDayPrice = $accData->price;
                        $oneDayLaterPrice = $accData->additional_day_price;
                        $accPrice = $oneDayPrice + ($oneDayLaterPrice * ($totalDays - 1));
                    } else {
                        $accPrice = $accData->price * ($totalDays > 0 ? $totalDays : 1);
                    }

                    if (\Illuminate\Support\Str::contains(strtolower($accData->name), 'helmet') && $accPrice >= 6500) {
                        $accPrice = 6500;
                    }

                    $subtotal += $accPrice;
                    $details .= '
                                            <tr class="">
                                                <td>'.$accData->name.'</td>
                                                <td>¥'.$accPrice.'</td>
                                            </tr>';
                }
            }

            $tax = round($subtotal * 0.10);
            $cardFee = round(($subtotal + $tax) * 0.0365);

            $details .= '
                            <tr>
                                <td>TAX 10%</td>
                                <td>¥'.number_format($tax).'</td>
                            </tr>
                            <tr>
                                <td>Card Fee 3.65%</td>
                                <td>¥'.number_format($cardFee).'</td>
                            </tr>
                           <tr class="fw-bold fs-5">
                                <td>Total Price</td>
                                <td>¥'.number_format((float) $request->price).'</td>
                           </tr>
                        </tbody>
                    </table>';

            //            dd($details);

            $booking->first_name = $request->first_name;
            $booking->last_name = $request->last_name;
            $booking->start_date = $request->start_date;
            $booking->end_date = $request->end_date;
            $booking->start_time = $request->start_time;
            $booking->end_time = $request->end_time;
            $booking->location = $request->location;
            $booking->comment = $request->comment ?? null;
            $booking->status = $request->status;
            $booking->bike_id = $request->bike_id;
            $booking->included_accessories = $request->included_accessories;
            $booking->selected_accessories = $request->selected_accessories;
            $booking->system_comment = $request->system_comment ?? null;
            $booking->email_comment = $request->email_comment ?? null;
            $booking->price = $request->price;
            $booking->insurance = ($request->has('insurance') && $request->insurance == 1) ? 1 : 0;
            $booking->table_data = $details;
            $booking->save();

            DB::commit();

            $selected_accessories = collect($booking->selectedAccessoriesList())->pluck('name')->toArray();
            $included_accessories = collect($booking->includedAccessoriesList())->pluck('name')->toArray();

            //                $booking->user->email
            // if (!empty($request->bookingMail)) {
                sendDynamicEmail($booking->email, 'BookingConfirmationMail', [
                    'name' => $booking->first_name,
                    'email_comment' => $booking->email_comment,
                    'booking_details' => $booking->table_data,
                ]);
            // }
            if (!empty($request->invoice)) {
                sendDynamicEmail($booking->email, 'PaymentRequestMail', [
                    'name' => $booking->first_name,
                    'payment_url' => route('login'),
                ]);
            }

            return $this->sendSuccess('Booking data updated successfully');
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->sendError($exception->getMessage(), 500);
        }

    }

    public function contractPreview(Request $request)
    {
        $rowData = Booking::with(['user.userDetail', 'bike'])
            ->where('booking_id', $request->id)
            ->first();

        return view('admin.booking.contract-preview', compact('rowData'));
    }

    //    public function BookingDetail(Request $request)
    //    {
    //        try {
    //            $booking = Booking::findOrFail($request->id);
    //            $bike = Bike::findOrFail($request->bike_id);
    //
    //            $totalDays = diffInDays($booking->start_date, $booking->end_date);
    //
    //            // Calculate bike price
    //            $price = $bike->max_price;
    //
    //            if ($totalDays <= 4) {
    //                $price = $bike->less_four_days_price;
    //            } elseif ($totalDays >= 5 && $totalDays <= 6) {
    //                $price = $bike->five_six_days_price;
    //            } elseif ($totalDays == 7) {
    //                $price = $bike->week_price;
    //            } elseif ($totalDays >= 8 && $totalDays <= 30) {
    //                $price = $bike->month_price;
    //            }
    //
    //            // ---- BEGIN HTML ----
    //            $details = '<table class="table table-bordered mb-4 border-0">
    //            <tr>
    //                <td colspan="2"><h5 class="text-center fw-bold m-0">BOOKING QUOTE</h5></td>
    //            </tr>
    //            <tbody>
    //                <tr><td>Full name</td><td>'.$booking->user->first_name.' '.($booking->user->last_name ?? '').'</td></tr>
    //                <tr><td>E-mail</td><td>'.$booking->user->email.'</td></tr>
    //                <tr><td>Mobile</td><td>'.$booking->user->mobile.'</td></tr>
    //                <tr><td>Bike Name</td><td>'.$bike->name.'</td></tr>
    //                <tr><td>Total Days</td><td>'.$totalDays.'</td></tr>
    //                <tr><td>Start Date</td><td>'.date('d/m/Y', strtotime($booking->start_date)).' Pickup '.date('H:i A', strtotime($booking->start_time)).'</td></tr>
    //                <tr><td>End Date</td><td>'.date('d/m/Y', strtotime($booking->end_date)).' Drop off '.date('H:i A', strtotime($booking->end_time)).'</td></tr>
    //                <tr><td>Total Bike Price</td><td>¥'.$price.'</td></tr>
    //            </tbody>
    //
    //            <tr>
    //                <td colspan="2"><h6 class="m-0 fw-bold">Accessories & Insurance</h6></td>
    //            </tr>
    //
    //            <tbody>
    //                <tr>
    //                    <td>Insurance</td>
    //                    <td>¥'.$bike->insurance_price.'</td>
    //                </tr>
    //        ';
    //
    //    foreach ($bike->freeAccessories() as $acc) {
    //    $details .= '
    //        <tr>
    //            <td>'.$acc->name.' (Free)</td>
    //            <td>¥0</td>
    //        </tr>';
    // }
    // // EXTRA ACCESSORIES
    // foreach ($bike->extraAccessories() as $acc) {
    //
    //    $accPrice = ($acc->price > 0)
    //        ? $acc->price * ($totalDays > 0 ? $totalDays : 1)
    //        : 0;
    //
    //    $details .= '
    //        <tr>
    //            <td>'.$acc->name.'</td>
    //            <td>¥'.$accPrice.'</td>
    //        </tr>';
    // }
    //
    //            // ---- TOTAL PRICE ----
    //            $details .= '
    //            <tr class="fw-bold fs-5">
    //                <td>Total Price</td>
    //                <td>¥'.$request->price.'</td>
    //            </tr>
    //            </tbody>
    //        </table>';
    //
    //
    //            // These are accessories stored through relationships (no request)
    //            $selected_accessories = [];  // Or remove if unused
    //            $included_accessories = [];  // Or remove if unused
    //
    //            // Send booking mail
    //            Mail::to($booking->user->email)->send(
    //                new BookingMail(
    //                    $booking->user,
    //                    $booking,
    //                    $booking->bike,
    //                    $selected_accessories,
    //                    $included_accessories
    //                )
    //            );
    //
    //            // Send invoice mail if needed
    //            if (!empty($request->invoice)) {
    //                Mail::to($booking->user->email)->send(new PaymentEmail($booking->user));
    //            }
    //
    //            return $this->sendSuccess('Booking data sent successfully');
    //
    //        } catch (Exception $exception) {
    //            return $this->sendError($exception->getMessage(), 500);
    //        }
    //    }
    public function BookingDetail(Request $request)
    {
        //        dd($request->included_accessories, $request->selected_accessories);
        try {
            $booking = Booking::findOrFail($request->id);
            $bike = Bike::findOrFail($request->bike_id);

            $totalDays = totalBookingDays($booking->start_date, $booking->end_date, $booking->end_time);

            $price = $bike->max_price;
            if ($totalDays <= 4) {
                $price = $bike->less_four_days_price;
            } elseif ($totalDays >= 5 && $totalDays <= 6) {
                $price = $bike->five_six_days_price;
            } elseif ($totalDays == 7) {
                $price = $bike->week_price;
            } elseif ($totalDays >= 8 && $totalDays <= 30) {
                $price = $bike->month_price;
            }

            $details = '<table class="table table-bordered mb-4 border-0">
                    <tr>
                        <td colspan="2"><h5 class="text-center fw-bold m-0">BOOKING QUOTE</h5></td>
                    </tr>
                    <tbody>
                        <tr>
                            <td>Full name</td>
                            <td>'.($booking->first_name ?? ''.' '.($booking->last_name ?? '')).'</td>
                        </tr>
                        <tr>
                            <td>E-mail</td>
                            <td>'.$booking->email ?? ''.'</td>
                        </tr>
                        <tr>
                            <td>Mobile</td>
                            <td>'.$booking->user->mobile.'</td>
                        </tr>
                        <tr>
                            <td>Bike Name</td>
                            <td>'.$bike->name.'</td>
                        </tr>
                        <tr>
                            <td>Total Days</td>
                            <td>'.$totalDays.'</td>
                        </tr>
                        <tr>
                            <td>Start Date</td>
                            <td>'.date('d/m/Y', strtotime($request->start_date)).' Pickup '.date('H:i A', strtotime($request->start_time)).'</td>
                        </tr>
                        <tr>
                            <td>End Date</td>
                            <td>'.date('d/m/Y', strtotime($request->end_date)).' Drop off '.date('H:i A', strtotime($request->end_time)).'</td>
                        </tr>
                        <tr>
                            <td>Total Bike Price</td>
                            <td>¥'.$price.'</td>
                        </tr>
                    </tbody>
                    <tr>
                        <td colspan="2"><h6 class="m-0 fw-bold">Accessories & Insurance</h6></td>
                    </tr>
                    <tbody>
                        <tr>
                            <td>Insurance</td>
                            <td>NA</td>
                        </tr>';
            if ($request->selected_accessories) {
                foreach ($request->selected_accessories as $acc_id) {
                    $accData = Accessories::find($acc_id);
                    $accPrice = ($accData->price > 0 ? $accData->price * ($totalDays > 0 ? $totalDays : 1) : 0);
                    $details .= '
                                            <tr class="">
                                                <td>'.$accData->name.'</td>
                                                <td>¥'.$accPrice.'</td>
                                            </tr>';
                }
            }
            $details .= '
                           <tr class="fw-bold fs-5">
                                <td>Total Price</td>
                                <td>¥'.$request->price.'</td>
                           </tr>
                        </tbody>
                    </table>';

            //            dd($details);

            $selected_accessories = collect($booking->selectedAccessoriesList())->pluck('name')->toArray();
            $included_accessories = collect($booking->includedAccessoriesList())->pluck('name')->toArray();

            Mail::to($booking->user->email)->send(new BookingMail($booking->user, $booking, $booking->bike, $selected_accessories, $included_accessories));

            return $this->sendSuccess('Booking Detail Sent successfully');
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function updateCustomerPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $user = User::find($request->user_id);
            $user->password = Hash::make($request->password);
            $user->save();

            return $this->sendSuccess('Customer password updated successfully');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function setStatus(Request $request)
    {
        try {
            // Find the booking by booking_id

            $booking = Booking::where('booking_id', $request->booking_id)->first();

            // Update the status fields based on the request input (set to 1 if checked)
            $booking->send_payment_link = $request->selectedActions['send_payment_link'];
            $booking->send_booking_detail = $request->selectedActions['send_booking_detail'];
            $booking->send_login_detail = $request->selectedActions['send_login_detail'];
            $booking->send_document_verified = $request->selectedActions['send_document_verified'];

            // Save the updated booking details
            $booking->save();

            return $this->sendSuccess('Booking status updated successfully');
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    //    public function bulkSend(Request $request)
    //    {
    //
    //        $actions = $request->actions ?? [];
    //
    //        if (empty($actions)) {
    //            return response()->json([
    //                'status' => false,
    //                'message' => 'Please select at least one option.'
    //            ]);
    //        }
    //
    //        foreach ($actions as $action) {
    //
    //            if ($action === 'payment_link') {
    //                // your payment link logic
    //            }
    //
    //            if ($action === 'booking_detail') {
    //                $this->BookingDetail($request);
    //            }
    //
    //            if ($action === 'login_detail') {
    //                $this->sendLoginDetail($request);
    //            }
    //
    //            if ($action === 'document_verified') {
    //                $this->sendDocumentVerifiedMail($request);
    //            }
    //        }
    //
    //        return response()->json([
    //            'status' => true,
    //            'message' => 'Selected emails sent successfully.'
    //        ]);
    //    }
}
