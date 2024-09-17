<?php

namespace App\Http\Requests;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\UnauthorizedException;

class TaskStoreRequest extends FormRequest
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
            'title' => 'required|string|max:30',
            'project_id' => 'required|integer|exists:projects,id',
            'description' => 'required|string|min:10|max:30',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required|date',
            'status' => 'required|in:new,in_progress,done',
            'assigned_to' => 'required|exists:users,id',
            'role' => 'required|in:manager,developer,tester'
        ];
    }



    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The task title is required.',
            'title.max' => 'The task title cannot exceed 30 characters.',
            'project_id.required' => 'A project ID is required.',
            'project_id.exists' => 'The selected project does not exist.',
            'description.required' => 'The task description is required.',
            'description.min' => 'The task description must be at least 10 characters long.',
            'description.max' => 'The task description cannot exceed 30 characters.',
            'priority.required' => 'Priority is required.',
            'priority.in' => 'Priority must be one of: low, medium, high.',
            'due_date.required' => 'A due date is required.',
            'due_date.date' => 'The due date must be a valid date.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be one of: new, in progress, done.',
            'assigned_to.required' => 'A user must be assigned to the task.',
            'assigned_to.exists' => 'The assigned user does not exist.',
            'role.required' => 'A role is required for the assigned user.',
            'role.in' => 'Role must be one of: manager, developer, tester.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }

}
