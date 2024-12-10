<?php

namespace App\Factory;

use App\Report\Report;

class ReportFactory 
{
    public static function create(array $stocks): Report
    {
        return new Report($stocks);
    }
}