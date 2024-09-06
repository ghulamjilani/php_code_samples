<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeesStoreRequest;

use App\Http\Responses\{
    ResponseCode,
    Message
};
use App\Models\Employees;
use Illuminate\Http\{
    Request
};

class EmployeesController extends Controller
{
    public function index()
    {
        try
        {
            if(auth()->user()->role_id == 2)
                $Employees = Employees::where('user_id', auth()->id())->get();
                // $Employees = Employees::where('user_id', auth()->id())->with("Level", "Department")->get();
            elseif(auth()->user()->role_id == 1)
                $Employees = Employees::get();

            if(empty($Employees))
                return makeResponse(ResponseCode::SUCCESS, Message::NO_EMPLOYEES_YET, [], ResponseCode::SUCCESS);
            else
                return makeResponse(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $Employees, ResponseCode::SUCCESS);
        }
        catch (\Exception $e) {
            return makeResponse(ResponseCode::UNEXPECTED_ERROR, ResponseCode::getMessage(ResponseCode::UNEXPECTED_ERROR), [], ResponseCode::UNEXPECTED_ERROR, $e->getMessage());
        }
    }

    public function show($id)
    {
        try
        {
            $Employee = Employees::where('user_id', auth()->id())->with("User", "Level", "Department")->find($id);
            if(!$Employee)
                return makeResponse(ResponseCode::FAIL, Message::NO_EMPLOYEES, [], ResponseCode::FAIL);
            else
                return makeResponse(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::UPDATED), $Employee, ResponseCode::SUCCESS);
        }
        catch (\Exception $e) {
            return makeResponse(ResponseCode::UNEXPECTED_ERROR, ResponseCode::getMessage(ResponseCode::UNEXPECTED_ERROR), [], ResponseCode::UNEXPECTED_ERROR, $e->getMessage());
        }
    }

    public function store(EmployeesStoreRequest $request){
        try {
            $request['user_id'] = auth()->id();
            $data = $request->all();
            if(!$request->id)
            {
                // $Employee = Employees::create($data);
                $Employee = Employees::create([
                    'name'                  => $request['name'],
                    'user_id'               => auth()->id(),
                    'emp_id'                => $request['emp_id'],
                    'level_id'              => $request['level_id'] ?? 0,
                    // 'time_shifts_id'        => $request['time_shifts_id'] ?? 0,
                    'department_id'         => $request['department_id'] ?? 0,
                    'dob'                   => $request['dob'],
                    'gender'                => $request['gender'],
                    'address'               => $request['address'],
                    'email'                 => $request['email'],
                    'marital_status'        => $request['marital_status'],
                    'nationality'           => $request['nationality'],
                    'phone_no'              => $request['phone_no'],
                    'ni_number'             => $request['ni_number'],
                    'phone_no_p'            => $request['phone_no_p'] ?? "",
                    'phone_no_s'            => $request['phone_no_s'] ?? "",
                    'contract_start_date'   => $request['contract_start_date'], 
                    'contract_end_date'     => $request['contract_end_date'] ?? "",
                    'confirmation_date'     => $request['confirmation_date'] ?? "",
                    'joining_date'          => $request['joining_date'],
                    'line_manager'          => $request['line_manager'] ?? "",
                    'staff_manager'         => $request['staff_manager'] ?? "",
                    'job_location'          => $request['job_location'] ?? "",
                ]);
                if($Employee)
                    return makeResponse(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $Employee, true);
                else
                    return makeResponse(ResponseCode::FAIL, ResponseCode::getMessage(ResponseCode::FAIL), [], ResponseCode::FAIL);
            }
            else
            {
                // $Employee = Employees::where('id', $request->id)->update($data);
                $Employee = Employees::where('id', $request->id)->update([
                    'name'                  => $request['name'],
                    'user_id'               => auth()->id(),
                    'emp_id'                => $request['emp_id'],
                    'level_id'              => $request['level_id'] ?? 0,
                    // 'time_shifts_id'        => $request['time_shifts_id'] ?? 0,
                    'department_id'         => $request['department_id'] ?? 0,
                    'dob'                   => $request['dob'],
                    'gender'                => $request['gender'],
                    'address'               => $request['address'],
                    'email'                 => $request['email'],
                    'marital_status'        => $request['marital_status'],
                    'nationality'           => $request['nationality'],
                    'phone_no'              => $request['phone_no'],
                    'ni_number'             => $request['ni_number'],
                    'phone_no_p'            => $request['phone_no_p'] ?? "",
                    'phone_no_s'            => $request['phone_no_s'] ?? "",
                    'contract_start_date'   => $request['contract_start_date'], 
                    'contract_end_date'     => $request['contract_end_date'] ?? "",
                    'confirmation_date'     => $request['confirmation_date'] ?? "",
                    'joining_date'          => $request['joining_date'],
                    'line_manager'          => $request['line_manager'] ?? "",
                    'staff_manager'         => $request['staff_manager'] ?? "",
                    'job_location'          => $request['job_location'] ?? "",
                ]);
                if($Employee)
                    return makeResponse(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $Employee, true);
                else
                    return makeResponse(ResponseCode::FAIL, ResponseCode::getMessage(ResponseCode::FAIL), [], ResponseCode::FAIL);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroy($id)
    {
        try
        {
            $employee = Employees::where('user_id', auth()->id())->find($id);
            if(!$employee)
                return makeResponse(ResponseCode::FAIL, Message::NO_EMPLOYEES, [], ResponseCode::FAIL);
            else{
                $employee->delete();
                return makeResponse(ResponseCode::SUCCESS, Message::RECORD_DELETED, $employee, ResponseCode::SUCCESS);
            }
        }
        catch (\Exception $e) {
            return makeResponse(ResponseCode::UNEXPECTED_ERROR, ResponseCode::getMessage(ResponseCode::UNEXPECTED_ERROR), [], ResponseCode::UNEXPECTED_ERROR, $e->getMessage());
        }
    }

    public function employeeNames()
    {
        try
        {
            if(auth()->user()->role_id == 2)
                $Employees = Employees::select(
                                            "id",
                                            "name",
                                            "user_id"
                                        )->where('user_id', auth()->id())->get();
            elseif(auth()->user()->role_id == 1)
                $Employees = Employees::select(
                                            "id",
                                            "name",
                                            "user_id"
                                        )->get();

            if(empty($Employees))
                return makeResponse(ResponseCode::SUCCESS, Message::NO_EMPLOYEES_YET, [], ResponseCode::SUCCESS);
            else
                return makeResponse(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $Employees, ResponseCode::SUCCESS);
        }
        catch (\Exception $e) {
            return makeResponse(ResponseCode::UNEXPECTED_ERROR, ResponseCode::getMessage(ResponseCode::UNEXPECTED_ERROR), [], ResponseCode::UNEXPECTED_ERROR, $e->getMessage());
        }
    }

    public function search($search)
    {
        try
        {
            if(auth()->user()->role_id == 2)
            {    
                $Employees = Employees::where('user_id', auth()->id())
                                        ->where(function ($query) use ($search) {
                                            $query->where('emp_id', 'like', "%{$search}%")
                                                ->orWhereRaw("name LIKE?", ["%{$search}%"]);
                                        })->get();
            } elseif(auth()->user()->role_id == 1)
            {    
                $Employees = Employees::where('emp_id', 'like', "%{$search}%")
                                        ->orWhereRaw("name LIKE?", ["%{$search}%"])
                                        ->get();
            }
            if(empty($Employees))
                return makeResponse(ResponseCode::SUCCESS, Message::NO_EMPLOYEES_YET, [], ResponseCode::SUCCESS);
            else
                return makeResponse(ResponseCode::SUCCESS, ResponseCode::getMessage(ResponseCode::SUCCESS), $Employees, ResponseCode::SUCCESS);
        }
        catch (\Exception $e) {
            return makeResponse(ResponseCode::UNEXPECTED_ERROR, ResponseCode::getMessage(ResponseCode::UNEXPECTED_ERROR), [], ResponseCode::UNEXPECTED_ERROR, $e->getMessage());
        }
    }
}
