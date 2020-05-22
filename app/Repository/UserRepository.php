<?php

namespace App\Repository;

use App\Model\User;
use Services\DB;

/**
 * Managing Users on DB level.
 * This helps separating business entities (Model) from storage logic.
 */
class UserRepository
{
    /**
     * @param string|int $id
     * @return User|false
     * @throws \Exception
     */
    public function getUserById($id)
    {
        $rawData = DB::query(
            'SELECT 
                `user`.`id`,
                `user`.`first_name`, 
                `user`.`last_name`, 
                `user`.`email`, 
                `account`.`is_active`,
                `account`.`account_balance`
            FROM `user`
            INNER JOIN `account` ON `account`.`user_id` = `user`.`id`
            WHERE `user`.`id` = :id',
            ['id' => $id]
        );

        if (empty($rawData[0])) {
            return false;
        }

        $rawData = $rawData[0];

        $user = new User();
        $user->setId((int)$rawData['id']);
        $user->setFirstName($rawData['first_name']);
        $user->setLastName($rawData['last_name']);
        $user->setEmail($rawData['email']);
        $user->setIsActive($rawData['is_active'] ? true : false);
        $user->setAccountBalance((int)$rawData['account_balance']);

        return $user;
    }
}
