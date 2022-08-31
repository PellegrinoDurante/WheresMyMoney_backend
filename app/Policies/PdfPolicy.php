<?php

namespace App\Policies;

use App\Models\Pdf;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PdfPolicy
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
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Pdf $pdf
     * @return Response|bool
     */
    public function view(User $user, Pdf $pdf): Response|bool
    {
        return $user->id == $pdf->user_id;
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
     * @param Pdf $pdf
     * @return Response|bool
     */
    public function update(User $user, Pdf $pdf): Response|bool
    {
        return $user->id == $pdf->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Pdf $pdf
     * @return Response|bool
     */
    public function delete(User $user, Pdf $pdf): Response|bool
    {
        return $user->id == $pdf->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Pdf $pdf
     * @return Response|bool
     */
    public function restore(User $user, Pdf $pdf): Response|bool
    {
        return $user->id == $pdf->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Pdf $pdf
     * @return Response|bool
     */
    public function forceDelete(User $user, Pdf $pdf): Response|bool
    {
        return true;
    }
}
