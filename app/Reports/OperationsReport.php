<?php

namespace App\Reports;

use App\Model\Operation;
use App\Model\User;
use App\Repository\OperationRepository;

class OperationsReport implements ReportInterface
{
    /**
     * @param User $user
     * @return array
     */
    public function serializeUserForReport(User $user)
    {
        return [
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'isActive' => $user->getIsActive() ? 'yes' : 'no',
            'accountBalance' => $user->getAccountBalance()
        ];
    }

    /**
     * @param $type integer
     * @return string
     */
    public function getOperationType($type)
    {
        switch ((int)$type) {
            case OperationRepository::OPERATION_TYPE_FUNDS_TRANSFER:
                return 'Transfer funds';
            case OperationRepository::OPERATION_TYPE_FUNDS_WITHDRAW:
                return 'Withdraw funds';
            case OperationRepository::OPERATION_TYPE_FUNDS_ADD:
                return 'Adding funds';
        }
    }

    /**
     * @param Operation $operation
     * @return array
     * @throws \Exception
     */
    public function serializeOperation($operation)
    {
        $reportElement = [];

        if (!$operation->getFromUser() || !$operation->getFromUser()->getId()) {
            throw new \Exception('Operation object must have a main user');
        }

        $reportElement['fromUser'] = $this->serializeUserForReport($operation->getFromUser());

        // This user may not be present.
        $toUser = $operation->getToUser();
        if ($toUser->getId()) {
            $reportElement['toUser'] = $this->serializeUserForReport($operation->getToUser());
        }

        $reportElement['operationType'] = $this->getOperationType($operation->getType());
        $reportElement['processedAmount'] = $operation->getAmount();
        $reportElement['createdAt'] = $operation->getCreatedAt();

        return $reportElement;
    }

    /**
     * @param Operation[] $collection
     * @return array
     * @throws \Exception
     */
    public function generate($collection)
    {
        $report = [];
        foreach ($collection as $operation) {
            $report[] = $this->serializeOperation($operation);
        }

        return $report;
    }
}
