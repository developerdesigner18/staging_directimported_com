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

        $cars = Car::where('is_recommended', 1)->orderBy('created_at', 'desc')->limit(6)->get();
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
