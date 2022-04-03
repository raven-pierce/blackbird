<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VariantLecture;
use Illuminate\Auth\Access\HandlesAuthorization;

class VariantLecturePolicy
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
        if ($user->can('lecture_access')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VariantLecture  $variantLecture
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, VariantLecture $variantLecture)
    {
        if ($user->can('lecture_show')) {
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
        if ($user->can('lecture_create')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VariantLecture  $variantLecture
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, VariantLecture $variantLecture)
    {
        if ($user->can('lecture_edit')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VariantLecture  $variantLecture
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, VariantLecture $variantLecture)
    {
        if ($user->can('lecture_delete')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VariantLecture  $variantLecture
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, VariantLecture $variantLecture)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VariantLecture  $variantLecture
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, VariantLecture $variantLecture)
    {
        //
    }
}
