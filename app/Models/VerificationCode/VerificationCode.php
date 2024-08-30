<?php

namespace App\Models\VerificationCode;

use App\Models\Customer\Customer;
use App\Traits\CreatedUpdatedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerificationCode extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;

    protected $fillable = ['customer_id', 'code', 'expires_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public static function isValid($customerId, $code)
    {
        return self::where('customer_id', $customerId)
        ->where('code', $code)
        ->where('expires_at', '>', now())
        ->first();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->code = rand(100000, 999999);
            $model->expires_at = Carbon::now()->addMinutes(10);
        });
    }


}
