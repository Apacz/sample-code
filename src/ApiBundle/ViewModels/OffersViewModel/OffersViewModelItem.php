<?php
namespace ApiBundle\ViewModels\OffersViewModel;

use JMS\Serializer\Annotation\Type;

use JMS\Serializer\Annotation\AccessType;

/**
 * Class OffersViewModelItem
 * @package ApiBundle\ViewModels\OffersViewModel
 */
class OffersViewModelItem
{

    /**
     * Offer id (use it to get offer details via /api/offers/{id})
     * @Type(name="integer")
     */
    public $id;


    /**
     * Job title
     * @Type(name="string")
     */
    public $title;


    /**
     * Offer publication date. Format: YYYY-MM-DD
     * @Type(name="string")
     */
    public $published;


    /**
     * Job description
     * @Type(name="string")
     */
    public $jobDescription;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    function getPublished()
    {
        return $this->published;
    }

    function setPublished($published)
    {
        $this->published = $published;
    }
        
    /**
     * @return string
     */
    public function getJobDescription()
    {
        return $this->jobDescription;
    }

    /**
     * @param mixed $jobDescription
     */
    public function setJobDescription(string $jobDescription)
    {
        $this->jobDescription = $jobDescription;
    }


}