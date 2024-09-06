<?php

namespace App\Http\Requests;

use App\Http\Responses\ResponseCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProofOfWorkStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'pow_id'        => 'required',
            'document'      => 'required|file|mimes:jpg,jpeg,png,gif,csv,txt,pdf|max:5120',
            'emp_id'        => 'required|exits:employees',
            'expiry_date'   => 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            makeResponse(
                ResponseCode::VALIDATION_ERROR, 
                ResponseCode::getMessage(ResponseCode::VALIDATION_ERROR), 
                [], 
                ResponseCode::VALIDATION_ERROR,
                $validator->errors()
            ),
            422
        ));
    }
}
