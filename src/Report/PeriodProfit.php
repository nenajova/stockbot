<?php

namespace App\Report;

use App\Utils\TransactionType;

class PeriodProfit
{
    private float $profit = 0;
    private $transactions;

    public function __construct()
    {
        $this->transactions = [];
    }

    public function addTransaction(Transaction $transaction)
    {
        $this->transactions[] = $transaction;
        if ($transaction->getTransactionType() == TransactionType::Buy) {
            $this->profit -= $transaction->getPrice();
        } else {
            $this->profit += $transaction->getPrice();
        }
    }

    public function getProfit()
    {
        return $this->profit;
    }

    public function getTransactionCounter()
    {
        return count($this->transactions);
    }

    public function getTransactionToString()
    {
        $transactions = "";
        foreach($this->transactions as $transaction){
            if ($transaction->getTransactionType() == TransactionType::Buy) {
                $transactions .= " #Buy: " . $transaction->getDate()->format('m.d.Y') 
                . ", for " . $transaction->getPrice();
            } else {
                $transactions .= " #Sell: " . $transaction->getDate()->format('m.d.Y') 
                . ", for " . $transaction->getPrice();
            }
        }
        return $transactions;
    }
}