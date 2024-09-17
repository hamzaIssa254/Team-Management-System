<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class TaskUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'status' => 'nullable|in:new,in_progress,done',
        'priority' => 'nullable|in:low,medium,high',
        'assigned_to' => 'nullable|exists:users,id',
        'project_id' => 'nullable|exists:projects,id',
        'due_date' => 'nullable|date'


        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title cannot exceed 30 characters.',
            'description.string' => 'The description must be a string.',
            'description.min' => 'The description must be at least 10 characters.',
            'description.max' => 'The description cannot exceed 30 characters.',
            'priority.in' => 'The priority must be one of the following: low, medium, high.',
            'due_date.date' => 'The due date must be a valid date.',
            'status.in' => 'The status must be one of the following: pending, in_progress, completed.',
            'assigned_to.exists' => 'The selected assigned user must be a valid user.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422));
    }
}
