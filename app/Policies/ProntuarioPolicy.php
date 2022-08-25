<?php

namespace App\Policies;

use App\Models\Prontuario;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProntuarioPolicy
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
     * @param  \App\Models\Prontuario  $prontuario
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Prontuario $prontuario)
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
        return $user->perfil <= 2 ? Response::allow() : Response::deny('Ação vedada aos visitantes!');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Prontuario  $prontuario
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user/*, Prontuario $prontuario*/)
    {
        return $user->perfil <= 2 ? Response::allow() : Response::deny('Ação vedada aos visitantes!');
    }

    public function deletarProntuario(User $user/*, Prontuario $prontuario*/)
    {
        return $user->perfil <= 2 ? Response::allow() : Response::deny('Ação vedada aos visitantes!');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Prontuario  $prontuario
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Prontuario $prontuario)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Prontuario  $prontuario
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Prontuario $prontuario)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Prontuario  $prontuario
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Prontuario $prontuario)
    {
        //
    }
}
