<?php

namespace Modules\Core\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Query implements FilterInterface
{
    /**
     * @param Builder $builder
     * @param array $parameters
     * @return Builder
     */
    public function handle(Builder $builder, array $parameters = []): Builder
    {
        if ($queries = data_get($parameters, 'query')) {
            foreach ($queries as $query) {
                switch ($query['operator']) {
                    case '=':
                    case 'eq':
                        $builder->where($query['field'], '=', $query['value']);
                        break;
                    case '<':
                    case 'lt':
                        $builder->where($query['field'], '<', $query['value']);
                        break;
                    case '<=':
                    case 'lte':
                        $builder->where($query['field'], '<=', $query['value']);
                        break;
                    case '>':
                    case 'gt':
                        $builder->where($query['field'], '>', $query['value']);
                        break;
                    case '>=':
                    case 'gte':
                        $builder->where($query['field'], '>=', $query['value']);
                        break;
                    case '!':
                    case 'not':
                        $builder->whereNot($query['field'], $query['value']);
                        break;
                    case 'in':
                        $builder->whereIn($query['field'], $query['value']);
                        break;
                    case 'not_in':
                        $builder->whereNotIn($query['field'], $query['value']);
                        break;
                    case 'like':
                        $builder->where($query['field'], 'like', "%{$query['value']}%");
                        break;
                }
            }
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
        // Valid operators.
        $ins = [
            '=',
            'eq',
            '<',
            'lt',
            '<=',
            'lte',
            '>',
            'gt',
            '>=',
            'gte',
            '!',
            'not',
            'in',
            'not_in',
            'like'
        ];

        Validator::validate($parameters, [
            'query' => ['sometimes', 'required', 'array'],
            'query.*.operator' => ['required_with:query', 'string', Rule::in($ins)],
            'query.*.field' => ['required_with:query', 'string'],
            'query.*.value' => ['required_with:query'],
        ]);
    }
}
