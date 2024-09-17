<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Services\ProjectService;
use App\Services\ApiResponseService;

class ProjectController extends Controller
{
    protected $projectService;
    /**
     * Summary of __construct
     * @param \App\Services\ProjectService $projectService
     */
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }
    /**
     * Summary of index
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $filters = $request->only(['priority','status']);
        $perPage = $request->input('per_page', 15);
        $projects = $this->projectService->gettAllProject($filters,$perPage);
        return ApiResponseService::paginated($projects,'projects retrive success');
    }

    /**
     * Summary of store
     * @param \App\Http\Requests\ProjectStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProjectStoreRequest $request)
    {
        $data = $request->validated();
        $this->projectService->createproject($data);
        return ApiResponseService::success(null,'project created success',201);
    }

    /**
     * Summary of show
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Project $project)
    {
        $this->projectService->getProject($project);
        return ApiResponseService::success($project,'project retrive success');
    }

    /**
     * Summary of update
     * @param \App\Http\Requests\ProjectUpdateRequest $request
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $data = $request->validated();
       $this->projectService->updateProject($data,$project);
        return ApiResponseService::success(null,'project update success');
    }

    /**
     * Summary of destroy
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Project $project)
    {
        $this->projectService->deleteProject($project);
        return ApiResponseService::success(null,'project delete successfully',201);

    }
}
