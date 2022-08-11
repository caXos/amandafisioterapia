<?php

namespace App\Policies;

use App\Models\Agenda;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AgendaPolicy
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
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Agenda $agenda)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
        // return $user->id <= 2;
        return $user->id <= 2 ? Response::allow() : Response::deny('Ação vedada aos visitantes!');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Agenda $agenda)
    {
        //
    }

    public function completarCompromisso(User $user)
    {
        return $user->id <= 2 ? Response::allow() : Response::deny('teste deny');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Agenda $agenda)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Agenda $agenda)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Agenda $agenda)
    {
        //
    }
}
