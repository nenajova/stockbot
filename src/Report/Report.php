<?php

namespace App\Report;

class Report
{
    private Period $mainPeriod;
    private Period $previousPeriod;
    private Period $nextPeriod;
    private \DateTime $startDate;
    private \DateTime $endDate;
    private int $daysCount;

    public function __construct(
        private readonly array $stocks,
    )
    {
        $this->mainPeriod = new Period($this->stocks['main_period']);
        $this->previousPeriod = new Period($this->stocks['previous_period']);
        $this->nextPeriod = new Period($this->stocks['next_period']);
    }

    public function getMainPeriod(): Period
    {
        return $this->mainPeriod;
    }

    public function getPreviousPeriod(): Period
    {
        return $this->previousPeriod;
    }

    public function getNextPeriod(): Period
    {
        return $this->nextPeriod;
    }
}