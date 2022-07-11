<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Project $project)
    {
        $project_user = $user->projects->find($project->id);
        return $project_user ?
         Response::allow():Response::deny('امکان مشاهده پروژه مورد نظر وجود ندارد.');

    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->isAdmin() ?
        Response::allow():Response::deny('مجوز ایجاد پروژه برای شما وجود ندارد');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        //
        return $user->isAdmin() ?
        Response::allow() : Response::deny('دسترسی ادیت پروژه وجود ندارد.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Project $project)
    {

        if($project->requirements()->count() != 0){
            return Response::deny('پروژه دارای نیازمندی می باشد');
        }

        if($project->phases()->count() != 0){
            return Response::deny('پروژه دارای فاز می باشد.');
        }

        if(!$user->isAdmin()){
            return Response::deny('امکان حذف پروژه وجود ندارد.');
        }

        if($project->child()->count() != 0){
            return Response::deny('پروژه زیر پروژه دارد.');
        }
        return Response::allow();

    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Project $project)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Project $project)
    {
        //
    }

    public function addUser($user){
        return $user->isAdmin() ?
        Response::allow() : Response::deny('دسترسی افزودن کاربر برای پروژه را ندارید.');
    }
}
