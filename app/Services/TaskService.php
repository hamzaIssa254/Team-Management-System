<?php

namespace App\Services;

use Exception;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TaskService
{
    /**
     * Summary of getAllTasks
     * @param array $filters
     * @param int $perPage
     * @throws \Exception
     * @return mixed
     */
    public function getAllTasks(array $filters, int $perPage)
    {
        try {
            // Generate a unique cache key based on filters and pagination
            $cacheKey = 'tasks_' . md5(json_encode($filters) . $perPage . request('page', 1));

            // Check if the cached result exists
            $tasks = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($filters, $perPage) {

                $priority = $filters['priority'] ?? null;
                $status = $filters['status'] ?? null;
                $project = $filters['project'] ?? null;

                $query = Task::query();


                if ($project) {
                    $query->whereRelation('project', 'id', $project);
                }


                if ($priority) {
                    $query->where('priority', $priority);
                }

                if ($status) {
                    $query->where('status', $status);
                }

                return $query->paginate($perPage);
            });

            return $tasks;
        } catch (Exception $e) {
            Log::error('error listing tasks ' . $e->getMessage());
            throw new Exception('there is something wrong');
        }
    }
    /**
     * Summary of createTask
     * @param array $data
     * @param \App\Models\Project $project
     * @return void
     */
    public function createTask(array $data, Project $project)
    {
        DB::beginTransaction();
        try {
            $task = Task::create([
                'title' => $data['title'],
                'project_id' => $data['project_id'],
                'description' => $data['description'],
                'assigned_to' => $data['assigned_to'],
                'due_date' => $data['due_date'],
                'status' => $data['status'],
                'priority' => $data['priority']
            ]);
            $user = User::find($data['assigned_to']);
            if ($user) {
                $project->users()->attach($user->id, [
                    'role' => $data['role'],
                    'contribution_hours' => 0,
                    'last_activity' => now(),
                    'project_id' => $data['project_id'],
                    'task_id' => $task->id
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            Log::error('cannot create this task ' . $e->getMessage());
        }
    }
    /**
     * Summary of getTask
     * @param \App\Models\Task $task
     * @return Task
     */
    public function getTask(Task $task)
    {
        try {
            return $task;
        } catch (Exception $e) {
            Log::error('cannot get this task ' . $e->getMessage());
        }
    }
    /**
     * Summary of updateTask
     * @param array $data
     * @param \App\Models\Task $task
     * @throws \Exception
     * @return void
     */
    public function updateTask(array $data, Task $task)
    {
        DB::beginTransaction(); // Start a transaction to ensure data consistency
    try {
        // Update the task details
        $task->update(array_filter($data));




        DB::commit(); // Commit transaction after successful updates
    } catch (Exception $e) {
        DB::rollBack(); // Rollback changes if an error occurs
        Log::error('Cannot update this task: ' . $e->getMessage());
        throw new Exception('Failed to update task: ' . $e->getMessage());
    }
    }
    /**
     * Summary of deleteTask
     * @param \App\Models\Task $task
     * @return void
     */
    public function deleteTask(Task $task)
    {
        try {
            $task->delete();
        } catch (Exception $e) {
            Log::error('cannot delete this task ' . $e->getMessage());
        }
    }
    /**
     * Summary of updateTaskStatus
     * @param array $data
     * @param \App\Models\Task $task
     * @param \App\Models\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateTaskStatus(array $data, Task $task, User $user)
    {

        $role = $user->projects()->where('project_id', $task->project_id)->first()->pivot->role;

        if ($role == 'developer' && $data['status']) {
            $task->status = $data['status'];
            $task->save();
        } elseif ($role == 'manager') {
            $task->update(array_filter(($data)));
        }

        return response()->json(['message' => 'Task updated successfully']);
    }
    /**
     * Summary of updateTaskContributionHours
     * @param \App\Models\User $user
     * @param \App\Models\Project $project
     * @param mixed $hours
     * @return void
     */
    public function updateTaskContributionHours(User $user, Project $project, $hours)
    {
        // تحديث الساعات التي ساهم بها المطور في المشروع
        $project->users()->updateExistingPivot($user->id, [
            'contribution_hours' => $hours,
            'last_activity' => now(),
        ]);
    }
    /**
     * Summary of completeTask
     * @param \App\Models\Task $task
     * @param \App\Models\User $user
     * @param \App\Models\Project $project
     * @return void
     */
    public function completeTask(Task $task, User $user, Project $project)
    {
        // حساب الساعات التي ساهم بها المطور بناءً على فرق الوقت بين آخر نشاط والوقت الحالي
        $lastActivity = $project->users()->find($user->id)->pivot->last_activity;
        $contributionHours = now()->diffInHours($lastActivity);

        // تحديث عدد ساعات المساهمة
        $this->updateTaskContributionHours($user, $project, $contributionHours);

        // تحديث حالة المهمة إلى منجزة
        $task->update(['status' => 'done']);
    }
    /**
     * Summary of getUserTasks
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserTasks(User $user)
    {
        try {
            // $user = User::findOrFail($id);

            return $user->tasks()->get();
        } catch (Exception $e) {
            Log::error('cannot get tasks ' . $e->getMessage());
        }
    }
    /**
     * Summary of addNote
     * @param \App\Models\Task $task
     * @param mixed $data
     * @return void
     */
    public function addNote(Task $task, $data)
    {
        try {
            $user = Auth::user();


            $task->notes()->create([
                'user_id' => $user->id,
                'note' => $data['note'],
            ]);
        } catch (Exception $e) {
            Log::error('Cannot add note: ' . $e->getMessage());
        }
    }


}
