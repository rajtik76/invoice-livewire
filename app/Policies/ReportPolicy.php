<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Report $report): bool
    {
        return $user->id === $report->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Report $report): bool
    {
        return $this->view($user, $report);
    }

    public function delete(User $user, Report $report): bool
    {
        return $this->view($user, $report);
    }

    public function restore(User $user, Report $report): bool
    {
        return $this->view($user, $report);
    }

    public function forceDelete(User $user, Report $report): bool
    {
        return $this->view($user, $report);
    }
}
