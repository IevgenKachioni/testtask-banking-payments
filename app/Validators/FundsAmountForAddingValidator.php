<?php

namespace App\Validators;

class FundsAmountForAddingValidator
{
    const MAX_ALLOWED_FUNDS_ADDING_AMOUNT = 500000; // 5000 Euro

    /**
     * @param integer $amount
     * @return boolean
     * @throws \Exception
     */
    public function validate($amount)
    {
        return ($amount <= static::MAX_ALLOWED_FUNDS_ADDING_AMOUNT);
    }
}
