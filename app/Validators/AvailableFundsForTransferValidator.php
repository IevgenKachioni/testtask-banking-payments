<?php

namespace App\Validators;

use App\Model\User;

class AvailableFundsForTransferValidator
{
    const MAX_CREDIT_LIMIT = 20000; // 200 Euro

    /**
     * @param User $sender
     * @param $amount
     * @return bool|void
     * @throws \Exception
     */
    public function validate(User $sender, $amount)
    {
        // Checks if credit limit will not be exceeded after a transaction
        return ($sender->getAccountBalance() - $amount + static::MAX_CREDIT_LIMIT >= 0);
    }
}
