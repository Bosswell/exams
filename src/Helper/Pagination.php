<?php

namespace App\Helper;

class Pagination 
{
    private $currentPage;
    private $totalItemsCount;
    private $pages;
    private $paginationLimit;
    private $itemsLimit;

    public function __construct(int $currentPage, int $totalItemsCount, int $paginationLimit, int $itemsLimit)
    {
        $this->totalItemsCount = $totalItemsCount;
        $this->paginationLimit = $paginationLimit;
        $this->itemsLimit = $itemsLimit;

        $this->countPages();
        $this->validCurrentPage($currentPage);
    }

    public function getRange() : Array
    {
        if (($this->currentPage + $this->paginationLimit) > $this->pages) 
            $rangeLimit = $this->pages;
        else
            $rangeLimit = $this->currentPage + $this->paginationLimit;

        if ($this->currentPage == 1) 
            $range = range($this->currentPage, $rangeLimit);
        else 
            $range = range($this->currentPage - 1, $rangeLimit);

        return $range;
    }

    public function getLimitOffset() : int
    {
        return $this->currentPage * $this->itemsLimit - $this->itemsLimit;
    }

    public function getCurrentPage() : int
    {
        return $this->currentPage;
    }
    
    public function getPagesQuantity() : int
    {
        return $this->pages;
    }

    private function countPages() : void
    {
        $this->pages = ceil($this->totalItemsCount / $this->itemsLimit);
    }

    private function validCurrentPage(int $currentPage) : void 
    {
        if ($currentPage > 0 && $currentPage <= $this->pages) {
            $this->currentPage = $currentPage;

        } else {
            $this->currentPage = 1;
        }
    }
}