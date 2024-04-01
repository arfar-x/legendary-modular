<?php

namespace Modules\Core\Repositories;

use Illuminate\Database\Eloquent\Builder;

interface RepositoryInterface
{
    /**
     * Assign queries on model's query builder.
     *
     * @param array $parameters
     * @return Builder
     */
    public function filter(array $parameters = []): Builder;
}
