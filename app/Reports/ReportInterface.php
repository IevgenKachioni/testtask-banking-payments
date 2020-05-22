<?php

namespace App\Reports;

interface ReportInterface
{
    /**
     * @param mixed $collection
     * @return array
     */
    public function generate($collection);
}
