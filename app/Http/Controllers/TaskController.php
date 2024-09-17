<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Requests\addNotRequest;
use App\Services\ApiResponseService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {

        $this->taskService = $taskService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $filters = $request->only(['project', 'status', 'priority']);


        $perPage = $request->input('per_page', 15);


        $tasks = $this->taskService->getAllTasks($filters, $perPage);


        return ApiResponseService::paginated($tasks, 'tasks retrieved successfully');
    }

    /**
     * Summary of store
     * @param \App\Http\Requests\TaskStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TaskStoreRequest $request, Project $project)
    {
        $data = $request->validated();
        $this->taskService->createTask($data, $project);
        return ApiResponseService::success(null, 'task created success');
    }

    /**
     * Summary of show
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Task $task)
    {
        $this->taskService->getTask($task);
        return ApiResponseService::success($task, 'task retrive success');
    }

    /**
     * Summary of update
     * @param \App\Http\Requests\TaskUpdateRequest $request
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TaskUpdateRequest $request, Task $task, Project $project)
{
    $data = $request->validated();
    $this->taskService->updateTask($data, $task, $project);
    return ApiResponseService::success(null, 'Task update success');
}

    /**
     * Summary of destroy
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Task $task)
    {
        $this->taskService->deleteTask($task);

        return ApiResponseService::success(null, 'task deleted success');
    }
    /**
     * Summary of updateContribution
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Project $project
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateContribution(Request $request, Project $project)
    {
        $user = Auth::user();
        $hours = $request->input('hours');


        $this->taskService->updateTaskContributionHours($user, $project, $hours);

        return response()->json(['message' => 'Contribution hours updated successfully.']);
    }

    /**
     * Summary of completeTask
     * @param \App\Models\Project $project
     * @param \App\Models\Task $task
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function completeTask(Project $project, Task $task,)
    {
        $user = Auth::user();


        $this->taskService->completeTask($task, $user, $project);

        return response()->json(['message' => 'Task marked as completed successfully.']);
    }
    /**
     * Summary of userTasks
     * @return \Illuminate\Http\JsonResponse
     */
    public function userTasks()
    {
        $user = Auth::user();
        $task = $this->taskService->getUserTasks($user);
        return ApiResponseService::success($task);
    }
    /**
     * Summary of addNote
     * @param \App\Models\Project $project
     * @param \App\Models\Task $task
     * @param \App\Http\Requests\addNotRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addNote(Project $project, Task $task, AddNotRequest $request)
    {
        $data = $request->validated();
        $this->taskService->addNote($task, $data);
        return ApiResponseService::success(null, 'Note added successfully', 201);
    }


}
