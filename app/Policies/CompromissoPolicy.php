<?php

namespace App\Policies;

use App\Models\Compromisso;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CompromissoPolicy
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
     * @param  \App\Models\Compromisso  $compromisso
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Compromisso $compromisso)
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
        return $user->id <= 2 ? Response::allow() : Response::deny('Ação vedada aos visitantes!');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Compromisso  $compromisso
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Compromisso $compromisso)
    {
        //
    }

    public function completarCompromisso(User $user)
    {
        return $user->id <= 2 ? Response::allow() : Response::deny('Visitantes não podem completar compromissos!');
    }

    public function deletarCompromisso(User $user)
    {
        return $user->id <= 2 ? Response::allow() : Response::deny('Visitantes não podem deletar compromissos!');
    }
    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Compromisso  $compromisso
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Compromisso $compromisso)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Compromisso  $compromisso
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Compromisso $compromisso)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Compromisso  $compromisso
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Compromisso $compromisso)
    {
        //
    }
}
