<?php

namespace App\Policies;

use App\Models\Phase;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PhasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user , $project_id)
    {
        $project_user = $user->projects->find($project_id);
        return $project_user ?
        Response::allow() : Response::deny('امکان دیدن فازهای این پروژه وجود ندارد.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Phase  $phase
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Phase $phase)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user,$project_id)
    {

        $project_user = $user->projects->find($project_id);
        if($project_user && $project_user->pivot->status == 1 ){
            return Response::allow();
        }

        return Response::deny('مجوز ایجاد فاز برای این پروژه را ندارید.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Phase  $phase
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Phase $phase)
    {
        return $user->id == $phase->user_id ? Response::allow()
        :Response::deny('مجوز به روز رسانی فاز را ندارید.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Phase  $phase
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Phase $phase)
    {
        if($user->id != $phase->user_id){
            return Response::deny('مجوز حذف فاز مورد نظر را ندارید.');
        }else if($phase->sprints->count() != 0){
            return Response::deny('فاز مورد نظر دارای اسپرینت است');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Phase  $phase
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Phase $phase)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Phase  $phase
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Phase $phase)
    {
        //
    }
}
