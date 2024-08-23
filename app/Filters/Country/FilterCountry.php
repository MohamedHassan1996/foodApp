<?php

namespace App\Filters\Country;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterCountry implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            $query->where('name', 'like', '%' . $value . '%')
                ->orWhere('code', 'like', '%' . $value . '%');
        });
    }
}
