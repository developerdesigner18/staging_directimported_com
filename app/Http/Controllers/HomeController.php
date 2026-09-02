<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Mail\ContactFormMail;
use App\Models\Car;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\HeroSlider;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\Color;
use League\HTMLToMarkdown\HtmlConverter;
use App\Models\EmailTemplates;
use App\Models\RentalPolicies;
use App\Models\HomeSection;

class HomeController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $sliders = HeroSlider::orderBy('created_at', 'asc')->get();
        $gallery = Gallery::orderBy('created_at', 'desc')->get();
        $faqs = Faq::orderBy('created_at', 'desc')->get();
        $services = Service::orderBy('created_at', 'desc')->limit(3)->get();

        $cars = Car::orderBy('created_at', 'desc')->limit(8)->get();
        $color = Color::first();
        $homeSection = HomeSection::with('points')->first();

        $array1 = $sliders->map(function ($item) {
            return $item->image;
        });
        $array2 = $sliders->take(2)->map(function ($item) {
            return $item->image;
        });
        $sliderImages = json_encode([...$array1, ...$array2]);
        return view('landing.pages.index', compact('sliders', 'gallery', 'cars', 'sliderImages', 'color', 'faqs', 'services', 'homeSection'));
    }

    private function buildVehicleQuery(?Request $request = null)
    {
        $query = Car::with(['category', 'spec', 'manufacturer', 'auctionGrade'])
            ->orderBy('created_at', 'desc');

        if (!$request) {
            return $query;
        }

        if ($request->filled('maker')) {
            $maker = trim($request->maker);
            $query->where(function ($q) use ($maker) {
                $q->whereHas('manufacturer', function ($m) use ($maker) {
                    $m->where('name', $maker)->orWhere('id', $maker);
                })->orWhereHas('spec', function ($s) use ($maker) {
                    $s->where('make', $maker);
                })->orWhere('manufacturer_id', $maker);
            });
        }

        if ($request->filled('car_name')) {
            $carName = trim($request->car_name);
            $query->where(function ($q) use ($carName) {
                $q->where('name', 'like', '%' . $carName . '%')
                    ->orWhere('model', 'like', '%' . $carName . '%')
                    ->orWhere('card_header', 'like', '%' . $carName . '%')
                    ->orWhere('card_subtitle', 'like', '%' . $carName . '%')
                    ->orWhere('slug', 'like', '%' . $carName . '%')
                    ->orWhereHas('manufacturer', function ($m) use ($carName) {
                        $m->where('name', 'like', '%' . $carName . '%');
                    })
                    ->orWhereHas('spec', function ($s) use ($carName) {
                        $s->where('make', 'like', '%' . $carName . '%')
                            ->orWhere('type', 'like', '%' . $carName . '%')
                            ->orWhere('engine', 'like', '%' . $carName . '%')
                            ->orWhere('body_type', 'like', '%' . $carName . '%');
                    });
            });
        }

        if ($request->filled('type')) {
            $type = trim($request->type);
            $query->where(function ($q) use ($type) {
                $q->whereHas('spec', function ($s) use ($type) {
                    $s->where('type', 'like', '%' . $type . '%')
                        ->orWhere('body_type', 'like', '%' . $type . '%');
                })->orWhereHas('category', function ($c) use ($type) {
                    $c->where('name', 'like', '%' . $type . '%');
                })->orWhere('name', 'like', '%' . $type . '%')
                    ->orWhere('card_header', 'like', '%' . $type . '%');
            });
        }

        if ($request->filled('year_min') || $request->filled('year_max')) {
            $yMin = $request->filled('year_min') ? (int) $request->year_min : 0;
            $yMax = $request->filled('year_max') ? (int) $request->year_max : 9999;
            $query->where(function ($q) use ($yMin, $yMax) {
                $q->where(function ($subQ) use ($yMin, $yMax) {
                    $subQ->whereNotNull('year')
                        ->where('year', '!=', '')
                        ->whereBetween('year', [$yMin, $yMax]);
                })->orWhere(function ($subQ) use ($yMin, $yMax) {
                    $subQ->where(function ($isNull) {
                        $isNull->whereNull('year')->orWhere('year', '');
                    })->whereHas('spec', function ($s) use ($yMin, $yMax) {
                        $s->whereBetween('model_year', [$yMin, $yMax]);
                    });
                });
            });
        }

        if ($request->filled('mileage_min') || $request->filled('mileage_max')) {
            $mMin = $request->filled('mileage_min') ? (int) $request->mileage_min : 0;
            $mMax = $request->filled('mileage_max') ? (int) $request->mileage_max : 99999999;

            $query->whereHas('spec', function ($s) use ($mMin, $mMax) {
                $s->whereNotNull('odometer')
                    ->whereBetween('odometer', [$mMin, $mMax]);
            });
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . trim($request->location) . '%');
        }

        if ($request->filled('price_min') || $request->filled('price_max')) {
            $minPrice = (float) $request->input('price_min', 0);
            $maxPrice = (float) $request->input('price_max', 30000000);
            if ($minPrice > 0 || $maxPrice < 30000000) {
                if ($minPrice > 0) {
                    $query->whereNotNull('vehicle_price')
                        ->whereBetween('vehicle_price', [$minPrice, $maxPrice]);
                } else {
                    $query->where(function ($q) use ($maxPrice) {
                        $q->where('vehicle_price', '<=', $maxPrice)
                            ->orWhereNull('vehicle_price');
                    });
                }
            }
        }

        return $query;
    }

    public function availableVehicles(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $cars = $this->buildVehicleQuery($request)->paginate($perPage)->withQueryString();

        $manufacturers = \App\Models\Manufacturer::orderBy('name', 'asc')->get();

        $locations = Car::whereNotNull('location')
            ->where('location', '!=', '')
            ->pluck('location')
            ->filter(fn($l) => !empty(trim($l)))
            ->unique()
            ->sort();

        return view('landing.pages.available-vehicles', compact('cars', 'manufacturers', 'locations'));
    }

    public function filterAvailableVehicles(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $cars = $this->buildVehicleQuery($request)->paginate($perPage)->withQueryString();

        return response()->json([
            'success' => true,
            'html' => view('landing.pages.partials.vehicle-list', compact('cars'))->render(),
            'count' => $cars->total(),
            'pagination' => (string) $cars->links('pagination::bootstrap-4'),
        ]);
    }

    public function rentalPolicies()
    {
        $policies = RentalPolicies::get();

        return view('landing.pages.rental-policies', compact('policies'));
    }

    public function licenceRequirement()
    {
        return view('landing.pages.licence-requirement');
    }

    public function aboutOurCars()
    {
        return view('landing.pages.about-our-cars');
    }

    public function usefulLinks()
    {
        return view('landing.pages.useful-links');
    }

    public function japanLaw()
    {
        return view('landing.pages.japan-law');
    }

    public function rideJapanLaw()
    {
        return view('landing.pages.ride-japan-law');
    }

    public function contact()
    {
        return view('landing.pages.contact');
    }

    public function reviews()
    {
        return view('landing.pages.reviews');
    }

    public function faqs()
    {
        $faqs = Faq::orderBy('created_at', 'desc')->get();
        //        dd($faqs);
        return view('landing.pages.faqs', compact('faqs'));
    }
    public function contactPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2',
            'email' => 'required|email',
            'contactNumber' => 'required|digits_between:10,15',
            'message' => 'required|string|min:10',
            'g-recaptcha-response' => 'required'
        ], [
            'name.required' => 'Please enter your name',
            'name.min' => 'Name must be at least 2 characters',
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'contactNumber.required' => 'Please enter your contact number',
            'contactNumber.digits_between' => 'Contact number must be between 10-15 digits',
            'message.required' => 'Please enter your message',
            'message.min' => 'Message must be at least 10 characters',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification'
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // Verify reCAPTCHA
        $recaptcha = $request->input('g-recaptcha-response');
        $secret_key = env('CAPTCHA_SECRET_KEY');
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $recaptcha;

        $response = file_get_contents($url);
        $response = json_decode($response);

        if (!$response->success) {
            return $this->sendError('reCAPTCHA verification failed. Please try again.');
        }

        try {
            sendDynamicEmail(env('RECEIVER_MAIL'), 'ContactUsMail', [
                'name' => $request->name,
                'email' => $request->email,
                'contactNumber' => $request->contactNumber,
                'messageContent' => $request->message,
            ]);

            return $this->sendSuccess('Your message has been sent successfully! We will get back to you soon.');
        } catch (\Exception $exception) {
            return $this->sendError('An error occurred while sending your message. Please try again later.');
        }
    }
}
