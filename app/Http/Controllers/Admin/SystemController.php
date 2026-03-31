<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class SystemController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return view('admin.system.settings');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'MAIL_HOST' => 'required',
            'MAIL_PORT' => 'required|numeric',
            'MAIL_USERNAME' => 'required',
            'MAIL_PASSWORD' => 'required',
            'MAIL_ENCRYPTION' => 'required',
            'MAIL_FROM_ADDRESS' => 'required|email',
            'MAIL_FROM_NAME' => 'required',
            'RECEIVER_MAIL' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $envPath = base_path('.env');

            // Read the current .env content
            $envContent = file_get_contents($envPath);

            // Update each setting in the .env content
            foreach ($request->all() as $key => $value) {
                if (str_starts_with($key, 'MAIL_')) {
                    // Escape any quotes in the value
                    $escapedValue = str_replace('"', '\"', $value);

                    // Create the pattern to search for
                    $pattern = "/^{$key}=.*$/m";

                    // Create the replacement line
                    $replacement = "{$key}=\"{$escapedValue}\"";

                    // If the key exists, replace it, otherwise add it
                    if (preg_match($pattern, $envContent)) {
                        $envContent = preg_replace($pattern, $replacement, $envContent);
                    } else {
                        $envContent .= PHP_EOL . $replacement;
                    }
                }
            }

            // Write the updated content back to the .env file
            file_put_contents($envPath, $envContent);

            // Clear the config cache
            Artisan::call('config:clear');

            return $this->sendSuccess('Settings updated successfully!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
}
