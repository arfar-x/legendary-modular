<?php

namespace Modules\FileManager\Models;

use Exception;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @property string id
 * @property string scope
 * @property string slug
 * @property string name
 * @property string type
 * @property string extension
 * @property string mime_type
 * @property string path
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class File extends Model
{
    use HasUuids;

    const FILE_TYPE_IMAGE = 'image';
    const FILE_TYPE_VIDEO = 'video';

    const MAX_FILE_SIZE = 10485760; // TODO: Make it readable

    protected $fillable = [
        'id',
        'scope',
        'slug',
        'name',
        'type',
        'extension',
        'mime_type',
        'path',
        'is_public',
        'created_at',
        'updated_at'
    ];

    /**
     * Get directory name for scope.
     *
     * @param string $scope
     * @param bool $absolute
     * @return string
     */
    public static function getScopePath(string $scope = 'default', bool $absolute = false): string
    {
        return $absolute ? storage_path($scope) : $scope;
    }

    /**
     * Create a new file record in the database while persisting it on the file disk.
     *
     * @param UploadedFile $file
     * @param array $parameters
     * @return Model|bool
     */
    public function storeFile(UploadedFile $file, array $parameters = []): Model|bool
    {
        // Extract file name/slug.
        $name = explode('.', $file->getClientOriginalName());
        Arr::forget($name, array_key_last($name));
        $scope = $parameters['scope'] ?? 'default';
        $slug = Str::slug("$scope-" . implode($name));
        $name = $slug . '_' . Carbon::now()->format('Y-m-d_H-i-s-u');
        $fullName = "$name.{$file->extension()}";

        // If the file record already exists in the database return it immediately.
        if ($fileRecord = $this->query()->where([
            'scope' => "$scope",
            'slug' => $slug,
            'is_public' => $parameters['is_public']
        ])->first()) {
            return $fileRecord;
        }

        try {
            DB::beginTransaction();

            $filePath = $this->persistFile($file, $scope, $fullName, $parameters['is_public']);

            $file = $this->query()->create([
                'slug' => $slug,
                'is_public' => (bool)$parameters['is_public'],
                'scope' => $scope,
                'name' => $name,
                'type' => $parameters['type'],
                'mime_type' => $file->getMimeType(),
                'extension' => $file->extension(),
                'path' => $filePath
            ]);

            DB::commit();

            return $file;

        } catch (Exception $exception) {
            DB::rollBack();

            $this->deleteByPath($this->getPath($scope, (bool)$parameters['is_public']) . '/' . $fullName);

            Log::error($exception->getMessage(), $exception->getTrace());
        }

        return false;
    }

    /**
     * Persist uploaded-file on the file disk.
     *
     * @param UploadedFile $file
     * @param string $scope
     * @param string $name
     * @param bool $isPublic
     * @return string
     */
    public function persistFile(UploadedFile $file, string $scope, string $name, bool $isPublic): string
    {
        return $file->storeAs($this->getPath($scope, $isPublic), $name);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function deleteByPath(string $path): bool
    {
        return Storage::delete($path);
    }

    /**
     * @param string $scope
     * @param bool $isPublic
     * @return string
     */
    public function getPath(string $scope, bool $isPublic = false): string
    {
        return $isPublic ? 'public' : static::getScopePath($scope);
    }
}
