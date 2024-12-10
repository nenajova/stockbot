<?php

namespace App\Report;

class Period
{
    private \DateTime $startDate;
    private \DateTime $endDate;
    private Price $bestToBuy;
    private Price $bestToSell;
    private int $daysCount;
    private float $profit;

    public function __construct(
        private readonly array $stockPrices
    )
    {
        $this->analyze();
    }

    private function analyze()
    {
        $minPrice = null;
        $maxPrice = null;
        $startDate = null;
        $endDate = null;

        foreach ($this->stockPrices as $stockPrice) {
            if ($minPrice === null || $stockPrice['close'] < $minPrice['close']) {
                $minPrice = $stockPrice;
            }
            if (
                $maxPrice === null 
                || $minPrice['date'] < $stockPrice['date'] // max price must be later then min in time
                || $stockPrice['close'] > $maxPrice['close']
                ) {
                $maxPrice = $stockPrice;
            }
            if ($startDate === null || $stockPrice['date'] < $startDate['date']) {
                $startDate = $stockPrice;
            }
            if ($endDate === null || $stockPrice['date'] > $endDate['date']) {
                $endDate = $stockPrice;
            }
        }

        $this->bestToBuy = new Price($minPrice['close'], $minPrice['date']);
        $this->bestToSell = new Price($maxPrice['close'], $maxPrice['date']);
        $this->startDate = $startDate['date'];
        $this->endDate = $endDate['date'];
        $this->daysCount = count($this->stockPrices);
        $this->profit = $this->bestToSell->getPrice() - $this->bestToBuy->getPrice();
    }

    public function getBestToBuy()
    {
        return $this->bestToBuy;
    }

    public function getBestToSell()
    {
        return $this->bestToSell;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function getDaysCount()
    {
        return $this->daysCount;
    }

    public function getProfit()
    {
        return $this->profit;
    }
}