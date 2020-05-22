<?php

namespace App\Commands;

interface CommandInterface
{
    /**
     * @return string
     */
    public static function getName();

    /**
     * @return string
     */
    public function run();
}
