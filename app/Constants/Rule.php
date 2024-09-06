<?php

namespace App\Constants;

class Rule
{
    // Rules According to API's
    private static $rules = [
        'GENERATE_CIPHER' => [
            'sampleString'          => 'required',
        ],
        'ADD_CATEGORY' => [
            'name'              => 'required',
            'category_image'    => 'file',
            'parentId'          => 'required',
        ],
        'SAVE_ERROR' => [
            'apiName'       => 'required',
            'body'          => 'required',
            'header'        => 'required',
            'errorMessage'  => 'required',
        ],
    ];

    public static function get($api)
    {
        return self::$rules[$api];
    }
}
