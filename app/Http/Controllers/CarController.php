<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Mail\bookingsQuoteMail;
use App\Mail\RegisterMail;
use App\Models\Accessories;
use App\Models\Banner;
use App\Models\Car;
use App\Models\CarConfiguration;
use App\Models\Booking;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Enum\BookingStatus;

class CarController extends Controller
{
    use ResponseTrait;

    public function index()
    {

        $categoryList = Category::all();

        $limit = 8;
        $offset = 0;

        // Get all cars. We'll sort them in memory since CC is in category name.
        //        $allCars = Car::all()->sort(function($a, $b) {
        //            preg_match('/(\d+)/', $a->category->name ?? '', $aMatches);
        //            preg_match('/(\d+)/', $b->category->name ?? '', $bMatches);
        //            $aCC = isset($aMatches[1]) ? (int)$aMatches[1] : 0;
        //            $bCC = isset($bMatches[1]) ? (int)$bMatches[1] : 0;
        //            return $bCC <=> $aCC; // Decreasing order
        //        });
        $allCars = Car::with('category')
            ->orderBy('sort_order', 'asc')
            ->get();

        $carsGroupedByCategory = $allCars->groupBy('category_id');

        // For initial page load, get paginated cars
        $totalRows = count($allCars);
        $carsList = $allCars->slice($offset, $limit);
        $totalPages = ceil($totalRows / $limit);

        // Map categories to CC ranges
        $ccRanges = $this->mapCategoriesToRanges($categoryList);

        $banner = Banner::first();

        return view('landing.car.cars', compact('categoryList', 'carsList', 'carsGroupedByCategory', 'limit', 'totalPages', 'ccRanges', 'banner'));
    }

