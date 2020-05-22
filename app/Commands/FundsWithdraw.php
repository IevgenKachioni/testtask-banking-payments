<?php

namespace App\Commands;

use App\Managers\FundsWithdrawManager;
use App\Model\User;
use App\Repository\UserRepository;
use App\Validators\AvailableFundsForWithdrawValidator;
use App\Validators\UserAccountValidator;

/**
 * Performs funds withdraw.
 *
 * CLI call example: php ./index.php withdraw 1 200
 * There, the parameters are: the user id, the amount.
 */
class FundsWithdraw extends BaseCommand
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
        return 'withdraw';
    }

    /**
     * @param User $user
     * @throws \Exception
     */
    protected function validateAccount($user)
    {
        $validator = new UserAccountValidator();
        if (!$validator->validate($user)) {
            $extraInfo = $user ?  ' with email ' . $user->getEmail() : '';
            throw new \Exception('User' . $extraInfo . ' is not allowed to perform this operation.');
        }
    }

    /**
     * @param User $user
     * @param integer $amount
     * @throws \Exception
     */
    protected function validateAvailableFundsToWithdraw(User $user, $amount)
    {
        $validator = new AvailableFundsForWithdrawValidator();
        if (!$validator->validate($user, $amount)) {
            throw new \Exception('Insufficient funds amount on account.');
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
        $this->validateAvailableFundsToWithdraw($user, $amount);
    }

    /**
     * @param User $user
     * @param $amount
     * @throws \Exception
     */
    protected function withdrawFunds(User $user, $amount)
    {
        $fundsWithdrawManager = new FundsWithdrawManager();

        $result = $fundsWithdrawManager->withdrawFunds($user, $amount);
        if (!$result['success']) {
            throw new \Exception('Funds withdraw failed: ' . $result['message']);
        }
    }

    /**
     * @return string
     */
    protected function getSuccessResponse()
    {
        return 'The withdraw operation was successful!';
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
        $this->withdrawFunds($user, $amount);

        return $this->getSuccessResponse();
    }
}
