<?php

namespace ApiBundle\ViewModels\OffersViewModel;

use ApiBundle\ViewModels\OffersViewModel\OffersViewModelItem;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\AccessType;

/**
 * Class OffersViewModel
 * @package ApiBundle\ViewModels\OffersViewModel
 * @AccessType("reflection")
 */
class OffersViewModel
{   
    /**
     * Total offers count matched by given criteria
     * @Type(name="integer")
     */     
    public $itemsTotal;

    /**
     * Offers returned in current page
     * @Type(name="array<ApiBundle\ViewModels\OffersViewModel\OffersViewModelItem>")
     */
    public $items;   
    
    /**
     * @return array|OffersViewModelItem[]
     */
    public function getItems()
    {
        return $this->items;
    }


    public function addItem(OffersViewModelItem $item)
    {
        $this->items[] = $item;
    }

    /**
     * @return mixed
     */
    public function getItemsTotal()
    {
        return $this->itemsTotal;
    }

    /**
     * @param mixed $itemsTotal
     */
    public function setItemsTotal($itemsTotal)
    {
        $this->itemsTotal = $itemsTotal;
    }


}