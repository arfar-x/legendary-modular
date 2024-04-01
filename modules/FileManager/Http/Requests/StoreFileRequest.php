<?php

namespace Modules\FileManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\FileManager\Models\File;

class StoreFileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'scope' => ['sometimes', 'required', 'string', 'min:3'],
            'type' => ['required', 'string', Rule::in(File::FILE_TYPE_IMAGE, File::FILE_TYPE_VIDEO)],
            'is_public' => ['required', 'bool'],
            'file' => [
                'required',
                'file',
                'max:'.(File::MAX_FILE_SIZE / 1024),
                'mimes:jpg,jpeg,png,gif,bmp,pdf,mkv,mp4,m4v,wav,wave,wmv,mpeg'
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // We do this because Laravel's form-request validation can recognize data sent from form-data object in the
        // request; so, we pass arguments as query-strings then merge it with the request's main payload.
        $this->merge($this->query());
    }
}
