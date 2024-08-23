<?php

namespace App\Filters\Product;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FilterProductByAlternative implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->whereHas('alternatives', function (Builder $query) use ($value) {
            $query->where('main_product_id', $value);
        });
    }
}
