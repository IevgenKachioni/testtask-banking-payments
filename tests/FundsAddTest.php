<?php

use \PHPUnit\Framework\TestCase;
use \App\Command;

class FundsAddTest extends TestCase
{
    public function testFundsAddSuccess()
    {
        $parameters = ['add_funds', '1', '2000'];

        $command = new Command($parameters);
        $response = json_decode($command->run());

        $this->assertTrue($response->success);
    }

    public function testFundsAddFailsForMissingUser()
    {
        $parameters = ['add_funds', 'missingUser', '2000'];

        $command = new Command($parameters);
        $response = json_decode($command->run());

        $this->assertFalse($response->success);
    }

    public function testFundsAddFailsForDisabledUser()
    {
        $parameters = ['add_funds', '3', '2000'];

        $command = new Command($parameters);
        $response = json_decode($command->run());

        $this->assertFalse($response->success);
    }

    public function testFundsAddFailsIfAmountExceeded()
    {
        $parameters = ['add_funds', '1', '500001'];

        $command = new Command($parameters);
        $response = json_decode($command->run());

        $this->assertFalse($response->success);
    }
}