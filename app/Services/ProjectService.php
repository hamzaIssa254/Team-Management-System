<?php

namespace App\Services;

use Exception;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    /**
     * Summary of gettAllProject
     * @param array $filters
     * @param int $perPage
     * @throws \Exception
     * @return mixed
     */
    public function gettAllProject( array $filters,int $perPage)
    {
        try {
            // Generate a unique cache key based on filters and pagination
            $cacheKey = 'projects_' . $perPage . md5(json_encode($filters)) . request('page', 1);

            // Check if the cached result exists
            $projects = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($filters, $perPage) {

                // Start with the base query and eager load necessary relationships
                $query = Project::with(['users', 'tasks']);

                // Apply filters if they exist
                if (isset($filters['oldest'])) {
                    $query->with('oldestTask');
                }

                if (isset($filters['latest'])) {
                    $query->with('latestTask');
                }
                if (isset($filters['title_condition'])) {
                    $titleCondition = $filters['title_condition'];
                    // Eager load the highest priority task with the title condition
                    $query->with(['highestPriorityTaskWithCondition' => function ($query) use ($titleCondition) {
                        $query->where('title', 'like', "%$titleCondition%");
                    }]);
                }


                return $query->paginate($perPage);
            });

            return $projects;
        } catch (Exception $e) {
            Log::error('Error listing projects: ' . $e->getMessage());
            throw new Exception('There is something wrong');
        }
    }
    /**
     * Summary of createproject
     * @param array $data
     * @return void
     */
    public function createproject(array $data)
    {
        DB::beginTransaction();
        try {
         Project::create([
            'name' => $data['name'],
            'description' =>$data['description']
         ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('cannot create this project '.$e->getMessage());

        }
    }
    /**
     * Summary of getProject
     * @param \App\Models\Project $project
     * @return Project
     */
    public function getProject(Project $project)
    {
        try {
            return $project;
        } catch (Exception $e) {
            Log::error('cannot get this project '.$e->getMessage());

        }
    }
    /**
     * Summary of updateProject
     * @param array $data
     * @param \App\Models\Project $project
     * @return void
     */
    public function updateProject(array $data,Project $project)
    {
        try {
            $project->update(array_filter($data));
        }  catch (Exception $e) {
            Log::error('cannot update this project '.$e->getMessage());

        }
    }
    /**
     * Summary of deleteProject
     * @param \App\Models\Project $project
     * @return void
     */
    public function deleteProject(Project $project)
    {
        try {
            $project->delete();
        }  catch (Exception $e) {
            Log::error('cannot delete this project '.$e->getMessage());

        }
    }


}
