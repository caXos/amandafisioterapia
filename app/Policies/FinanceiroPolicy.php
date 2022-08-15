<?php

namespace App\Policies;

use App\Models\Financeiro;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class FinanceiroPolicy
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
     * @param  \App\Models\Financeiro  $financeiro
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Financeiro $financeiro)
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
        // return $user->role <= 2 ? Response::allow() : Response::deny('Ação vedada aos visitantes!');
        // if ($request->user()->id <= 2) $this->authorize('completarCompromisso', Agenda::class);
        // if ($user->id <= 2) dd("Usuário é admin");
        // else dd("usuário não é admin");
        return $user->perfil <= 2 ? Response::allow() : Response::deny('Ação vedada aos visitantes!');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Financeiro  $financeiro
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Financeiro $financeiro)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Financeiro  $financeiro
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Financeiro $financeiro)
    {
        return $user->perfil <= 2 ? Response::allow() : Response::deny('Ação vedada aos visitantes!');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Financeiro  $financeiro
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Financeiro $financeiro)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Financeiro  $financeiro
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Financeiro $financeiro)
    {
        //
    }
}
