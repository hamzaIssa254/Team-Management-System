<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];
    /**
     * Summary of tasks
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    /**
     * Summary of users
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role', 'contribution_hours', 'last_activity')->withTimestamps();
    }
    /**
     * Summary of highestPriorityTaskWithCondition
     * @param mixed $titleCondition
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function highestPriorityTaskWithCondition($titleCondition)
    {
        return $this->hasOne(Task::class)->ofMany('priority', 'max', function ($query) use ($titleCondition) {
            $query->where('title', 'like', "%$titleCondition%");
        });
    }
    /**
     * Summary of latestTask
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestTask()
    {
        return $this->hasOne(Task::class)->latestOfMany();
    }
    /**
     * Summary of oldestTask
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function oldestTask()
    {
        return $this->hasOne(Task::class)->oldestOfMany();
    }
}
