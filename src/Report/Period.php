<?php

namespace App\Report;

use App\Utils\PriceTrend;
use App\Utils\StockHold;
use App\Utils\TransactionType;

class Period
{
    private \DateTime $startDate;
    private \DateTime $endDate;
    private Price $bestToBuy;
    private Price $bestToSell;
    private int $daysCount;
    private float $profit;
    private int $trendChangeCount;
    private PeriodProfit $periodProfit; 

    public function __construct(
        private readonly array $stockPrices
    )
    {
        $this->periodProfit = new PeriodProfit();
        $this->analyze();
    }

    private function analyze()
    {
        $minPrice = null;
        $maxPrice = null;
        $startDate = null;
        $endDate = null;
        $trend = null;
        $currentTrend = null;
        $trendChangeCount = 0;
        $stockHold = StockHold::No;

        foreach ($this->stockPrices as $index => $stockPrice) {
            
            $currentTrend = $trend;
            if ($index+1 < count($this->stockPrices)) {
                if ($stockPrice['close'] > $this->stockPrices[$index+1]['close'])
                {
                    $trend = PriceTrend::Down;
                } 
                else if ($stockPrice['close'] < $this->stockPrices[$index+1]['close'])
                {
                    $trend = PriceTrend::Up;
                } 
                else {
                    $trend = PriceTrend::Stable;
                }
            }
            if ($currentTrend != $trend)
            {
                $trendChangeCount++;
            } 
            
            if ($trend == PriceTrend::Up && $stockHold == StockHold::No)
            {
                $this->periodProfit->addTransaction(
                    new Transaction(
                        $stockPrice['close'], 
                        $stockPrice['date'], 
                        TransactionType::Buy
                    )
                );
                $stockHold = StockHold::Yes;
            }
            if (
                    $stockHold == StockHold::Yes
                    && ($index+1 == count($this->stockPrices) || $trend == PriceTrend::Down)
                )
            {
                $this->periodProfit->addTransaction(
                    new Transaction(
                        $stockPrice['close'], 
                        $stockPrice['date'], 
                        TransactionType::Sell
                    )
                );
                $stockHold = StockHold::No;
            }

            //prices
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

            // dates
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
        $this->trendChangeCount = $trendChangeCount;
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

    public function getTrendChangeCount()
    {
        return $this->trendChangeCount;
    }

    public function getPeriodProfit()
    {
        return $this->periodProfit;
    }
}