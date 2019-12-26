<?php
namespace ApiBundle\ViewModels;

use JMS\Serializer\Annotation\Type;

use JMS\Serializer\Annotation\AccessType;

/**
 * Class OffersViewModelItem
 * @package ApiBundle\ViewModels
 */
class DictionaryViewModelItem
{

    /**
     * Attribute id to pass to /api/offers
     * @Type(name="integer")
     */
    public $id;

    /**
     * Attribute name to show on filters to users
     * @Type(name="string")
     */
    public $name;

    /**
     * Count of offers matching given attribute
     * @Type(name="integer")
     */
    public $count;
    
    function __construct($id, $name, $count)
    {
        $this->id = $id;
        $this->name = $name;
        $this->count = $count;
    }

    
    function getId()
    {
        return $this->id;
    }

    function getName()
    {
        return $this->name;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function setCount($count)
    {
        $this->count = $count;
    }
}