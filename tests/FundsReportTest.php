<?php

use \PHPUnit\Framework\TestCase;
use \App\Command;

class FundsReportTest extends TestCase
{
    public function testSeeReportSuccess()
    {
        $parameters = ['operations', '2', '2000'];

        $command = new Command($parameters);
        $response = json_decode($command->run());

        $this->assertTrue($response->success);
    }

    public function testReportNotShownForMissingUser()
    {
        $parameters = ['operations', 'missingUser', '2000'];

        $command = new Command($parameters);
        $response = json_decode($command->run());

        $this->assertFalse($response->success);
    }

    public function testReportNotShownForDisabledUser()
    {
        $parameters = ['operations', '3', '2000'];

        $command = new Command($parameters);
        $response = json_decode($command->run());

        $this->assertFalse($response->success);
    }
}