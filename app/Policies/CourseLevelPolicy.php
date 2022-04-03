<?php

namespace App\Policies;

use App\Models\CourseLevel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourseLevelPolicy
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
        if ($user->can('course_level_access')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourseLevel  $courseLevel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CourseLevel $courseLevel)
    {
        if ($user->can('course_level_show')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if ($user->can('course_level_create')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourseLevel  $courseLevel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CourseLevel $courseLevel)
    {
        if ($user->can('course_level_edit')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourseLevel  $courseLevel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CourseLevel $courseLevel)
    {
        if ($user->can('course_level_delete')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourseLevel  $courseLevel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CourseLevel $courseLevel)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourseLevel  $courseLevel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CourseLevel $courseLevel)
    {
        //
    }
}
