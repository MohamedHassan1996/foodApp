<?php

namespace App\Models\Product;

use App\Enums\Item\ItemStatus;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;

    protected $fillable = [
        'name',
        'description',
        'path',
        'status',
    ];

    protected $casts = [
        'status' => ItemStatus::class,
    ];
}
