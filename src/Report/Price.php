<?php

namespace App\Report;

class Price
{
    public function __construct(
        private readonly float          $price,
        private readonly \DateTime      $date
    )
    {
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getDate()
    {
        return $this->date;
    }
}