<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplates extends Model
{
    protected $fillable = [
        'name',
        'key',
        'subject',
        'placeholder',
        'body',
    ];

    public function getNameAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        $names = [
            'ContactRequestMail'          => 'Vehicle Details & Quote Request',
            'ContactUsMail'               => 'Contact Us Form Submission',
            'RegisterMail'                => 'User Registration Welcome',
            'ForgotPasswordMail'          => 'Password Reset Request',
            'LoginDetailsMail'            => 'Staff / Employee Login Credentials',
            'DocumentRejectedMail'        => 'Document Verification Rejected',
            'AdminDocumentNotificationMail' => 'Admin Document Verification Notification',
        ];

        return $names[$this->key] ?? preg_replace('/(?<!\ )[A-Z]/', ' $0', (string)$this->key);
    }

    public function render($data = [])
    {
        return \Illuminate\Support\Facades\Blade::render($this->body, $data);
    }
}
