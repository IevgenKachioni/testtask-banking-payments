<?php

namespace App\Commands;

use App\Managers\FundsAddingManager;
use App\Model\User;
use App\Repository\UserRepository;
use App\Validators\AvailableFundsForWithdrawValidator;
use App\Validators\FundsAmountForAddingValidator;
use App\Validators\UserAccountValidator;

/**
 * Performs adding funds to the customer account.
 *
 * CLI call example:  php ./index.php add_funds 1 200
 * There, the parameters are: the user id, the amount.
 */
class FundsAdd extends BaseCommand
{
    /**
     * @var integer
     */
    protected $userId;

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
            throw new \Exception('Missing or invalid user id CLI param.');
        }
        $this->userId = (int)$params[1];

        if (empty($params[2]) || !is_numeric($params[2])) {
            throw new \Exception('Missing or invalid amount CLI param.');
        }
        $this->amount = (int)$params[2];

        return $this;
    }

    /**
     * @return string
     */
    public static function getName()
    {
        return 'add_funds';
    }

    /**
     * @param User $user
     * @throws \Exception
     */
    protected function validateAccount($user)
    {
        $validator = new UserAccountValidator();
        if (!$validator->validate($user)) {
            $extraInfo = $user ? ' with email ' . $user->getEmail() : '';
            throw new \Exception('User' . $extraInfo . ' is not allowed to perform this operation.');
        }
    }

    /**
     * @param integer $amount
     * @throws \Exception
     */
    protected function validateAmountToAdd($amount)
    {
        $validator = new FundsAmountForAddingValidator();
        if (!$validator->validate($amount)) {
            throw new \Exception('Funds you are trying to put to account must not exceed the specified limit.');
        }
    }

    /**
     * @param User $user
     * @param $amount
     * @throws \Exception
     */
    public function validateRequest(User $user, $amount)
    {
        $this->validateAccount($user);
        $this->validateAmountToAdd($amount);
    }

    /**
     * @param User $user
     * @param $amount
     * @throws \Exception
     */
    protected function addFundsToAccount(User $user, $amount)
    {
        $fundsAddingManager = new FundsAddingManager();

        $result = $fundsAddingManager->addFundsToAccount($user, $amount);
        if (!$result['success']) {
            throw new \Exception('Funds adding failed: ' . $result['message']);
        }
    }

    /**
     * @return string
     */
    protected function getSuccessResponse()
    {
        return 'The funds adding was successful!';
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function run()
    {
        $userRepository = new UserRepository();
        $user = $userRepository->getUserById($this->userId);
        $amount = $this->amount;

        $this->validateRequest($user, $amount);
        $this->addFundsToAccount($user, $amount);

        return $this->getSuccessResponse();
    }
}
