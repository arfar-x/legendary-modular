<?php

namespace Modules\Core\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class With implements FilterInterface
{
    /**
     * @param Builder $builder
     * @param array $parameters
     * @return Builder
     */
    public function handle(Builder $builder, array $parameters = []): Builder
    {
        if ($withs = data_get($parameters, 'with')) {
            $builder->with($withs);
        }

        return $builder;
    }

    /**
     * Validate query on model's query builder.
     *
     * @param array $parameters
     * @return void
     */
    public function validate(array $parameters = []): void
    {
        Validator::validate($parameters, [
            'with' => ['sometimes', 'required', 'array']
        ]);
    }
}
