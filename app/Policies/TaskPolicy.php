<?php

namespace App\Policies;

use App\Models\Sprint;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user ,$sprint_id)
    {
        $sprint = Sprint::find($sprint_id);
        $project = $sprint->phase->project;
        $project_user = $user->projects->find($project->id);
        return ($project_user && $project_user->pivot->status != 0) ?
        Response::allow() : Response::deny('مجوز مشاهده تسک ها را ندارید.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Task $task)
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user ,$sprint_id)
    {
        $sprint = Sprint::find($sprint_id);
        $project = $sprint->phase->project;
        $project_user = $user->projects->find($project->id);
        if($project_user && $project_user->pivot->status == 2){
            return Response::allow();
        }

        return Response::deny('مجوز ایجاد تسک را ندارید.');



    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Task $task)
    {
        //
        if ($task) {
            if ($user->id != $task->user_id) {
                return Response::deny('مجوز برای به روز رسانی این تسک را ندارید.');
            } else if ($task->todo_date != null) {
                return Response::deny('تسک تایید شده را نمی توان به روز کرد.');
            }
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Task $task)
    {
        //
        if ($user->id != $task->user_id) {
            return Response::deny('مجوز برای حذف این تسک را ندارید.');
        } else if ($task->todo_date != null) {
            return Response::deny('تسک تایید شده را نمی توان حذف کرد.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Task $task)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Task $task)
    {
        //
    }
}
