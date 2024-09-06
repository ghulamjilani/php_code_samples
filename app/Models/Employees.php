<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'emp_id',
        'level_id',
        'department_id',
        'dob',
        'gender',
        'address',
        'email',
        'marital_status',
        'nationality',
        'phone_no',
        'ni_number',
        'phone_no_p',
        'phone_no_s',
        'contract_start_date',
        'contract_end_date',
        'confirmation_date',
        'joining_date',
        'line_manager',
        'staff_manager',
        'job_location',
    ];

    protected $casts = [
        'id'        => 'integer',
        'user_id'   => 'integer',
        'level_id'  => 'integer',
        // 'time_shifts_id' => 'integer'
    ];
    
    public function User()
    {
        return $this->belongsTo(User::class, 'emp_id', 'id');
    }
}

   