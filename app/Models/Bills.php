<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};

class Bills extends Model
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
        'invoice_no',
        'invoice_attach',
        'user_id',
        'amount',
        'billing_date',
        'description',
        'status',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'invoice_no' => 'integer'
    ];

    public function Organization()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
