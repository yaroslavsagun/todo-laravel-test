<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetTasksRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "filters.status" => Rule::enum(TaskStatus::class),
            "filters.priority" => "integer|min:1|max:5",
            "filters.title" => "string|min:1",
            "filters.description" => "string|min:1",
            "sortBy.*" => "in:asc,desc",
        ];
    }
}
