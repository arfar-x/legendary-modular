<?php

namespace Modules\FileManager\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;
use Modules\Core\Repositories\BaseRepository;
use Modules\Core\Repositories\CrudActions;
use Modules\Core\Repositories\RepositoryInterface;
use Modules\FileManager\Models\File;
use Modules\User\Models\User;

class FileService
{
    use CrudActions;

    /**
     * @var User
     */
    protected Model $file;

    /**
     * @var RepositoryInterface
     */
    protected RepositoryInterface $repo;

    public function __construct()
    {
        $this->file = new File();
        $this->repo = new BaseRepository($this->file);
    }

    /**
     * Get the files of the specified scope in a paginated form.
     *
     * @param string $scope
     * @param array $parameters
     * @param bool $withTempUrl
     * @return LengthAwarePaginator
     */
    public function getByScope(string $scope, array $parameters = [], bool $withTempUrl = true): LengthAwarePaginator
    {
        $result = $this->list(array_merge_recursive([
            'query' => [[
                'operator' => '=',
                'field' => 'scope',
                'value' => $scope
            ]]
        ], $parameters));

        if ($withTempUrl) {
            $total = $result->total();
            $perPage = $result->perPage();
            $currentPage = $result->currentPage();
            // If we call map() method on a LengthAwarePaginator instance, it would return a Collection instance.
            // So, we have to create another instance of LengthAwarePaginator and return it.
            $result = new LengthAwarePaginator($result->map(function ($item) {
                return [$item->id => $this->tempUrl($item)];
            }), $total, $perPage, $currentPage);
        }

        return $result;
    }

    /**
     * Store a file.
     *
     * @param UploadedFile $file
     * @param array $parameters
     * @return Model|bool
     */
    public function storeFile(UploadedFile $file, array $parameters = []): Model|bool
    {
        return $this->file->storeFile($file, $parameters);
    }

    /**
     * @param string $id
     * @return File
     */
    public function show(string $id): File
    {
        /** @var File */
        return $this->repo->find($id);
    }

    /**
     * @param string $slug
     * @return File
     */
    public function showBySlug(string $slug): File
    {
        /** @var File */
        return $this->repo->firstBy('slug', $slug);
    }

    /**
     * Show the file content accessed by the temporary URL.
     *
     * @param string $id
     * @return string|null
     */
    public function showAsTempUrl(string $id): string|null
    {
        /** @var File $file */
        $file = $this->file->query()->findOrFail($id);

        if (!URL::hasValidSignature(request(), false)) {
            return null;
        }

        return $file->path;
    }

    /**
     * Generate temporary URL for the file.
     *
     * @param File $file
     * @param int $ttlMinutes
     * @return string
     */
    public function tempUrl(File $file, int $ttlMinutes = 0): string
    {
        if (!$ttlMinutes) {
            $ttlMinutes = config('file-manager.temp_url.expires_after', 1);
        }

        $ttlMinutes = now()->addMinutes($ttlMinutes);

        return URL::temporarySignedRoute(
            config('file-manager.temp_url.url_name'),
            $ttlMinutes,
            ['file' => $file->id],
            config('file-manager.temp_url.absolute', false)
        );
    }

    /**
     * Destroy the file.
     *
     * @param File $file
     * @param bool $forceDelete
     * @return bool
     */
    public function destroy(File $file, bool $forceDelete = false): bool
    {
        if ($file->deleteByPath($file->path)) {
            return $this->repo->delete($file);
        }

        return false;
    }

    /**
     * Destroy the file by the specified slug.
     *
     * @param string $slug
     * @return bool
     */
    public function destroyBySlug(string $slug): bool
    {
        /** @var File $file */
        $file = $this->repo->firstBy('slug', $slug);

        if ($file->deleteByPath($file->path)) {
            return $this->repo->delete($file);
        }

        return false;
    }
}
