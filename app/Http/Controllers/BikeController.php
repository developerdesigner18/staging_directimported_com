<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Mail\bookingsQuoteMail;
use App\Mail\RegisterMail;
use App\Models\Accessories;
use App\Models\Banner;
use App\Models\Bike;
use App\Models\BikeConfiguration;
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

class BikeController extends Controller
{
    use ResponseTrait;

    public function index()
    {

        $categoryList = Category::all();

        $limit = 8;
        $offset = 0;

        // Get all bikes. We'll sort them in memory since CC is in category name.
        //        $allBikes = Bike::all()->sort(function($a, $b) {
        //            preg_match('/(\d+)/', $a->category->name ?? '', $aMatches);
        //            preg_match('/(\d+)/', $b->category->name ?? '', $bMatches);
        //            $aCC = isset($aMatches[1]) ? (int)$aMatches[1] : 0;
        //            $bCC = isset($bMatches[1]) ? (int)$bMatches[1] : 0;
        //            return $bCC <=> $aCC; // Decreasing order
        //        });
        $allBikes = Bike::with('category')
            ->orderBy('sort_order', 'asc')
            ->get();

        $bikesGroupedByCategory = $allBikes->groupBy('category_id');

        // For initial page load, get paginated bikes
        $totalRows = count($allBikes);
        $bikesList = $allBikes->slice($offset, $limit);
        $totalPages = ceil($totalRows / $limit);

        // Map categories to CC ranges
        $ccRanges = $this->mapCategoriesToRanges($categoryList);

        $banner = Banner::first();

        return view('landing.bike.bikes', compact('categoryList', 'bikesList', 'bikesGroupedByCategory', 'limit', 'totalPages', 'ccRanges', 'banner'));
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
            $query = Bike::where('name', 'LIKE', "%$search%")
                ->whereBetween('less_four_days_price', [$min_price, $max_price]);

            // Apply range filter if not "ALL"
            if ($range !== '0' && $range !== 0) {
                $categoryIds = $this->getCategoryIdsByRange($range, $allCategories);
                if (! empty($categoryIds)) {
                    $query->whereIn('category_id', $categoryIds);
                }
            }

            //            $allbikesList = $query->get()->sort(function($a, $b) {
            //                preg_match('/(\d+)/', $a->category->name ?? '', $aMatches);
            //                preg_match('/(\d+)/', $b->category->name ?? '', $bMatches);
            //                $aCC = isset($aMatches[1]) ? (int)$aMatches[1] : 0;
            //                $bCC = isset($bMatches[1]) ? (int)$bMatches[1] : 0;
            //                return $bCC <=> $aCC; // Decreasing order
            //            });
            $allbikesList = $query->orderBy('sort_order', 'asc')->get();

            $totalRows = count($allbikesList);

            // When an engine/range filter is active, show ALL bikes (no pagination)
            if ($range !== '0' && $range !== 0) {
                $totalPages = 1;
                $bikesList = $allbikesList;
            } else {
                $totalPages = ceil($totalRows / $limit);
                $bikesList = $allbikesList->slice($offset, $limit);
            }

            $html = '';
            if (! $bikesList->isEmpty()) {
                // Group bikes by category
                $bikesGrouped = $bikesList->groupBy('category_id');

                // Get CC ranges for grouping
                $ccRanges = $this->mapCategoriesToRanges($allCategories);

                foreach ($ccRanges as $rangeName => $rangeData) {
                    $bikesInRange = collect();
                    foreach ($rangeData['category_ids'] as $catId) {
                        if (isset($bikesGrouped[$catId])) {
                            $bikesInRange = $bikesInRange->merge($bikesGrouped[$catId]);
                        }
                    }
                    $bikesInRange = $bikesInRange->sortBy('sort_order')->values();
                    if ($bikesInRange->count() > 0) {
                        // Add range section header
                        $html .= '<div class="col-12 category-section-header" id="cc" data-range="'.$rangeName.'">
                            <span class="cc-range-title">'.e($rangeName).'</span>
                        </div>';

                        // Add bikes in this range
                        foreach ($bikesInRange as $bike) {
                            $categoryName = $bike->category->name ?? 'Top Rated';
                            $description = \Illuminate\Support\Str::limit(strip_tags($bike->description), 40);
                            $imageUrl = asset(BIKE_PATH.$bike->images[0]);
                            $singleRoute = route('motorcycle.single', ['slug' => $bike->slug]);

                            $html .= '<div class="col-lg-4 col-md-6 mb-4">
                                <div class="bike-card">
                                    <div class="bike-card-img-wrapper">
                                        <a href="'.$singleRoute.'">
                                            <img src="'.$imageUrl.'" alt="'.$bike->name.'">
                                        </a>
                                    </div>
                                    <div class="bike-card-body">
                                        <h4 class="bike-title">
                                            <a href="'.$singleRoute.'">
                                                '.($bike->card_header ?? $bike->name).'
                                            </a>
                                        </h4>
                                        <div class="bike-subtitle">
                                            '.($bike->card_subtitle ?? 'Premium Adventure Touring').'
                                        </div>
                                        <div class="bike-emblem-row">
                                            <div class="emblem-item"><i class="bx bx-helmet"></i><span>Geared Up</span></div>
                                            <div class="emblem-item"><i class="bx bx-calendar"></i><span>Ready to Book</span></div>
                                            <div class="emblem-item"><i class="bx bx-gas-pump"></i><span>Adventure-Ready</span></div>
                                        </div>
                                        <div class="bike-price-block">
                                            <span class="price-from">From</span>
                                            <span class="price-amount">¥'.number_format($bike->month_price).'</span>
                                            <span class="price-per">/ Per Day</span>
                                        </div>
                                        <button class="btn-adventure btncheckout"
                                        data-slug="'.$bike->slug.'"
                                                data-id="'.$bike->id.'"
                                                data-name="'.$bike->name.'"
                                                data-insurance="'.$bike->insurance_price.'"
                                                data-image="'.$imageUrl.'">
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
                        <h4>No bikes found</h4>
                        <p>Try adjusting your search or filters</p>
                    </div>
                </div>';
            }

            return $this->sendSuccess(['data' => $html, 'pagination' => bikePagination($totalPages, $page)]);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function singleBike($slug)
    {

        $bike = Bike::with('map')->where('slug', $slug)->firstOrFail();
        $bikeConf = BikeConfiguration::get();
        $banner = Banner::first();

        // Get 3 random bikes excluding current bike
        $relatedBikes = Bike::with('category')
            ->where('id', '!=', $bike->id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('landing.bike.single', compact('bike', 'bikeConf', 'banner', 'relatedBikes'));
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

        return view('landing.bike.my_bookings', compact('accessories', 'user'));
    }

    public function myBookingsAction_old(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'bike_ids' => 'required|array',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'mobile' => 'required',
                'policy_status' => 'required',
            ], [
                'bike_ids' => 'The bike Selection is required.',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $user_id = Auth::guard('web')->user()->id;
            if ($request->bike_ids) {
                foreach ($request->bike_ids as $bike_id) {
                    $acc_bike_id = $request->acc_bike_id[$bike_id] ?? [];
                    $saveData = [
                        'user_id' => Auth::guard('web')->user()->id,
                        'booking_id' => generateBookingId(),
                        'bike_id' => $bike_id,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'start_time' => Carbon::createFromFormat('h:i A', $request->start_time)->format('H:i:s'),
                        'end_time' => Carbon::createFromFormat('h:i A', $request->end_time)->format('H:i:s'),
                        'location' => $request->location,
                        'policy_status' => $request->policy_status,
                        'comment' => $request->comment,
                        'status' => BookingStatus::PROCESSING->value,
                        'selected_accessories' => json_encode($acc_bike_id),
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
                'bike_ids' => 'required|array',
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
                'bike_ids' => 'The bike Selection is required.',
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
            $bikesaveData = [];
            $booking_ids = [];
            if ($request->bike_ids) {
                foreach ($request->bike_ids as $bike_id) {
                    $insurance = $request->acc_insurance[$bike_id] ?? 0;
                    $acc_bike_id = $request->acc_bike_id[$bike_id] ?? [];
                    $booking_id = generateBookingId();

                    $booking_ids[] = $booking_id;
                    $bikesaveData[] = [
                        'user_id' => $user_id,
                        'booking_id' => $booking_id,
                        'bike_id' => $bike_id,
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
                        'selected_accessories' => $acc_bike_id,
                        'insurance' => $insurance,
                    ];
                }
            }

            $bookings = $bikesaveData;
            $quoteDetails = view('landing.bike.quoteDetails', compact('bookings', 'booking_ids', 'request', 'user_id'))->render();

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
                'data' => $bikesaveData,
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
                $pricePerDay = $booking->bike->getTieredPrice($totalDays);
                $price = $pricePerDay; // The code below uses $price*$totalDays
                $subtotal = $price * $totalDays;
                $details .= '<table class="table table-bordered mb-4 border-0">
                    <tr>
                        <td colspan="2"><h5 class="text-center fw-bold m-0">BOOKING QUOTE #'.($key + 1).'</h5></td>
                    </tr>
                    <tbody>
                        <tr>
                            <td>Full name</td>
                            <td>'.($booking->user->first_name.' '.($booking->user->last_name ?? '')).'</td>
                        </tr>
                        <tr>
                            <td>E-mail</td>
                            <td>'.$booking->user->email.'</td>
                        </tr>
                        <tr>
                            <td>Mobile</td>
                            <td>'.$booking->user->mobile.'</td>
                        </tr>
                        <tr>
                            <td>Bike Name</td>
                            <td>'.$booking->bike->name.'</td>
                        </tr>
                        <tr>
                            <td>Total Days</td>
                            <td>'.$totalDays.'</td>
                        </tr>
                        <tr>
                            <td>Start Date</td>
                            <td>'.date('d/m/Y', strtotime($booking->start_date)).' Pickup '.date('H:i A', strtotime($booking->start_time)).'</td>
                        </tr>
                        <tr>
                            <td>End Date</td>
                            <td>'.date('d/m/Y', strtotime($booking->end_date)).' Drop off '.date('H:i A', strtotime($booking->end_time)).'</td>
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
                if ($accessories) {
                    foreach ($accessories as $key => $acc_id) {
                        $accData = Accessories::find($acc_id);
                        $accPrice = $accData->price * ($totalDays > 0 ? $totalDays : 1);
                        $subtotal += $accPrice;
                        $details .= '
                                <tr class="">
                                    <td>'.$accData->name.'</td>
                                    <td>¥'.$accPrice.'</td>
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
                            <td>¥'.number_format($tax).'</td>
                        </tr>
                        <tr>
                            <td>Card Fee 3.65%</td>
                            <td>¥'.number_format($cardFee).'</td>
                        </tr>
                       <tr class="fw-bold fs-5">
                            <td>Total Price</td>
                            <td>¥'.number_format($totalPrice).'</td>
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
            $bikebookedData = [];
            if ($request->bike_id) {
                foreach ($request->bike_id as $key => $bike_id) {
                    $acc_bike_id = (array) json_decode($request->acc_bike_id);
                    $acc_bike_id = $acc_bike_id[$bike_id] ?? [];
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
                            <td>'.($request->first_name.' '.($request->last_name ?? '')).'</td>
                        </tr>
                        <tr>
                            <td>E-mail</td>
                            <td>'.$request->email.'</td>
                        </tr>
                        <tr>
                            <td>Mobile</td>
                            <td>'.$request->mobile.'</td>
                        </tr>
                        <tr>
                            <td>Bike Name</td>
                            <td>'.$request->bike_name[$key].'</td>
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
                            <td>¥'.$request->price[$key].'</td>
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
                    if ($acc_bike_id) {
                        foreach ($acc_bike_id as $acc_id) {
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
                                <td>¥'.$request->totalPrice[$key].'</td>
                           </tr>
                        </tbody>
                    </table>';

                    $saveData = [
                        'user_id' => $user_id,
                        'booking_id' => $request->booking_id[$key],
                        'bike_id' => $bike_id,
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
                        'selected_accessories' => $acc_bike_id ?? [],
                        'included_accessories' => json_decode($request->included_accessories[$key]) ?? null,
                        'price' => $request->totalPrice[$key],
                        'table_data' => $details,
                        'insurance_price' => $insurance_price,
                        'insurance' => $insurance ?? 0,
                    ];
                    Booking::create($saveData);
                    // dd($saveData);
                    $bikebookedData[] = [
                        'user_id' => $user_id,
                        'booking_id' => $request->booking_id[$key],
                        'bike_id' => $bike_id,
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
                        'selected_accessories' => $acc_bike_id,
                        'insurance_price' => $insurance_price,
                        'insurance' => $insurance ?? 0,
                    ];

                }
            }
            DB::commit();
            // env('RECEIVER_MAIL')
            Mail::to(env('RECEIVER_MAIL'))->send(new bookingsQuoteMail($user_id, $bikebookedData));

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
            $bikebookedData = [];

            if ($request->bike_id) {

                $acc_bike_id_all = json_decode($request->acc_bike_id, true) ?? [];

                foreach ($request->bike_id as $key => $bike_id) {

                    $acc_bike_id = $acc_bike_id_all[$bike_id] ?? [];
                    $totalDays = $request->total_days[$key];
                    $insurance_price = $request->insurance_price[$key];
                    $insurance = $request->insurance[$key];

                    // ✅ NEW: subtotal calculation
                    $subtotal = $request->price[$key];

                    // Build accessories rows
                    $accessoryRows = '';
                    if ($acc_bike_id) {
                        foreach ($acc_bike_id as $acc_id) {
                            $accData = Accessories::find($acc_id);
                            if (! $accData) {
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
                            <td>'.e($accData->name).'</td>
                            <td>¥'.number_format($accPrice).'</td>
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
                        <tr><td>Full name</td><td>'.e($request->first_name.' '.($request->last_name ?? '')).'</td></tr>
                        <tr><td>E-mail</td><td>'.e($request->email).'</td></tr>
                        <tr><td>Mobile</td><td>'.e($request->mobile).'</td></tr>
                        <tr><td>Bike Name</td><td>'.e($request->bike_name[$key]).'</td></tr>
                        <tr><td>Total Days</td><td>'.$totalDays.'</td></tr>
                        <tr>
                            <td>Start Date</td>
                            <td>'.date('d/m/Y', strtotime($request->start_date)).' Pickup '.date('h:i A', strtotime($request->start_time)).'</td>
                        </tr>
                        <tr>
                            <td>End Date</td>
                            <td>'.date('d/m/Y', strtotime($request->end_date)).' Drop off '.date('h:i A', strtotime($request->end_time)).'</td>
                        </tr>
                        <tr><td>Total Bike Price</td><td>¥'.$request->price[$key].'</td></tr>
                    </tbody>
                    <tr>
                        <td colspan="2"><h6 class="m-0 fw-bold">Accessories & Insurance</h6></td>
                    </tr>
                    <tbody>
                        <tr>
                            <td>Insurance</td>
                            <td>'.(($insurance_price > 0) ? '¥'.$insurance_price : 'NA').'</td>
                        </tr>
                        '.$accessoryRows.'

                        <!-- ✅ NEW -->
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
                            <td>¥'.number_format($totalPrice).'</td>
                        </tr>
                    </tbody>
                </table>';

                    $startTimeParsed = Carbon::parse($request->start_time)->format('H:i:s');
                    $endTimeParsed = Carbon::parse($request->end_time)->format('H:i:s');

                    $saveData = [
                        'user_id' => $user_id,
                        'booking_id' => $request->booking_id[$key],
                        'bike_id' => $bike_id,
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
                        'selected_accessories' => $acc_bike_id,
                        'included_accessories' => json_decode($request->included_accessories[$key]) ?? null,

                        // ✅ SAVE CORRECT TOTAL
                        'price' => $totalPrice,

                        'table_data' => $details,
                        'insurance_price' => $insurance_price,
                        'insurance' => $insurance ?? 0,
                    ];

                    Booking::create($saveData);

                    $bikebookedData[] = [
                        'user_id' => $user_id,
                        'booking_id' => $request->booking_id[$key],
                        'bike_id' => $bike_id,
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
                        'selected_accessories' => $acc_bike_id,
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

    public function bikeAccessories(Request $request)
    {
        $bike = Bike::find($request->bike_id);
        $freeAccessories = $bike ? $bike->freeAccessories() : collect();

        if ($freeAccessories->isEmpty()) {
            return $this->sendSuccess([]);
        }

        return $this->sendSuccess($freeAccessories);
    }

    public function getExtraAccessories(Request $request)
    {
        // 1️⃣ Get the bike by ID
        $bike = Bike::find($request->bike_id);

        // 2️⃣ Get only the extra accessories for this bike
        $extra = $bike ? $bike->extraAccessories() : collect();

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
