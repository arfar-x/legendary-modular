<?php

namespace Modules\Core\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;

interface FilterInterface
{
    /**
     * Apply query on model's query builder.
     *
     * @param Builder $builder
     * @param array $parameters
     * @return Builder
     */
    public function handle(Builder $builder, array $parameters = []): Builder;

    /**
     * Validate query on model's query builder.
     *
     * @param array $parameters
     * @return void
     */
    public function validate(array $parameters = []): void;
}
