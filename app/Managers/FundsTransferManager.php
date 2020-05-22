<?php

namespace App\Managers;

use App\Model\User;
use App\Repository\OperationRepository;
use Services\DB;

/**
 * Transferring funds from one user bank account to another.
 */
class FundsTransferManager
{
    /**
     * @param User $sender
     * @param User $receiver
     * @param int $amount Amount in cents.
     * @return array
     */
    public function transferFunds(User $sender, User $receiver, $amount)
    {
        $success = false;
        $message = '';

        try {
            $senderBalanceAfterTransaction = $sender->getAccountBalance() - $amount;
            $receiverBalanceAfterTransaction = $receiver->getAccountBalance() + $amount;

            $fundsPlacingQuery = 'UPDATE `account` 
            SET account_balance = :balance WHERE (user_id = :userId)';

            // Removing funds from sender account
            DB::query(
                $fundsPlacingQuery,
                [
                    'userId' => $sender->getId(),
                    'balance' => $senderBalanceAfterTransaction
                ],
                false
            );

            // Placing funds onto receiver account
            DB::query(
                $fundsPlacingQuery,
                [
                    'userId' => $receiver->getId(),
                    'balance' => $receiverBalanceAfterTransaction
                ],
                false
            );

            // Logging the action
            $operationRepository = new OperationRepository();
            $operationRepository->addOperation($sender, $receiver, OperationRepository::OPERATION_TYPE_FUNDS_TRANSFER, $amount);

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
