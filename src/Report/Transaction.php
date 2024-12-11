<?php

namespace App\Report;

use App\Utils\TransactionType;

class Transaction extends Price
{
    public function __construct(
        float $price, 
        \DateTime $date, 
        private readonly TransactionType $transactionType
    )
    {
        parent::__construct($price, $date);
    }

    public function getTransactionType()
    {
        return $this->transactionType;
    }
}