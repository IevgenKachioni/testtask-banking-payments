<?php

namespace App\Validators;

use App\Model\User;

class AvailableFundsForWithdrawValidator
{
    /**
     * @param User $user
     * @param $amount
     * @return bool|void
     * @throws \Exception
     */
    public function validate(User $user, $amount)
    {
        return ($user->getAccountBalance() - $amount >= 0);
    }
}
