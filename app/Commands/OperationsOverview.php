<?php

namespace App\Commands;

use App\Model\User;
use App\Reports\OperationsReport;
use App\Repository\OperationRepository;
use App\Repository\UserRepository;
use App\Validators\UserAccountValidator;

/**
 * Shows the balance amount of specified user.
 *
 * CLI call example: php ./index.php operations 1
 * There, the parameters are: the user.
 */
class OperationsOverview extends BaseCommand
{
    /**
     * @var string
     */
    public $report = '';

    /**
     * @var integer
     */
    protected $userId;

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
        $this->userId = (int)$params[1];

        return $this;
    }

    /**
     * @return string
     */
    public static function getName()
    {
        return 'operations';
    }

    /**
     * @param User $user
     * @throws \Exception
     */
    protected function validateAccount(User $user)
    {
        $validator = new UserAccountValidator();
        if (!$validator->validate($user)) {
            throw new \Exception('User with email = ' . $user->getEmail() . ' is not allowed to perform this operation.');
        }
    }

    /**
     * @param User $user
     * @throws \Exception
     */
    public function validateRequest($user)
    {
        $this->validateAccount($user);
    }

    /**
     * @param User $user
     * @return void
     * @throws \Exception
     */
    protected function generateOperationsReport(User $user)
    {
        $operationRepository = new OperationRepository();
        $collection = $operationRepository->getOperationsByUser($user);

        $reportGenerator = new OperationsReport();

        $this->report = $reportGenerator->generate($collection);
    }

    /**
     * @return string
     */
    protected function getSuccessResponse()
    {
        // Just to be able to easily represent array a string
        return json_encode($this->report);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function run()
    {
        $userRepository = new UserRepository();
        $user = $userRepository->getUserById($this->userId);

        $this->validateRequest($user);
        $this->generateOperationsReport($user);

        return $this->getSuccessResponse();
    }
}
