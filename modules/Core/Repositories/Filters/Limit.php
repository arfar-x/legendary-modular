<?php

namespace Modules\Core\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class Limit implements FilterInterface
{
    /**
     * @param Builder $builder
     * @param array $parameters
     * @return Builder
     */
    public function handle(Builder $builder, array $parameters = []): Builder
    {
        $builder->limit(data_get($parameters, 'limit', config('core.filter.default_doc_count')));

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
            'limit' => ['sometimes', 'required', 'numeric', 'min:1', 'max:'.config('core.filter.max_doc_count')],
        ]);
    }
}
