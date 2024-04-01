<?php

namespace Modules\Core\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class Sort implements FilterInterface
{
    /**
     * @param Builder $builder
     * @param array $parameters
     * @return Builder
     */
    public function handle(Builder $builder, array $parameters = []): Builder
    {
        if ($sort = data_get($parameters, 'sort')) {
            if (is_string($sort)) {
                $tmpSort = [];
                $tmpSort['by'] = $sort;
                $tmpSort['order'] = 'desc';
                $sort = $tmpSort;
            }
            if (isset($sort['order']) && !isset($sort['by'])) {
                return $builder;
            }
            if (!isset($sort['order'])) {
                $sort['order'] = 'desc';
            }
            $builder->orderBy($sort['by'], $sort['order']);
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
            'sort' => ['sometimes', 'required'],
            'sort.by' => ['sometimes', 'required_with:sort'],
            'sort.order' => ['sometimes', 'required_with:sort'],
        ]);
    }
}
