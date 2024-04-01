<?php

namespace Modules\FileManager\Policies;

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\User\Services\Policy\CompatibleWithPolicyService;

class FilePolicy
{
    use CompatibleWithPolicyService;

    /**
     * Determine the 'view' action is always accessible for all users regardless their role-permission.
     *
     * @param Authenticatable|null $user
     * @return bool
     */
    public function view(?Authenticatable $user): bool
    {
        return true;
    }
}
