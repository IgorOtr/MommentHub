<?php

namespace App\Http\Requests;

use App\Services\GoogleDriveService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FolderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $folderId = $this->route('folder')?->id;

        return [
            'event_id' => ['required', 'exists:events,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('folders', 'slug')->ignore($folderId)],
            'description' => ['nullable', 'string'],
            'google_drive_url' => [
                'required',
                'url',
                function ($attribute, $value, $fail) {
                    if (! app(GoogleDriveService::class)->isValidFolderUrl($value)) {
                        $fail('O link informado não é uma pasta pública válida do Google Drive.');
                    }
                },
            ],
            'is_public' => ['sometimes', 'boolean'],
        ];
    }
}
