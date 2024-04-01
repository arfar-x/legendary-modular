<?php

namespace Modules\Core\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Repositories\Filters\FilterInterface;
use Modules\Core\Repositories\Filters\Limit;
use Modules\Core\Repositories\Filters\Paginate;
use Modules\Core\Repositories\Filters\Query;
use Modules\Core\Repositories\Filters\Only;
use Modules\Core\Repositories\Filters\Sort;
use Modules\Core\Repositories\Filters\With;

class BaseRepository implements RepositoryInterface
{
    /**
     * Filter chains that apply queries on query builder.
     *
     * @var array|string[]
     */
    protected array $filters = [
        Query::class,
        Only::class,
        Sort::class,
        Limit::class,
        With::class,
        Paginate::class // Better be the last index
    ];

    /**
     * @var Builder
     */
    protected Builder $builder;

    /**
     * @param Model $model
     */
    public function __construct(protected Model &$model)
    {
        $this->builder = $this->model->query();
    }

    /**
     * @param array $parameters
     * @return Builder
     */
    public function filter(array $parameters = []): Builder
    {
        foreach ($this->filters as $filter) {
            /** @var FilterInterface $filter */
            $filter = new $filter;
            $filter->validate($parameters);
            $this->builder = $filter->handle($this->builder, $parameters);
        }

        return $this->builder;
    }

    /**
     * Get the result from queried model.
     *
     * @param array $parameters
     * @return Collection|array
     */
    public function get(array $parameters = []): Collection|array
    {
        return $this->filter($parameters)->get();
    }

    /**
     * Get the paginated result from queried model.
     *
     * @param array $parameters
     * @param string|null $modelResource
     * @return LengthAwarePaginator
     */
    public function paginated(array $parameters = [], ?string $modelResource = null): LengthAwarePaginator
    {
        $this->filter($parameters);

        /**
         * `builder->paginate()` method runs the query within itself. The problem is, the queries applied by
         * `$this->filter()` method are NOT applied on paginate() query. So, we have to instruct pagination mechanism
         * manually.
         */
        $total = $this->builder->toBase()->getCountForPagination();

        if ($modelResource) {
            /** @var JsonResource $modelResource */
            $items = $modelResource::collection($this->builder->get());
        } else {
            $items = $this->builder->get();
        }

        $currentPage = (int)data_get($parameters['paginate'] ?? [], 'page', 1);
        $perPage = (int)data_get(
            $parameters['paginate'] ?? [],
            'per_page',
            data_get($parameters, 'limit', config('core.filter.paginate_per_page'))
        );

        return new LengthAwarePaginator($items, $total, $perPage, $currentPage);
    }

    /**
     * Store a new record of specified resource.
     *
     * @param array $parameters
     * @return Model
     */
    public function store(array $parameters): Model
    {
        return $this->builder->create($parameters);
    }

    /**
     * Update the record of specified resource.
     *
     * @param Model $model
     * @param array $parameters
     * @return Model|bool
     */
    public function update(Model $model, array $parameters): Model|bool
    {
        if ($model->update($parameters)) {
            return $model->refresh();
        }

        return false;
    }

    /**
     * Delete the record.
     *
     * @param Model $model
     * @return bool
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * Force delete the record.
     *
     * @param Model $model
     * @return bool
     */
    public function forceDelete(Model $model): bool
    {
        return $model->forceDelete();
    }

    /**
     * Mass-delete the records by their IDs.
     *
     * @param array $ids
     * @return bool
     */
    public function massDelete(array $ids): bool
    {
        $models = $this->builder->whereIn('id', $ids);

        return $models->delete();
    }

    /**
     * Eager load the relations for the model.
     *
     * @param Model $model
     * @param string|array $relations
     * @return Model
     */
    public function with(Model $model, string|array $relations = []): Model
    {
        return $model->load($relations);
    }

    /**
     * @param string $id
     * @param bool $failOnNotFound
     * @return Model
     */
    public function find(string $id, bool $failOnNotFound = true): Model
    {
        return $failOnNotFound ? $this->builder->findOrFail($id) : $this->builder->find($id);
    }

    /**
     * @param string $column
     * @param string $value
     * @param bool $failOnNotFound
     * @return Model
     */
    public function firstBy(string $column, string $value, bool $failOnNotFound = true): Model
    {
        $this->builder->where($column, $value);

        return $failOnNotFound ? $this->builder->firstOrFail() : $this->builder->first();
    }
}
