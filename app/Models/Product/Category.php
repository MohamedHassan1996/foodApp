<?php

namespace App\Models\Product;

use App\Enums\Category\CategoryStatus;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;

    protected $fillable = [
        'name',
        'path',
        'status',
    ];

    protected $casts = [
        'status' => CategoryStatus::class,
    ];

}
