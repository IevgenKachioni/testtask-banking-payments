<?php

namespace App;

use App\Commands\FundsAdd;
use App\Commands\FundsTransfer;
use App\Commands\FundsWithdraw;
use App\Commands\OperationsOverview;

/**
 * Base CLI Command class.
 */
class Command
{
    /**
     * @var array
     */
    protected $arguments;

    /**
     * @var array
     */
    protected $commands = [];

    /**
     * @param array $arguments
     * @throws \Exception
     */
    public function __construct($arguments)
    {
        $this->arguments = $arguments;
        $this->registerCommands();
    }

    /**
     * Add new commands here.
     *
     * @return array
     */
    protected function getCommandsList()
    {
        return [
            FundsTransfer::class,
            OperationsOverview::class,
            FundsWithdraw::class,
            FundsAdd::class,
        ];
    }

    /**
     * Register commands in system.
     *
     * @throws \Exception
     */
    protected function registerCommands()
    {
        $registeredCommands = [];
        foreach ($this->getCommandsList() as $command) {
            if (isset($registeredCommands[$command::getName()])) {
                throw new \Exception('The command with the same name is already registered');
            }
            $registeredCommands[$command::getName()] = $command;
        }

        $this->commands = $registeredCommands;
    }

    /**
     * Get a command class instance based on provided CLI parameters.
     *
     * @param string $commandName
     * @return string
     * @throws \Exception
     */
    protected function getCommandClassName($commandName)
    {
        if (empty($this->commands[$commandName])) {
            throw new \Exception('Cannot find the command by name');
        }

        return $this->commands[$commandName];
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function getCalledCommandName()
    {
        $cliArguments = $this->arguments;
        if (empty($cliArguments[0])) {
            throw new \Exception('Missing command name');
        }

        return $cliArguments[0];
    }

    /**
     * @return string
     */
    public function run()
    {
        $success = false;

        try {
            $commandName = $this->getCalledCommandName();
            $commandClassName = $this->getCommandClassName($commandName);

            $command = new $commandClassName($this->arguments);
            $result = $command->run();

            $success = true;

        } catch (\Throwable $e) {
            $result = 'An error occurred: ' . $e->getMessage();
        }

        return json_encode([
            'success' => $success,
            'info' => $result
        ]);
    }
}
