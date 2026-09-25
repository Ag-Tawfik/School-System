<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadStudentAttachmentRequest extends FormRequest
{
    // Extensions are checked against the file's detected content type, not the client-supplied name.
    public const ALLOWED_MIMES = 'jpg,jpeg,png,gif,webp';

    public const MAX_KB = 5120;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id' => 'required|integer|exists:students,id',
            'photos' => 'required|array',
            'photos.*' => 'file|mimes:' . self::ALLOWED_MIMES . '|max:' . self::MAX_KB,
        ];
    }
}
