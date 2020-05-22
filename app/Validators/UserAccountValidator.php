<?php

namespace App\Validators;

use App\Model\User;

class UserAccountValidator
{
    /**
     * @param User $user
     * @return boolean
     * @throws \Exception
     */
    public function validate($user)
    {
        return ($user && is_object($user) && $user->getIsActive());
    }
}
