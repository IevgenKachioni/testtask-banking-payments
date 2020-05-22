<?php

namespace App\Repository;

use App\Model\Operation;
use App\Model\User;
use Services\DB;

class OperationRepository
{
    const OPERATION_TYPE_FUNDS_TRANSFER = 1;
    const OPERATION_TYPE_FUNDS_WITHDRAW = 2;
    const OPERATION_TYPE_FUNDS_ADD = 3;

    /**
     * @param integer $type
     * @return bool
     */
    public function isOperationInvolvingMultipleUsers($type)
    {
        return in_array($type, [
            static::OPERATION_TYPE_FUNDS_TRANSFER,
        ]);
    }

    /**
     * @param User $sender
     * @param User|null $receiver
     * @param integer $operationType
     * @param integer $amount
     * @throws \Exception
     */
    public function addOperation(User $sender, $receiver, $operationType, $amount)
    {
        $senderId = $sender->getId();
        $receiverId = $receiver ? $receiver->getId() : null;

        DB::query(
            'INSERT INTO `operation` (`from_user`, `to_user`, `type`, `amount`)
                   VALUES (:fromUser, :toUser, :operationType, :fundsAmount);',
            [
                'fromUser' => $senderId,
                'toUser' => $receiverId,
                'operationType' => $operationType,
                'fundsAmount' => $amount,
            ],
            false
        );
    }

    /**
     * @param User $user
     * @return array
     * @throws \Exception
     */
    public function getOperationsByUser(User $user)
    {
        $rawData = DB::query(
            'SELECT * FROM `operation` WHERE from_user = :userId OR to_user = :userId ORDER BY created_at ASC;',
            [
                'userId' => $user->getId()
            ]
        );

        if (empty($rawData)) {
            return [];
        }

        $collection = [];
        foreach ($rawData as $operationData) {

            $operation = new Operation();
            $operation->setId($operationData['id']);

            $userRepository = new UserRepository();

            $fromUser = $userRepository->getUserById($operationData['from_user']);

            $toUser = new User();
            if ($this->isOperationInvolvingMultipleUsers($operationData['type'])) {
                $toUser = $userRepository->getUserById($operationData['to_user']);
            }

            $operation->setFromUser($fromUser);
            $operation->setToUser($toUser);

            $operation->setAmount((int)$operationData['amount']);
            $operation->setType((int)$operationData['type']);
            $operation->setCreatedAt($operationData['created_at']);

            $collection[] = $operation;
        }

        return $collection;
    }
}
