<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Schedules;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchedulesPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Schedules');
    }

    public function view(AuthUser $authUser, Schedules $schedules): bool
    {
        return $authUser->can('View:Schedules');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Schedules');
    }

    public function update(AuthUser $authUser, Schedules $schedules): bool
    {
        return $authUser->can('Update:Schedules');
    }

    public function delete(AuthUser $authUser, Schedules $schedules): bool
    {
        return $authUser->can('Delete:Schedules');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Schedules');
    }

    public function restore(AuthUser $authUser, Schedules $schedules): bool
    {
        return $authUser->can('Restore:Schedules');
    }

    public function forceDelete(AuthUser $authUser, Schedules $schedules): bool
    {
        return $authUser->can('ForceDelete:Schedules');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Schedules');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Schedules');
    }

    public function replicate(AuthUser $authUser, Schedules $schedules): bool
    {
        return $authUser->can('Replicate:Schedules');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Schedules');
    }

}