    public function pagination(Request $request)
    {
        try {
            $min_price = $request->min_price ?? '0';
            $max_price = $request->max_price ?? '100000';
            $search = $request->search ?? '';
            $range = $request->range ?? '0'; // Changed from category to range
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 1;
            $offset = ($page - 1) * $limit;

            // Get all categories for range mapping
            $allCategories = Category::all();

            // Build query
            $query = Car::where('name', 'LIKE', "%$search%")
                ->whereBetween('less_four_days_price', [$min_price, $max_price]);

            // Apply range filter if not "ALL"
            if ($range !== '0' && $range !== 0) {
                $categoryIds = $this->getCategoryIdsByRange($range, $allCategories);
                if (!empty($categoryIds)) {
                    $query->whereIn('category_id', $categoryIds);
                }
            }

            //            $allcarsList = $query->get()->sort(function($a, $b) {
            //                preg_match('/(\d+)/', $a->category->name ?? '', $aMatches);
            //                preg_match('/(\d+)/', $b->category->name ?? '', $bMatches);
            //                $aCC = isset($aMatches[1]) ? (int)$aMatches[1] : 0;
            //                $bCC = isset($bMatches[1]) ? (int)$bMatches[1] : 0;
            //                return $bCC <=> $aCC; // Decreasing order
            //            });
            $allcarsList = $query->orderBy('sort_order', 'asc')->get();

            $totalRows = count($allcarsList);

            // When an engine/range filter is active, show ALL cars (no pagination)
            if ($range !== '0' && $range !== 0) {
                $totalPages = 1;
                $carsList = $allcarsList;
            } else {
                $totalPages = ceil($totalRows / $limit);
                $carsList = $allcarsList->slice($offset, $limit);
            }

            $html = '';
            if (!$carsList->isEmpty()) {
                // Group cars by category
                $carsGrouped = $carsList->groupBy('category_id');

                // Get CC ranges for grouping
                $ccRanges = $this->mapCategoriesToRanges($allCategories);

                foreach ($ccRanges as $rangeName => $rangeData) {
                    $carsInRange = collect();
                    foreach ($rangeData['category_ids'] as $catId) {
                        if (isset($carsGrouped[$catId])) {
                            $carsInRange = $carsInRange->merge($carsGrouped[$catId]);
                        }
                    }
                    $carsInRange = $carsInRange->sortBy('sort_order')->values();
                    if ($carsInRange->count() > 0) {
                        // Add range section header
                        $html .= '<div class="col-12 category-section-header" id="cc" data-range="' . $rangeName . '">
                            <span class="cc-range-title">' . e($rangeName) . '</span>
                        </div>';

                        // Add cars in this range
                        foreach ($carsInRange as $car) {
                            $categoryName = $car->category->name ?? 'Top Rated';
                            $description = \Illuminate\Support\Str::limit(strip_tags($car->description), 40);
                            $imageUrl = asset(CAR_PATH . $car->images[0]);
                            $singleRoute = route('motorcycle.single', ['slug' => $car->slug]);

                            $html .= '<div class="col-lg-4 col-md-6 mb-4">
                                <div class="car-card">
                                    <div class="car-card-img-wrapper">
                                        <a href="' . $singleRoute . '">
                                            <img src="' . $imageUrl . '" alt="' . $car->name . '">
                                        </a>
                                    </div>
                                    <div class="car-card-body">
                                        <h4 class="car-title">
                                            <a href="' . $singleRoute . '">
                                                ' . ($car->card_header ?? $car->name) . '
                                            </a>
                                        </h4>
                                        <div class="car-subtitle">
                                            ' . ($car->card_subtitle ?? 'Premium Adventure Touring') . '
                                        </div>
                                        <div class="car-emblem-row">
                                            <div class="emblem-item"><i class="bx bx-helmet"></i><span>Geared Up</span></div>
                                            <div class="emblem-item"><i class="bx bx-calendar"></i><span>Ready to Book</span></div>
                                            <div class="emblem-item"><i class="bx bx-gas-pump"></i><span>Adventure-Ready</span></div>
                                        </div>
                                        <div class="car-price-block">
                                            <span class="price-from">From</span>
                                            <span class="price-amount">¥' . number_format($car->month_price) . '</span>
                                            <span class="price-per">/ Per Day</span>
                                        </div>
                                        <button class="btn-adventure btncheckout"
                                        data-slug="' . $car->slug . '"
                                                data-id="' . $car->id . '"
                                                data-name="' . $car->name . '"
                                                data-insurance="' . $car->insurance_price . '"
                                                data-image="' . $imageUrl . '">
                                            CHECK IT OUT
                                        </button>
                                    </div>
                                </div>
                            </div>';
                        }
                    }
                }
            } else {
                $html .= '<div class="col-12">
                    <div class="no-results">
                        <h4>No cars found</h4>
                        <p>Try adjusting your search or filters</p>
                    </div>
                </div>';
            }

            return $this->sendSuccess(['data' => $html, 'pagination' => carPagination($totalPages, $page)]);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function singleCar($slug)
    {

        $car = Car::with(['map', 'spec'])->where('slug', $slug)->firstOrFail();
        $carConf = CarConfiguration::get();
        $banner = Banner::first();

        // Get 3 random cars excluding current car
        $relatedCars = Car::with('category')
            ->where('id', '!=', $car->id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('landing.car.single', compact('car', 'carConf', 'banner', 'relatedCars'));
    }

    public function requestQuote(Request $request)
    {
        try {
            $ids = Cache::get('requestQuote');
            $ids[] = $request->id;
            $val = array_unique($ids);
            Cache::put('requestQuote', $val);

            return $this->sendSuccess('Request Quote successfully!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function myBookings()
    {
        $user = Auth::guard('web')->user();
        $accessories = Accessories::all();

        return view('landing.car.my_bookings', compact('accessories', 'user'));
    }

    public function myBookingsAction_old(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'car_ids' => 'required|array',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'mobile' => 'required',
                'policy_status' => 'required',
            ], [
                'car_ids' => 'The car Selection is required.',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $user_id = Auth::guard('web')->user()->id;
            if ($request->car_ids) {
                foreach ($request->car_ids as $car_id) {
                    $acc_car_id = $request->acc_car_id[$car_id] ?? [];
                    $saveData = [
                        'user_id' => Auth::guard('web')->user()->id,
                        'booking_id' => generateBookingId(),
                        'car_id' => $car_id,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'start_time' => Carbon::createFromFormat('h:i A', $request->start_time)->format('H:i:s'),
                        'end_time' => Carbon::createFromFormat('h:i A', $request->end_time)->format('H:i:s'),
                        'location' => $request->location,
                        'policy_status' => $request->policy_status,
                        'comment' => $request->comment,
                        'status' => BookingStatus::PROCESSING->value,
                        'selected_accessories' => json_encode($acc_car_id),
                    ];
                    Booking::create($saveData);
                }
            }
            Mail::to(env('RECEIVER_MAIL'))->send(new bookingsQuoteMail($user_id));
            DB::commit();

            return $this->sendSuccess('Quote Generated successfully!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function myBookingsAction(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'car_ids' => 'required|array',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'mobile' => 'required',
                //                'start_time' => 'required|date_format:h:i A',
                //                'end_time' => 'required|date_format:h:i A',
                'start_time' => 'required',
                'end_time' => 'required',
                'policy_status' => 'required',
            ], [
                'car_ids' => 'The car Selection is required.',
                'start_time.required' => 'Please enter the start time.',
                'start_time.date_format' => 'Start time must be in the format HH:MM AM/PM.',
                //                'end_time.required' => 'Please enter the end time.',
                //                'end_time.date_format' => 'End time must be in the format HH:MM AM/PM.',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $user_id = Auth::guard('web')->user()->id ?? null;

            //            dd($user_id);
            $carsaveData = [];
            $booking_ids = [];
            if ($request->car_ids) {
                foreach ($request->car_ids as $car_id) {
                    $insurance = $request->acc_insurance[$car_id] ?? 0;
                    $acc_car_id = $request->acc_car_id[$car_id] ?? [];
                    $booking_id = generateBookingId();

                    $booking_ids[] = $booking_id;
                    $carsaveData[] = [
                        'user_id' => $user_id,
                        'booking_id' => $booking_id,
                        'car_id' => $car_id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'mobile' => $request->mobile,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'start_time' => Carbon::createFromFormat('h:i A', $request->start_time)->format('H:i:s'),
                        'end_time' => Carbon::createFromFormat('h:i A', $request->end_time)->format('H:i:s'),
                        'location' => $request->location,
                        'policy_status' => $request->policy_status,
                        'comment' => $request->comment,
                        //                        'status' => 'PENDING',
                        'selected_accessories' => $acc_car_id,
                        'insurance' => $insurance,
                    ];
                }
            }

            $bookings = $carsaveData;
            $quoteDetails = view('landing.car.quoteDetails', compact('bookings', 'booking_ids', 'request', 'user_id'))->render();

            // Send email to admin
            sendDynamicEmail(env('RECEIVER_MAIL'), 'BookingQuoteMail', [
                'name' => 'Admin',
                'booking_details' => $quoteDetails,
            ]);

            // Send email to user
            sendDynamicEmail($request->email, 'BookingQuoteMail', [
                'name' => $request->first_name,
                'booking_details' => $quoteDetails,
            ]);
            //            if (!Auth::guard('web')->check()) {
            //                $existingUser = User::where('email', $request->email)->first();
            //                if ($existingUser) {
            //                    return $this->sendError('You are already registered. Please login to continue.', 400);
            //                }
            //
            //                $user = new User();
            //                $user->first_name = $request->first_name;
            //                $user->last_name = $request->last_name;
            //                $user->mobile = $request->mobile;
            //                $user->email = $request->email;
            //                $user->password = Hash::make($booking_id); // Or temp password logic
            //                $user->save();
            //                Mail::to($user->email)->send(new RegisterMail($user->first_name,$user->last_name,$user->email,$user->mobile));
            //
            //                Auth::guard('web')->attempt([
            //                    'email' => $user->email,
            //                    'password' => $booking_id
            //                ]);
            //            }
            DB::commit();

            return $this->sendSuccess([
                'data' => $carsaveData,
                'booking_ids' => $booking_ids,
                'html' => $quoteDetails,
            ]);

        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function bookingsQuoteDetails(Request $request)
    {
        try {
            $bookings = Booking::where(['user_id' => Auth::guard('web')->user()->id, 'status' => BookingStatus::PROCESSING->value])->get();

            $details = '';
            foreach ($bookings as $key => $booking) {
                $accessories = json_decode($booking->selected_accessories) ?? [];
                $totalDays = $booking->totalDays();
                $pricePerDay = $booking->car->getTieredPrice($totalDays);
                $price = $pricePerDay; // The code below uses $price*$totalDays
                $subtotal = $price * $totalDays;
                $details .= '<table class="table table-bordered mb-4 border-0">
                    <tr>
                        <td colspan="2"><h5 class="text-center fw-bold m-0">BOOKING QUOTE #' . ($key + 1) . '</h5></td>
                    </tr>
                    <tbody>
                        <tr>
                            <td>Full name</td>
                            <td>' . ($booking->user->first_name . ' ' . ($booking->user->last_name ?? '')) . '</td>
                        </tr>
                        <tr>
                            <td>E-mail</td>
                            <td>' . $booking->user->email . '</td>
                        </tr>
                        <tr>
                            <td>Mobile</td>
                            <td>' . $booking->user->mobile . '</td>
                        </tr>
                        <tr>
                            <td>Car Name</td>
                            <td>' . $booking->car->name . '</td>
                        </tr>
                        <tr>
                            <td>Total Days</td>
                            <td>' . $totalDays . '</td>
                        </tr>
                        <tr>
                            <td>Start Date</td>
                            <td>' . date('d/m/Y', strtotime($booking->start_date)) . ' Pickup ' . date('H:i A', strtotime($booking->start_time)) . '</td>
                        </tr>
                        <tr>
                            <td>End Date</td>
                            <td>' . date('d/m/Y', strtotime($booking->end_date)) . ' Drop off ' . date('H:i A', strtotime($booking->end_time)) . '</td>
                        </tr>
                        <tr>
                            <td>Total Car Price</td>
                            <td>¥' . $price . '</td>
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
                if ($accessories) {
                    foreach ($accessories as $key => $acc_id) {
                        $accData = Accessories::find($acc_id);
                        $accPrice = $accData->price * ($totalDays > 0 ? $totalDays : 1);
                        $subtotal += $accPrice;
                        $details .= '
                                <tr class="">
                                    <td>' . $accData->name . '</td>
                                    <td>¥' . $accPrice . '</td>
                                </tr>';
                    }
                }

                // Calculate TAX and Card Fee
                $tax = round($subtotal * 0.10);
                $cardFee = round(($subtotal + $tax) * 0.0365);
                $totalPrice = $subtotal + $tax + $cardFee;

                $details .= '
                        <tr>
                            <td>TAX 10%</td>
                            <td>¥' . number_format($tax) . '</td>
                        </tr>
                        <tr>
                            <td>Card Fee 3.65%</td>
                            <td>¥' . number_format($cardFee) . '</td>
                        </tr>
                       <tr class="fw-bold fs-5">
                            <td>Total Price</td>
                            <td>¥' . number_format($totalPrice) . '</td>
                       </tr>
                    </tbody>
                </table>';
            }

            return $this->sendSuccess($details);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function bookingProcessingOld(Request $request)
    {

        // *
        //        $user_id = Auth::guard('web')->user()->id ;
        $user_id = Auth::guard('web')->user()->id ?? null;

        try {
            $carbookedData = [];
            if ($request->car_id) {
                foreach ($request->car_id as $key => $car_id) {
                    $acc_car_id = (array) json_decode($request->acc_car_id);
                    $acc_car_id = $acc_car_id[$car_id] ?? [];
                    $totalDays = $request->total_days[$key];
                    $insurance_price = $request->insurance_price[$key];
                    $insurance = $request->insurance[$key];
                    $details = '<table class="table table-bordered mb-4 border-0">
                    <tr>
                        <td colspan="2"><h5 class="text-center fw-bold m-0">BOOKING QUOTE</h5></td>
                    </tr>
                    <tbody>
                        <tr>
                            <td>Full name</td>
                            <td>' . ($request->first_name . ' ' . ($request->last_name ?? '')) . '</td>
                        </tr>
                        <tr>
                            <td>E-mail</td>
                            <td>' . $request->email . '</td>
                        </tr>
                        <tr>
                            <td>Mobile</td>
                            <td>' . $request->mobile . '</td>
                        </tr>
                        <tr>
                            <td>Car Name</td>
                            <td>' . $request->car_name[$key] . '</td>
                        </tr>
                        <tr>
                            <td>Total Days</td>
                            <td>' . $totalDays . '</td>
                        </tr>
                        <tr>
                            <td>Start Date</td>
                            <td>' . date('d/m/Y', strtotime($request->start_date)) . ' Pickup ' . date('H:i A', strtotime($request->start_time)) . '</td>
                        </tr>
                        <tr>
                            <td>End Date</td>
                            <td>' . date('d/m/Y', strtotime($request->end_date)) . ' Drop off ' . date('H:i A', strtotime($request->end_time)) . '</td>
                        </tr>
                        <tr>
                            <td>Total Car Price</td>
                            <td>¥' . $request->price[$key] . '</td>
                        </tr>
                    </tbody>
                    <tr>
                        <td colspan="2"><h6 class="m-0 fw-bold">Accessories & Insurance</h6></td>
                    </tr>
                    <tbody>
                        <tr>
                            <td>Insurance</td>
                            <td>' . (($insurance_price > 0) ? '¥' . $insurance_price : 'NA') . '</td>
                        </tr>';
                    if ($acc_car_id) {
                        foreach ($acc_car_id as $acc_id) {
                            $accData = Accessories::find($acc_id);
                            $accPrice = ($accData->price > 0 ? $accData->price * ($totalDays > 0 ? $totalDays : 1) : 0);
                            $details .= '
                                <tr class="">
                                    <td>' . $accData->name . '</td>
                                    <td>¥' . $accPrice . '</td>
                                </tr>';
                        }
                    }
                    $details .= '
                           <tr class="fw-bold fs-5">
                                <td>Total Price</td>
                                <td>¥' . $request->totalPrice[$key] . '</td>
                           </tr>
                        </tbody>
                    </table>';

                    $saveData = [
                        'user_id' => $user_id,
                        'booking_id' => $request->booking_id[$key],
                        'car_id' => $car_id,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'start_time' => Carbon::createFromFormat('h:i A', $request->start_time)->format('H:i:s'),
                        'end_time' => Carbon::createFromFormat('h:i A', $request->end_time)->format('H:i:s'),
                        'location' => $request->location,
                        'policy_status' => '1',
                        'comment' => $request->comment,
                        //                        'status' => 'PROCESSING',
                        'selected_accessories' => $acc_car_id ?? [],
                        'included_accessories' => json_decode($request->included_accessories[$key]) ?? null,
                        'price' => $request->totalPrice[$key],
                        'table_data' => $details,
                        'insurance_price' => $insurance_price,
                        'insurance' => $insurance ?? 0,
                    ];
                    Booking::create($saveData);
                    // dd($saveData);
                    $carbookedData[] = [
                        'user_id' => $user_id,
                        'booking_id' => $request->booking_id[$key],
                        'car_id' => $car_id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'mobile' => $request->mobile,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'start_time' => Carbon::createFromFormat('h:i A', $request->start_time)->format('H:i:s'),
                        'end_time' => Carbon::createFromFormat('h:i A', $request->end_time)->format('H:i:s'),
                        'location' => $request->location,
                        'policy_status' => '1',
                        'comment' => $request->comment,
                        //                        'status' => 'PROCESSING',
                        'selected_accessories' => $acc_car_id,
                        'insurance_price' => $insurance_price,
                        'insurance' => $insurance ?? 0,
                    ];

                }
            }
            DB::commit();
            // env('RECEIVER_MAIL')
            Mail::to(env('RECEIVER_MAIL'))->send(new bookingsQuoteMail($user_id, $carbookedData));

            return $this->sendSuccess('Quote Booked successfully!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function bookingProcessing(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id ?? null;

        DB::beginTransaction();

        try {
            $carbookedData = [];

            if ($request->car_id) {

                $acc_car_id_all = json_decode($request->acc_car_id, true) ?? [];

                foreach ($request->car_id as $key => $car_id) {

                    $acc_car_id = $acc_car_id_all[$car_id] ?? [];
                    $totalDays = $request->total_days[$key];
                    $insurance_price = $request->insurance_price[$key];
                    $insurance = $request->insurance[$key];

                    // ✅ NEW: subtotal calculation
                    $subtotal = $request->price[$key];

                    // Build accessories rows
                    $accessoryRows = '';
                    if ($acc_car_id) {
                        foreach ($acc_car_id as $acc_id) {
                            $accData = Accessories::find($acc_id);
                            if (!$accData) {
                                continue;
                            }

                            if ($totalDays > 1 && $accData->additional_day_price) {
                                $accPrice = $accData->price + ($accData->additional_day_price * ($totalDays - 1));
                            } else {
                                $accPrice = $accData->price * ($totalDays > 0 ? $totalDays : 1);
                            }

                            if (Str::contains(strtolower($accData->name), 'helmet') && $accPrice >= 6500) {
                                $accPrice = 6500;
                            }

                            // ✅ ADD to subtotal
                            $subtotal += $accPrice;

                            $accessoryRows .= '
                        <tr>
                            <td>' . e($accData->name) . '</td>
                            <td>¥' . number_format($accPrice) . '</td>
                        </tr>';
                        }
                    }

                    // ✅ ADD insurance
                    $subtotal += $insurance_price;

                    // ✅ TAX + CARD
                    $tax = round($subtotal * 0.10);
                    $cardFee = round(($subtotal + $tax) * 0.0365);
                    $totalPrice = $subtotal + $tax + $cardFee;

                    $details = '
                <table class="table table-bordered mb-4 border-0">
                    <tr>
                        <td colspan="2"><h5 class="text-center fw-bold m-0">BOOKING QUOTE</h5></td>
                    </tr>
                    <tbody>
                        <tr><td>Full name</td><td>' . e($request->first_name . ' ' . ($request->last_name ?? '')) . '</td></tr>
                        <tr><td>E-mail</td><td>' . e($request->email) . '</td></tr>
                        <tr><td>Mobile</td><td>' . e($request->mobile) . '</td></tr>
                        <tr><td>Car Name</td><td>' . e($request->car_name[$key]) . '</td></tr>
                        <tr><td>Total Days</td><td>' . $totalDays . '</td></tr>
                        <tr>
                            <td>Start Date</td>
                            <td>' . date('d/m/Y', strtotime($request->start_date)) . ' Pickup ' . date('h:i A', strtotime($request->start_time)) . '</td>
                        </tr>
                        <tr>
                            <td>End Date</td>
                            <td>' . date('d/m/Y', strtotime($request->end_date)) . ' Drop off ' . date('h:i A', strtotime($request->end_time)) . '</td>
                        </tr>
                        <tr><td>Total Car Price</td><td>¥' . $request->price[$key] . '</td></tr>
                    </tbody>
                    <tr>
                        <td colspan="2"><h6 class="m-0 fw-bold">Accessories & Insurance</h6></td>
                    </tr>
                    <tbody>
                        <tr>
                            <td>Insurance</td>
                            <td>' . (($insurance_price > 0) ? '¥' . $insurance_price : 'NA') . '</td>
                        </tr>
                        ' . $accessoryRows . '

                        <!-- ✅ NEW -->
                        <tr>
                            <td>TAX 10%</td>
                            <td>¥' . number_format($tax) . '</td>
                        </tr>
                        <tr>
                            <td>Card Fee 3.65%</td>
                            <td>¥' . number_format($cardFee) . '</td>
                        </tr>

                        <tr class="fw-bold fs-5">
                            <td>Total Price</td>
                            <td>¥' . number_format($totalPrice) . '</td>
                        </tr>
                    </tbody>
                </table>';

                    $startTimeParsed = Carbon::parse($request->start_time)->format('H:i:s');
                    $endTimeParsed = Carbon::parse($request->end_time)->format('H:i:s');

                    $saveData = [
                        'user_id' => $user_id,
                        'booking_id' => $request->booking_id[$key],
                        'car_id' => $car_id,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'total_days' => $totalDays,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'start_time' => $startTimeParsed,
                        'end_time' => $endTimeParsed,
                        'location' => $request->location,
                        'policy_status' => '1',
                        'comment' => $request->comment,
                        'selected_accessories' => $acc_car_id,
                        'included_accessories' => json_decode($request->included_accessories[$key]) ?? null,

                        // ✅ SAVE CORRECT TOTAL
                        'price' => $totalPrice,

                        'table_data' => $details,
                        'insurance_price' => $insurance_price,
                        'insurance' => $insurance ?? 0,
                    ];

                    Booking::create($saveData);

                    $carbookedData[] = [
                        'user_id' => $user_id,
                        'booking_id' => $request->booking_id[$key],
                        'car_id' => $car_id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'mobile' => $request->mobile,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'total_days' => $totalDays,
                        'start_time' => $startTimeParsed,
                        'end_time' => $endTimeParsed,
                        'location' => $request->location,
                        'policy_status' => '1',
                        'comment' => $request->comment,
                        'selected_accessories' => $acc_car_id,
                        'insurance_price' => $insurance_price,
                        'insurance' => $insurance ?? 0,
                    ];
                }
            }

            DB::commit();

            // Send email to admin
            sendDynamicEmail(env('RECEIVER_MAIL'), 'BookingQuoteMail', [
                'name' => 'Admin',
                'booking_details' => '',
            ]);

            // Send email to user
            sendDynamicEmail($request->email, 'BookingQuoteMail', [
                'name' => $request->first_name,
                'booking_details' => '',
            ]);

            return $this->sendSuccess('Your Booking Request has been sent to our Team and you will recieve a email confirmation in 24hrs');

        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function carAccessories(Request $request)
    {
        $car = Car::find($request->car_id);
        $freeAccessories = $car ? $car->freeAccessories() : collect();

        if ($freeAccessories->isEmpty()) {
            return $this->sendSuccess([]);
        }

        return $this->sendSuccess($freeAccessories);
    }

    public function getExtraAccessories(Request $request)
    {
        // 1️⃣ Get the car by ID
        $car = Car::find($request->car_id);

        // 2️⃣ Get only the extra accessories for this car
        $extra = $car ? $car->extraAccessories() : collect();

        // 3️⃣ Check if empty
        if ($extra->isEmpty()) {
            return response()->json([
                'status' => true,
                'accessories' => [],
                'message' => 'No Extra Accessories Found!',
            ]);
        }

        // 4️⃣ Return as JSON
        return response()->json([
            'status' => true,
            'accessories' => $extra,
        ]);
    }

    /**
     * Get CC range definitions
     */
    private function getCCRanges()
    {
        return [
            '750-1300cc' => ['min' => 750, 'max' => 1300],
            '400-700cc' => ['min' => 400, 'max' => 700],
            '150-350cc' => ['min' => 150, 'max' => 350],
            '0-125cc' => ['min' => 0, 'max' => 125],
        ];
    }

    /**
     * Map categories to their CC ranges
     */
    private function mapCategoriesToRanges($categories)
    {
        $ranges = $this->getCCRanges();
        $rangeMap = [];

        foreach ($ranges as $rangeName => $rangeLimits) {
            $rangeMap[$rangeName] = [
                'name' => $rangeName,
                'categories' => [],
                'category_ids' => [],
            ];
        }

        foreach ($categories as $category) {
            // Extract numeric value from category name (e.g., "125cc" -> 125)
            preg_match('/(\d+)/', $category->name, $matches);
            $ccValue = isset($matches[1]) ? (int) $matches[1] : 0;

            // Find which range this category belongs to
            foreach ($ranges as $rangeName => $rangeLimits) {
                if ($ccValue >= $rangeLimits['min'] && $ccValue <= $rangeLimits['max']) {
                    $rangeMap[$rangeName]['categories'][] = $category;
                    $rangeMap[$rangeName]['category_ids'][] = $category->id;
                    break;
                }
            }
        }

        return $rangeMap;
    }

    /**
     * Get category IDs for a specific range
     */
    private function getCategoryIdsByRange($range, $categories)
    {
        if ($range === '0' || $range === 0) {
            return []; // ALL - no filter
        }

        $rangeMap = $this->mapCategoriesToRanges($categories);

        return $rangeMap[$range]['category_ids'] ?? [];
    }
}
