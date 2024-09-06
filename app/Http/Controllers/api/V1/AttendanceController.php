<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Attendance;
use App\Models\CourseRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\{
    RecordAttendanceRequest,
    UpdateAttendanceRequest,
    MarkAttendanceRequest
};

use App\Http\Responses\{
    Message,
    ResponseCode
};

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $attendanceQuery = User::employees()->activated()->withSpecificAttributes()
                ->with(['attendances.course.courseRegistrations']);
            
            if ($request->has('course_ids')) {
                $attendanceQuery->byCourses($request->course_ids);
            }

            if ($request->has('status')) {
                $attendanceQuery->byAttendanceStatus($request->status);
            }

            if ($request->has('employee_number')) {
                $attendanceQuery->byJobNumber($request->employee_number);
            }

            $attendanceData = $attendanceQuery->distinct()
                ->orderBy('created_at', 'asc')
                ->paginate($request->get('per_page', 15));

            return makeResponse(ResponseCode::SUCCESS, __('attendance.list'), $attendanceData->toArray(), ResponseCode::SUCCESS);
        } catch (\Exception $e) {
            return makeResponse(ResponseCode::FAIL, __('attendance.fetch_error'), [], ResponseCode::FAIL, [$e->getMessage()]);
        }
    }

    public function record(RecordAttendanceRequest $request)
    {
        try {
            $employee = User::where('phone_number', $request->phone_number)
                ->where('employee_number', $request->employee_number)
                ->first();

            if (!$employee) {
                return makeResponse(ResponseCode::FAIL, __('devise.sessions.not_found'), [], ResponseCode::FAIL);
            }

            if (!$employee->hasRole(__('roles.employee'))) {
                return makeResponse(ResponseCode::FAIL, __('devise.sessions.role_not_exist'), [], ResponseCode::FAIL);
            }

            if (!$employee->activated) {
                return makeResponse(ResponseCode::FAIL, __('devise.sessions.not_activated'), [], ResponseCode::FAIL);
            }

            $course = Course::find($request->course_id);

            if (!$course) {
                return makeResponse(ResponseCode::FAIL, __('courses.not_found'), [], ResponseCode::FAIL);
            }

            if (!CourseRegistration::where(['user_id' => $employee->id, 'course_id' => $course->id, 'status' => 'approved'])->exists()) {
                return makeResponse(ResponseCode::FAIL, __('attendance.not_registered'), [], ResponseCode::FAIL);
            }

            if (Attendance::where(['employee_id' => $employee->id, 'course_id' => $course->id, 'attendance_date' => now()->toDateString()])->exists()) {
                return makeResponse(ResponseCode::FAIL, __('attendance.already_marked'), [], ResponseCode::FAIL);
            }

            $newAttendance = Attendance::create([
                'employee_id' => $employee->id,
                'course_id' => $course->id,
                'attendance_date' => now()->toDateString(),
                'status' => 0,
            ]);

            return makeResponse(ResponseCode::SUCCESS, __('attendance.record_success'), $newAttendance->load(['employee', 'course']), ResponseCode::SUCCESS);
        } catch (\Exception $e) {
            return makeResponse(ResponseCode::FAIL, __('attendance.record_fail'), [], ResponseCode::FAIL, [$e->getMessage()]);
        }
    }

    public function update(UpdateAttendanceRequest $request, $id)
    {
        try {
            $attendance = Attendance::find($id);

            if (!$attendance) {
                return makeResponse(ResponseCode::FAIL, __('attendance.not_found'), [], ResponseCode::FAIL);
            }

            $attendance->status = $request->status;
            $attendance->save();

            return makeResponse(ResponseCode::SUCCESS, __('attendance.update_success'), $attendance->load('course'), ResponseCode::SUCCESS);
        } catch (\Exception $e) {
            return makeResponse(ResponseCode::FAIL, __('attendance.update_fail'), [], ResponseCode::FAIL, [$e->getMessage()]);
        }
    }

    public function mark(MarkAttendanceRequest $request)
    {
        try {
            $newAttendance = Attendance::create([
                'employee_id' => $request->employee_id,
                'course_id' => $request->course_id,
                'attendance_date' => now()->toDateString(),
                'status' => $request->status,
            ]);

            return makeResponse(ResponseCode::SUCCESS, __('attendance.record_success'), $newAttendance->load('course'), ResponseCode::SUCCESS);
        } catch (\Exception $e) {
            return makeResponse(ResponseCode::FAIL, __('attendance.update_fail'), [], ResponseCode::FAIL, [$e->getMessage()]);
        }
    }
}
