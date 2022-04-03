<?php

namespace App\Policies;

use App\Models\AwardingBody;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AwardingBodyPolicy
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
        if ($user->can('awarding_body_access')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AwardingBody  $awardingBody
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, AwardingBody $awardingBody)
    {
        if ($user->can('awarding_body_show')) {
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
        if ($user->can('awarding_body_create')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AwardingBody  $awardingBody
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, AwardingBody $awardingBody)
    {
        if ($user->can('awarding_body_edit')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AwardingBody  $awardingBody
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, AwardingBody $awardingBody)
    {
        if ($user->can('awarding_body_delete')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AwardingBody  $awardingBody
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, AwardingBody $awardingBody)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AwardingBody  $awardingBody
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, AwardingBody $awardingBody)
    {
        //
    }
}
