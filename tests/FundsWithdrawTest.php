<?php

use \PHPUnit\Framework\TestCase;
use \App\Command;

class FundsWithdrawTest extends TestCase
{
    public function testFundsWithdrawSuccess()
    {
        $parameters = ['withdraw', '1', '100'];

        $command = new Command($parameters);
        $response = json_decode($command->run());

        $this->assertTrue($response->success);
    }

    public function testFundsWithdrawFailsForMissingUser()
    {
        $parameters = ['withdraw', 'missingUser', '100'];

        $command = new Command($parameters);
        $response = json_decode($command->run());

        $this->assertFalse($response->success);
    }

    public function testFundsWithdrawFailsForDisabledUser()
    {
        $parameters = ['withdraw', '3', '100'];

        $command = new Command($parameters);
        $response = json_decode($command->run());

        $this->assertFalse($response->success);
    }

    public function testFundsWithdrawFailsIfInsufficientFunds()
    {
        $parameters = ['withdraw', '3', '600000'];

        $command = new Command($parameters);
        $response = json_decode($command->run());

        $this->assertFalse($response->success);
    }
}