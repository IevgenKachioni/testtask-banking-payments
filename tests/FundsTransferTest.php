<?php

use \PHPUnit\Framework\TestCase;
use \App\Command;

class FundsTransferTest extends TestCase
{
    public function testFundsTransferSuccess()
    {
        $parameters = ['transfer', '1', '2', '2000'];

        $command = new Command($parameters);
        $response = json_decode($command->run());

        $this->assertTrue($response->success);
    }

    public function testFundsTransferFailsForMissingUser()
    {
        $parameters = ['transfer', 'missingUser', '2', '2000'];

        $command = new Command($parameters);
        $response = json_decode($command->run());

        $this->assertFalse($response->success);
    }

    public function testFundsTransferFailsForInactiveUser()
    {
        $parameters = ['transfer', '3', '2', '2000'];

        $command = new Command($parameters);
        $response = json_decode($command->run());

        $this->assertFalse($response->success);
    }
}