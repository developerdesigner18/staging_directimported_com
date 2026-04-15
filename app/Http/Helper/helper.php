<?php

// FOR GLOBAL FUNCTIONS

// Generate Filename
use App\Models\SiteSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\EmailTemplates;
use App\Mail\ApprovedMail;
use Illuminate\Support\Facades\Mail;
use League\HTMLToMarkdown\HtmlConverter;

function fileName($ext, $prefix = 'img_')
{
    return $prefix . now()->format('dmYHisv') . rand(100000, 999999) . '.' . $ext;
}

// Upload File
function uploadFile($file, $path, $prefix = 'img_')
{
    $manager = new \Intervention\Image\ImageManager(\Intervention\Image\Drivers\Gd\Driver::class);
    $image = $manager->read($file);
    $fileName = $prefix . time() . '_' . uniqid() . '.webp';

    if (!File::exists(public_path($path))) {
        File::makeDirectory(public_path($path), 0755, true);
    }

    $image->toWebp(80)->save(public_path($path) . $fileName);

    return $fileName;
}

function uploadFilepondEncodedFile($json, $path, $prefix = 'img_')
{
    $bannerJson = json_decode($json, true);
    if (!isset($bannerJson['data']) || !isset($bannerJson['type'])) {
        return null;
    }

    $base64Image = base64_decode($bannerJson['data']);

    $manager = new \Intervention\Image\ImageManager(\Intervention\Image\Drivers\Gd\Driver::class);
    $image = $manager->read($base64Image);

    $fileName = $prefix . time() . '_' . uniqid() . '.webp';

    if (!File::exists(public_path($path))) {
        File::makeDirectory(public_path($path), 0755, true);
    }

    $image->toWebp(80)->save(public_path($path) . $fileName);

    return $fileName;
}

// Convert time to human readable format
function dateToHuman($date, $format = 'Y-m-d H:i:s', $timezone = 'UTC')
{
    try {
        if (!empty($date)) {
            return \Carbon\Carbon::parse($date, 'UTC')
                ->tz($timezone)
                ->format($format);
        } else {
            return 'N/A';
        }
    } catch (Exception $exception) {
        return 'N/A';
    }
}

function generateBookingId()
{
    $today = now()->format('Ymd'); // Short date: YYMMDD
    $random = strtoupper(Str::random(4)); // Random 4 letters/numbers

    return 'BK' . $today . $random;
}
function diffInDays($date1, $date2)
{
    $start_date = Carbon::parse(date('Y-m-d', strtotime($date1)));
    $end_date = Carbon::parse(date('Y-m-d', strtotime($date2)));

    $days = $start_date->diffInDays($end_date);

    return $days;
}

function totalBookingDays($startDate, $endDate, $endTime = null)
{
    $start = Carbon::parse($startDate)->startOfDay();
    $end = Carbon::parse($endDate)->startOfDay();
    $days = $start->diffInDays($end);
    $days = max(1, $days); // Enforce base calculation to be at least 1 day

    if ($endTime) {
        $dropTime = strtotime($endTime);
        $limitTime = strtotime('14:00');
        if ($dropTime > $limitTime) {
            $days += 1;
        }
    }

    return (int) $days;
}

function bikePagination($totalPages, $active = 1)
{
    $html = '<div class="rid-pagination text-center">
        <ul class="list-inline">';

    // Previous
    $prevPage = max(1, $active - 1);
    $html .= '<li class="list-inline-item">
                <a class="previous" href="javascript:void(0);" data-page="' . $prevPage . '">
                    <i class="icofont-thin-left"></i>
                </a>
              </li>';

    // First page
    $html .= '<li class="list-inline-item page-btn ' . (($active == 1) ? 'active' : '') . '" data-page="1">
                <a href="javascript:void(0);">1</a>
              </li>';

    // Left dots
    if ($active > 3) {
        $html .= '<li class="list-inline-item"><span>...</span></li>';
    }

    // Middle pages (active-1, active, active+1)
    $start = max(2, $active - 1);
    $end = min($totalPages - 1, $active + 1);

    for ($i = $start; $i <= $end; $i++) {
        $html .= '<li class="list-inline-item page-btn ' . (($i == $active) ? 'active' : '') . '" data-page="' . $i . '">
                    <a href="javascript:void(0);">' . $i . '</a>
                  </li>';
    }

    // Right dots
    if ($active < $totalPages - 2) {
        $html .= '<li class="list-inline-item"><span>...</span></li>';
    }

    // Last page
    if ($totalPages > 1) {
        $html .= '<li class="list-inline-item page-btn ' . (($active == $totalPages) ? 'active' : '') . '" data-page="' . $totalPages . '">
                    <a href="javascript:void(0);">' . $totalPages . '</a>
                  </li>';
    }

    // Next
    $nextPage = min($totalPages, $active + 1);
    $html .= '<li class="list-inline-item">
                <a class="next" href="javascript:void(0);" data-page="' . $nextPage . '">
                    <i class="icofont-thin-right"></i>
                </a>
              </li>';

    $html .= '</ul></div>';

    return $html;
}

function deleteImage($file, $path)
{
    $filepath = public_path($path) . basename($file);
    if (File::exists($filepath)) {
        File::delete($filepath);

        return true;
    }

    return false;
}
function generateTemporaryPassword($firstName, $lastName, $bookingId)
{
    // First name aur last name ke first 2 characters
    $fnPart = substr($firstName, 0, 2);
    $lnPart = substr($lastName, 0, 2);

    // Booking ID ka last 4 digits
    $bookingPart = substr($bookingId, -4);

    // Random 2 character alphanumeric string
    $randomPart = Str::random(2);

    // Combine sab parts
    $tempPassword = $fnPart . $lnPart . $bookingPart . $randomPart;

    return $tempPassword;
}

function sendDynamicEmail($to, $templateKey, $data)
{
    $template = EmailTemplates::where('key', $templateKey)->first();
    if (!$template) {
        return false;
    }

    $body = $template->render($data);

    // Convert to markdown if needed, but emails.approved uses {!! !!}
    // and expects HTML/Markdown mixed.
    // However, some Mailables might expect Markdown for better formatting.
    // To be safe and consistent with current implementation:
    $converter = new HtmlConverter();
    $markdownBody = $converter->convert($body);

    Mail::to($to)->send(new ApprovedMail($template->subject, $markdownBody));
    return true;
}
function getSetting()
{
    return SiteSettings::first();
}