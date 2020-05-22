<?php

namespace App\Managers;

use App\Model\User;
use App\Repository\OperationRepository;
use Services\DB;

/**
 * Add funds to the user bank account.
 */
class FundsAddingManager
{
    /**
     * @param User $user
     * @param int $amount Amount in cents.
     * @return array
     */
    public function addFundsToAccount(User $user, $amount)
    {
        $success = false;
        $message = '';

        try {
            $userBalanceAfterTransaction = $user->getAccountBalance() + $amount;

            $fundsWithdrawQuery = 'UPDATE `account` SET `account_balance` = :balance WHERE (`user_id` = :userId)';

            // Adding funds to the user account
            DB::query($fundsWithdrawQuery,
                [
                    'userId' => $user->getId(),
                    'balance' => $userBalanceAfterTransaction
                ],
                false
            );

            // Logging the action
            $operationRepository = new OperationRepository();
            $operationRepository->addOperation($user, null, OperationRepository::OPERATION_TYPE_FUNDS_ADD, $amount);

            $success = true;

        } catch (\Throwable $e) {
            $message = $e->getMessage();
        }

        return [
            'success' => $success,
            'message' => $message
        ];
    }
}
