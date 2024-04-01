<?php

namespace Modules\Core\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class Only implements FilterInterface
{
    /**
     * @param Builder $builder
     * @param array $parameters
     * @return Builder
     */
    public function handle(Builder $builder, array $parameters = []): Builder
    {
        if ($selects = data_get($parameters, 'only')) {
            $builder->select($selects);
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
            'only' => ['sometimes', 'required', 'array']
        ]);
    }
}
