<?php

namespace App\Policies;

use App\Delegate;
use App\Paper;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaperPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Delegate  $delegate
     * @return mixed
     */
    public function viewAny(Delegate $delegate)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Delegate  $delegate
     * @param  \App\Paper  $paper
     * @return mixed
     */
    public function view(Delegate $delegate, Paper $paper)
    {
        return $delegate->id === $paper->delegate_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Delegate  $delegate
     * @return mixed
     */
    public function create(Delegate $delegate)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Delegate  $delegate
     * @param  \App\Paper  $paper
     * @return mixed
     */
    public function update(Delegate $delegate, Paper $paper)
    {
//        dd($delegate->id, $paper->delegate_id);
        return auth('delegate')->id() === $paper->delegate_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Delegate  $delegate
     * @param  \App\Paper  $paper
     * @return mixed
     */
    public function delete(Delegate $delegate, Paper $paper)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Delegate  $delegate
     * @param  \App\Paper  $paper
     * @return mixed
     */
    public function restore(Delegate $delegate, Paper $paper)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Delegate  $delegate
     * @param  \App\Paper  $paper
     * @return mixed
     */
    public function forceDelete(Delegate $delegate, Paper $paper)
    {
        //
    }
}
