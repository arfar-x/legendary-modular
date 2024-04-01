<?php

namespace Modules\FileManager\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $updated_at
 * @property mixed $created_at
 * @property mixed $public_path
 * @property mixed $extension
 * @property mixed $mime_type
 * @property mixed $type
 * @property mixed $slug
 * @property mixed $name
 * @property mixed $id
 */
class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,
            'mime_type' => $this->mime_type,
            'extension' => $this->extension,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
