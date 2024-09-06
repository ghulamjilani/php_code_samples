<?php

namespace App\Http\Responses;

class ResponseCode
{
    const SUCCESS   = 200;
    const UPDATED   = 10;
    const FAIL      = 400;
    const NOT_FOUND = 404;
    const UNAUTHORIZED      = 403;
    const LOGOUT_SUCCESS    = 204;
    const VALIDATION_ERROR  = 422;
    const UNEXPECTED_ERROR  = 500;
    const INVALID_CREDENTIALS   = 401;

    const CUSTOM_MESSAGE = [
        self::SUCCESS           => 'Request was successful.',
        self::UPDATED           => 'Request was updated successful.',
        self::FAIL              => 'Request failed.',
        self::VALIDATION_ERROR  => 'Validation error occurred.',
        self::UNEXPECTED_ERROR  => 'Something went wrong. Please try again later.',
        self::NOT_FOUND         => 'Not Found.',
        self::UNAUTHORIZED      => 'Unauthorized access.',
        self::LOGOUT_SUCCESS    => 'Logged out successfully',
        self::INVALID_CREDENTIALS => 'Invalid credentials. Please check your username and password.',
    ];

    public static function getMessage($code)
    {
        return self::CUSTOM_MESSAGE[$code] ?? 'Unknown Error';
    }
}