<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Models\ContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ContactRequestController extends Controller
{
    use ResponseTrait;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'preferred_contact_method' => 'nullable|string',
            'vehicle_id' => 'nullable|string',
            'destination_country' => 'nullable|string',
            'nearest_port_or_postal_code' => 'nullable|string',
            'message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $contactRequest = ContactRequest::create($request->all());

            DB::commit();

            // Send Email to Admin
            sendDynamicEmail(env('RECEIVER_MAIL'), 'ContactRequestMail', [
                'full_name' => $contactRequest->full_name,
                'email' => $contactRequest->email,
                'phone' => $contactRequest->phone_number,
                'method' => $contactRequest->preferred_contact_method,
                'vehicle' => $contactRequest->vehicle_id,
                'country' => $contactRequest->destination_country,
                'port' => $contactRequest->nearest_port_or_postal_code,
                'message_body' => $contactRequest->message,
            ]);

            return $this->sendSuccess('Your request has been submitted successfully! We will contact you soon.');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }
}
