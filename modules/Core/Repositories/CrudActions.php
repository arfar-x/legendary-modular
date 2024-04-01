<?php

namespace Modules\Core\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

trait CrudActions
{
    /**
     * @var RepositoryInterface
     */
    protected RepositoryInterface $repo;

    /**
     * Get the list of resources queried by filters.
     *
     * @param array $parameters
     * @param string|null $modelResource
     * @return LengthAwarePaginator
     */
    public function list(array $parameters = [], ?string $modelResource = null): LengthAwarePaginator
    {
        return $this->repo->paginated($parameters, $modelResource);
    }

    /**
     * Create a new resource.
     *
     * @param array $parameters
     * @return Model
     */
    public function store(array $parameters = []): Model
    {
        return $this->repo->store($parameters);
    }

    /**
     * Show the specified resource.
     *
     * @param string $id
     * @return Model
     */
    public function show(string $id): Model
    {
        return $this->repo->find($id);
    }

    /**
     * Update the resource model.
     *
     * @param Model $model
     * @param array $parameters
     * @return Model|bool
     */
    public function update(Model $model, array $parameters = []): Model|bool
    {
        return $this->repo->update($model, $parameters);
    }

    /**
     * Destroy the resource model.
     *
     * @param Model $model
     * @param bool $forceDelete
     * @return bool
     */
    public function destroy(Model $model, bool $forceDelete = false): bool
    {
        if ($forceDelete) {
            return $this->repo->forceDelete($model);
        }

        return $this->repo->delete($model);
    }

    /**
     * Destroy given resources.
     *
     * @param array $ids
     * @return bool
     */
    public function massDestroy(array $ids): bool
    {
        return $this->repo->massDelete($ids);
    }
}
