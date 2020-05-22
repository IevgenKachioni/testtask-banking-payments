<?php

namespace App\Commands;

use App\Managers\FundsTransferManager;
use App\Model\User;
use App\Repository\UserRepository;
use App\Validators\AvailableFundsForTransferValidator;
use App\Validators\UserAccountValidator;

/**
 * Performs funds transaction from one account to another.
 *
 * CLI call example: php ./index.php transfer 1 2 200
 * There, the parameters are: the sender, the receiver, the amount.
 */
class FundsTransfer extends BaseCommand
{
    /**
     * @var integer
     */
    protected $senderId;

    /**
     * @var integer
     */
    protected $receiverId;

    /**
     * @var integer
     */
    protected $amount;

    /**
     * @param array $params
     * @return self
     * @throws \Exception
     */
    public function setAttributes($params)
    {
        if (empty($params[1]) || !is_numeric($params[1])) {
            throw new \Exception('Missing or invalid sender user id CLI param.');
        }
        $this->senderId = (int)$params[1];

        if (empty($params[2]) || !is_numeric($params[2])) {
            throw new \Exception('Missing or invalid receiver user id CLI param.');
        }
        $this->receiverId = (int)$params[2];

        if (empty($params[3]) || !is_numeric($params[3])) {
            throw new \Exception('Missing or invalid amount CLI param.');
        }
        $this->amount = (int)$params[3];

        return $this;
    }

    /**
     * @return string
     */
    public static function getName()
    {
        return 'transfer';
    }

    /**
     * @param User $sender
     * @param User $receiver
     * @throws \Exception
     */
    protected function validateAccounts($sender, $receiver)
    {
        $validator = new UserAccountValidator();

        foreach ([$sender, $receiver] as $user) {
            if (!$validator->validate($user)) {
                $extraInfo = $user ?  ' with email ' . $user->getEmail() : '';
                throw new \Exception('User' . $extraInfo . ' is not allowed to perform this operation.');
            }
        }
    }

    /**
     * @param User $sender
     * @param integer $amount
     * @throws \Exception
     */
    protected function validateAvailableFundsToTransfer(User $sender, $amount)
    {
        $validator = new AvailableFundsForTransferValidator();
        if (!$validator->validate($sender, $amount)) {
            throw new \Exception('Invalid funds amount requested for a transaction.');
        }
    }

    /**
     * @param User $sender
     * @param User $receiver
     * @param $amount
     * @throws \Exception
     */
    public function validateRequest($sender, $receiver, $amount)
    {
        $this->validateAccounts($sender, $receiver);
        $this->validateAvailableFundsToTransfer($sender, $amount);
    }

    /**
     * @param User $sender
     * @param User $receiver
     * @param $amount
     * @throws \Exception
     */
    protected function transferFunds(User $sender, User $receiver, $amount)
    {
        $transferManager = new FundsTransferManager();
        $transferResult = $transferManager->transferFunds($sender, $receiver, $amount);
        if (!$transferResult['success']) {
            throw new \Exception('Funds transfer failed: ' . $transferResult['message']);
        }
    }

    /**
     * @return string
     */
    protected function getSuccessResponse()
    {
        return 'The transaction was successful!';
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function run()
    {
        $userRepository = new UserRepository();
        $sender = $userRepository->getUserById($this->senderId);
        $receiver = $userRepository->getUserById($this->receiverId);
        $amount = $this->amount;

        $this->validateRequest($sender, $receiver, $amount);
        $this->transferFunds($sender, $receiver, $amount);

        return $this->getSuccessResponse();
    }
}
