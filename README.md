# OTP Verification System

A module for generating and verifying OTPs.

## Installation Guide

Install the package using Composer.

```bash
composer require elysiumrealms/otp
```

Run the migrations.

```bash
php artisan migrate
```

## Implementation Guide

Schedule the `otp:clean` command to clean expired OTPs.

```php
function schedule(Schedule $schedule)
{
    $schedule->command('otp:clean')
        ->onOneServer()
        ->everyFiveMinutes();
}
```

## Usage Guide

Generate an for a mobile number.

```bash
curl -X POST http://localhost:8000/api/v1/otp/sms/generate \
    -H "Content-Type: application/json"
    -d '{"identifier": "1234567890"}'
```

Generate an OTP for an email address.

```bash
curl -X POST http://localhost:8000/api/v1/otp/mail/generate \
    -H "Content-Type: application/json"
    -d '{"identifier": "test@example.com"}'
```

Verify an OTP.

```bash
curl -X POST http://localhost:8000/api/v1/otp/validate \
    -H "Content-Type: application/json"
    -d '{"identifier": "1234567890", "token": "123456"}'
```

Verify an OTP for an email address.

```bash
curl -X POST http://localhost:8000/api/v1/otp/email/validate \
    -H "Content-Type: application/json"
    -d '{"identifier": "test@example.com", "token": "123456"}'
```

Check if identifier is verified with validator.

```php
public function __invoke(Request $request)
{
    $request->validate([
        'identifier' => 'required|otp_verified',
    ]);
}
```

Check if identifier is verified.

```php
if (\Elysiumrealms\Otp\Facades\Otp::verified('1234567890'))
    echo 'Identifier is verified';
```

For further details, please contact the development team.
