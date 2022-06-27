<?php

namespace App\Policies;

use App\Models\Sprint;
use App\Models\User;
use App\Models\Phase;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SprintPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user , $phase_id)
    {
        $phase = Phase::find($phase_id);
        $project = $phase->project;
        $project_user = $user->projects->find($project->id);
        return ($project_user && $project_user->pivot->status != 0) ?
            Response::allow() : Response::deny('مجوز مشاهده اسپرینت وجود ندارد.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sprint  $sprint
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Sprint $sprint)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user,$phase_id)
    {
        $phase = Phase::find($phase_id);
        $project = $phase->project;
        $project_user = $user->projects->find($project->id);
        if ($project_user && $project_user->pivot->status == 1) {
            return Response::allow();
        }

        return Response::deny('مجوز ایجاد اسپرینت را ندارید.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sprint  $sprint
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Sprint $sprint)
    {
        return $user->id == $sprint->user_id ?
            Response::allow() :
            Response::deny('مجوز تغییر اسپرینت را ندارید.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sprint  $sprint
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Sprint $sprint)
    {
        if ($user->id != $sprint->user_id) {
            return Response::deny('مجوز حذف اسپرینت رو ندارید.');
        } else if ($sprint->tasks->count() != 0) {
            return Response::deny('اسپرینت مورد نظر دارای تسک می باشد.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sprint  $sprint
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Sprint $sprint)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sprint  $sprint
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Sprint $sprint)
    {
        //
    }
}
