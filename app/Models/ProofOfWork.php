<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};

class ProofOfWork extends Model
{
    use HasFactory, SoftDeletes;

    protected $softDeletes = true;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'pow_id',
        'expiry_date',
        'document',
        'status',
        'emp_id',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'emp_id' => 'integer',
    ];

    public function Organization()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function Employee()
    {
        return $this->belongsTo(Employees::class, 'emp_id', 'id');
    }
}
