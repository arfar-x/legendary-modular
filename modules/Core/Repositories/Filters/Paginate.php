<?php

namespace Modules\Core\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class Paginate implements FilterInterface
{
    /**
     * @param Builder $builder
     * @param array $parameters
     * @return Builder
     */
    public function handle(Builder $builder, array $parameters = []): Builder
    {
        if ($paginate = data_get($parameters, 'paginate', false)) {
            // If 'paginate.per_page' does not exist, retrieve the value from 'parameters.limit'.
            $perPage = data_get(
                $paginate,
                'per_page',
                data_get($parameters, 'limit', config('core.filter.paginate_per_page'))
            );
            $builder->forPage($paginate['page'], $perPage);
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
            'paginate' => ['sometimes', 'required', 'array', 'max:' . config('core.filter.max_doc_count')],
            'paginate.page' => ['sometimes', 'required', 'numeric'],
            'paginate.per_page' => ['sometimes', 'required_without:limit', 'numeric']
        ]);
    }
}
