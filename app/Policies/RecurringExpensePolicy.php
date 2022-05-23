<?php

namespace App\Policies;

use App\Models\RecurringExpense;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RecurringExpensePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user): Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param RecurringExpense $recurringExpense
     * @return Response|bool
     */
    public function view(User $user, RecurringExpense $recurringExpense): Response|bool
    {
        return $user->id == $recurringExpense->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user): Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param RecurringExpense $recurringExpense
     * @return Response|bool
     */
    public function update(User $user, RecurringExpense $recurringExpense): Response|bool
    {
        return $user->id == $recurringExpense->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param RecurringExpense $recurringExpense
     * @return Response|bool
     */
    public function delete(User $user, RecurringExpense $recurringExpense): Response|bool
    {
        return $user->id == $recurringExpense->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param RecurringExpense $recurringExpense
     * @return Response|bool
     */
    public function restore(User $user, RecurringExpense $recurringExpense): Response|bool
    {
        return $user->id == $recurringExpense->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param RecurringExpense $recurringExpense
     * @return Response|bool
     */
    public function forceDelete(User $user, RecurringExpense $recurringExpense): Response|bool
    {
        return false;
    }
}
