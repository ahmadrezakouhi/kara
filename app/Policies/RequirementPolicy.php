<?php

namespace App\Policies;

use App\Models\Requirement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RequirementPolicy
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
        if($project_user && ($project_user->pivot->owner || $project_user->pivot->admin )){
            return Response::allow();
        }
        return Response::deny('مجوز مشاهده نیازمندی ها وجود ندارد.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Requirement  $requirement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Requirement $requirement)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user , $project_id)
    {
        $project_user = $user->projects->find($project_id);
        if($project_user && ($project_user->pivot->owner || $project_user->pivot->admin)){
            return Response::allow();
        }
        return Response::deny('مجوز ایجاد نیازمندی ها وجود ندارد.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Requirement  $requirement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Requirement $requirement)
    {
        if($user->id != $requirement->user_id){
            return Response::deny('مجوز به روز رسانی برای فاز وجود ندارد.');
        }

        if($requirement->phase != null){
            return Response::deny('نیازمندی دارای فاز می باشد.');
        }

        return Response::allow();

    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Requirement  $requirement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Requirement $requirement)
    {


        if($user->id != $requirement->user_id){
            return Response::deny('مجوز حذف برای فاز وجود ندارد.');
        }

        if($requirement->phase != null){
            return Response::deny('نیازمندی دارای فاز می باشد.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Requirement  $requirement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Requirement $requirement)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Requirement  $requirement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Requirement $requirement)
    {
        //
    }


    public function add_phase(User $user , Requirement $requirement){
       $project_user = $user->projects->find($requirement->project->id); //$user->projects->find($requirement->project->id);
       if($project_user && $project_user->pivot->admin){
        return Response::allow();
       }
       return Response::deny('مجوز  لینک دادن نیازمندی به فاز را ندارید.');
    }
}
