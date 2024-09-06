<?php

namespace App\Http\Requests;

use App\Http\Responses\ResponseCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeesStoreRequest extends FormRequest
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
            'id'                    => [ 'exists:employees,id' ],
            'name'                  => [ 'required'],
            'user_id'               => [ 'required', 'exists:users,id' ],
            'level_id'              => [ 'required', 'exists:levels,id' ],
            'time_shifts_id'        => [ 'required', 'exists:time_shifts,id' ],
            'department_id'         => [ 'required', 'exists:departments,id' ],
            'emp_id'                => [ 'required', 'unique:employees,emp_id' ],
            'dob'                   => [ 'required' ],
            'gender'                => [ 'required' ],
            'address'               => [ 'required' ],
            'email'                 => [ 'required' ],
            'marital_status'        => [ 'required' ],
            'nationality'           => [ 'required' ],
            'phone_no'              => [ 'required' ],
            'ni_number'             => [ 'required' ],
            'contract_start_date'   => [ 'required' ],
            'joining_date'          => [ 'required' ],          
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
