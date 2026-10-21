<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::ordered()->get();
        $settings = getSetting();
        return view("landing.pages.services", compact("services", "settings"));
    }
}
