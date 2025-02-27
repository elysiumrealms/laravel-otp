<?php

return [
    /*
     | --------------------------------------------------------------------------
     | Type
     | --------------------------------------------------------------------------
     |
     | The type of the OTP. Available types: `numeric`, `alphanumeric`.
     | - numeric: Only numbers will be used (0-9)
     | - alphanumeric: Both letters and numbers will be used (A-Z, 0-9)
     |
     */
    'type' => 'numeric',

    /*
     | --------------------------------------------------------------------------
     | Debug
     | --------------------------------------------------------------------------
     |
     | When debug is true, the OTP token will be included in the response to the client
     | during generation. This should only be enabled in development environments.
     |
     */
    'debug' => env('OTP_DEBUG', false),

    /*
     | --------------------------------------------------------------------------
     | Length
     | --------------------------------------------------------------------------
     |
     | The length of the OTP token. A longer length provides more security but may
     | be less user-friendly. Common lengths are 4-8 characters.
     |
     */
    'length' => 4,

    /*
     | --------------------------------------------------------------------------
     | Validity
     | --------------------------------------------------------------------------
     |
     | The validity period of the OTP token in minutes. After this time expires,
     | the token will no longer be valid and a new one must be generated.
     |
     */
    'validity' => 10,

    /*
     | --------------------------------------------------------------------------
     | Validations
     | --------------------------------------------------------------------------
     |
     | The validation rules to apply to the recipient identifier when generating OTP.
     | - mail: Must be a valid email address
     | - sms: Must be a valid string (typically a phone number)
     |
     */
    'validations' => [
        'mail' => 'email',
        'sms' => 'string',
    ],

    'database' => [
        /*
         | --------------------------------------------------------------------------
         | Table
         | --------------------------------------------------------------------------
         |
         | The database table name where OTP records will be stored. This table will
         | be created during package migration.
         |
         */
        'table' => 'otps',

        /*
         | --------------------------------------------------------------------------
         | Model
         |--------------------------------------------------------------------------
         |
         | The Eloquent model class that represents an OTP record. You can extend
         | this model to add custom functionality.
         |
         */
        'model' => Elysiumrealms\Otp\Models\Otp::class,
    ],

    /*
     | --------------------------------------------------------------------------
     | Providers
     | --------------------------------------------------------------------------
     |
     | The notification channel providers used to send OTP tokens.
     | - sms: Uses Twilio for sending SMS messages
     | - mail: Uses Laravel's built-in mail system (defined in config/mail.php)
     |
     */
    'providers' => [
        'sms' => NotificationChannels\Twilio\TwilioChannel::class,
    ],

    /*
     | --------------------------------------------------------------------------
     | Mail
     | --------------------------------------------------------------------------
     |
     | Configuration options for OTP emails.
     |
     */
    'mail' => [
        /*
         | --------------------------------------------------------------------------
         | Logo
         | --------------------------------------------------------------------------
         |
         | The path to the logo image that will be displayed in OTP emails.
         | Leave empty to exclude logo. The path should be relative to public directory.
         |
         */
        'logo' => '',
    ],

    /*
     | --------------------------------------------------------------------------
     | Routes
     | --------------------------------------------------------------------------
     |
     | Configuration for the package's API routes.
     |
     */
    'routes' => [
        /*
        | --------------------------------------------------------------------------
        | Prefix
        | --------------------------------------------------------------------------
        |
        | The URL prefix for all OTP-related routes. For example, with prefix
        | 'api/v1/otp', the generate endpoint would be at '/api/v1/otp/generate'.
        |
        */
        'prefix' => 'api/v1/otp',

        /*
        | --------------------------------------------------------------------------
        | Throttle
        | --------------------------------------------------------------------------
        |
        | Maximum number of OTP requests allowed per minute for each unique identifier.
        | This helps prevent abuse and brute force attempts.
        |
        */
        'throttle' => 5,
    ],
];
