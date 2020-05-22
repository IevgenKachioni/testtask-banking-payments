<?php

namespace App\Managers;

use App\Model\User;
use App\Repository\OperationRepository;
use Services\DB;

/**
 * Withdrawing funds from user bank account.
 */
class FundsWithdrawManager
{
    /**
     * @param User $user
     * @param int $amount Amount in cents.
     * @return array
     */
    public function withdrawFunds(User $user, $amount)
    {
        $success = false;
        $message = '';

        try {
            $userBalanceAfterTransaction = $user->getAccountBalance() - $amount;

            $fundsWithdrawQuery = 'UPDATE `account` SET `account_balance` = :balance WHERE (`user_id` = :userId)';

            // Removing funds from the user account
            DB::query($fundsWithdrawQuery,
                [
                    'userId' => $user->getId(),
                    'balance' => $userBalanceAfterTransaction
                ],
                false
            );

            // Logging the action
            $operationRepository = new OperationRepository();
            $operationRepository->addOperation($user, null, OperationRepository::OPERATION_TYPE_FUNDS_WITHDRAW, $amount);

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
