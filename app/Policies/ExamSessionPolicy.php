<?php

namespace App\Policies;

use App\Models\ExamSession;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExamSessionPolicy
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
        if ($user->can('exam_session_access')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ExamSession  $examSession
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ExamSession $examSession)
    {
        if ($user->can('exam_session_show')) {
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
        if ($user->can('exam_session_create')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ExamSession  $examSession
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ExamSession $examSession)
    {
        if ($user->can('exam_session_edit')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ExamSession  $examSession
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ExamSession $examSession)
    {
        if ($user->can('exam_session_delete')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ExamSession  $examSession
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ExamSession $examSession)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ExamSession  $examSession
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ExamSession $examSession)
    {
        //
    }
}
