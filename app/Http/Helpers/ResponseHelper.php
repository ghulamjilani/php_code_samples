<?php

// Constants
use App\Http\Responses\{
    Message,
    ResponseCode
};


/**
 * Return default object.
 *
 * @return array
 */
function invalidTokenResponse()
{
	return [
            'data' => [
                'code'      => ResponseCode::FAIL,
                'message'   => Message::INVALID_TOKEN,
                'serverMaintenance' => env("SERVER_MAINTENANCE", false),
            ],
            'status' => false
        ];
}


/**
 * Return default object.
 *
 * @return array
 */
function defaultErrorResponse()
{
	return [
            'data' => [
                'code'      => ResponseCode::FAIL,
                'message'   => Message::SOMETHING_WENT_WRONG,
                'serverMaintenance' => env("SERVER_MAINTENANCE"),
            ],
            'status' => false
        ];
}

/**
 * Make json object.
 *
 * @param  integer $code
 * @param  string $message
 * @param  boolean $status
 * @param  string $errors (optional)
 * @param  array $data
 * @return array
 */
function makeResponse($code, $message = '', $data = [], $status = 500, $errors = [])
{
	return [
            'data' => [
                'code'                  => $code,
                'message'               => $message,
                'result'                => $data,
                'errors'                => $errors,
                'serverMaintenance'     => env("SERVER_MAINTENANCE", false),
            ],
           'status' => $status
        ];
}