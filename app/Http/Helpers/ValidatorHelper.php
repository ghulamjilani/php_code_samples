<?php

// Constants
use App\Constants\Rule;
use App\Http\Responses\ResponseCode;
use Illuminate\Support\Facades\Validator;

/**
 * Return Rules Object.
 *
 * @param  string $api
 * @return object
 */
function getRule($api)
{
	return Rule::get($api);
}

function validateData($request, $apiRule, $customMessages=[], $customAttribute = [])
{
	try
	{
		$rules = getRule($apiRule);
		$validator = Validator::make($request->all(), $rules, $customMessages, $customAttribute);
		
		if ($validator->fails()) 
		{
			return [ 
				'status' => true, 
				'errors' => $validator->messages()
			];
		}
		return [ 'status'=>false ];
	}
	catch (\Exception $e) 
	{
		\DB::rollBack();
		return makeResponse(ResponseCode::FAIL,"Invalid Data:".$e->getMessage(),false, null, null, 1);
	}
}