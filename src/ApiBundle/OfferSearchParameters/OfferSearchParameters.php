<?php
/**
 * Created by PhpStorm.
 * User: patry
 * Date: 2017-03-27
 * Time: 10:53
 */

namespace ApiBundle\OfferSearchParameters;


/**
 * Class OfferSearchParameters
 * @package ApiBundle\SearchParameters
 */
class OfferSearchParameters
{
    private $page = 1;
    private $pageSize = 25;
    private $publishedFrom;
    private $categoryIds;
    private $branchIds;
    private $regionIds;
    private $countryIds;
    private $salaryIds;
    private $languageIds;
    private $educationLevelIds;
    private $city;
    private $direction;
    private $sort;
    private $jobTitle;
    private $keyword;
    private $language;
    private $source;
    private $positionIds;

    public function __construct($request)
    {

        foreach ($request as $key => $value) {
            $setter = 'set'.ucfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            } else {
                //$setter = substr($setter,0, -3);
                $setter .= 'Ids';
                if (method_exists($this, $setter)) {
                    $this->$setter($value);
                }
            }
        }
    }

    private function parseAttributeValue($value)
    {
        $return = explode(',', $value);
        if (empty($return)) {
            return array($value);
        } else {
            //return array_map('intval', $return);
            return  $return;
        }
    }

    public  function getObjectVars()
    {
        return get_object_vars($this);
    }

    /**
     * @return mixed
     */
    public function getPublishedFrom()
    {
        return $this->publishedFrom;
    }

    /**
     * @param null $publishedFrom
     * @throws \Exception
     */
    public function setPublishedFrom($publishedFrom = null)
    {
        $this->publishedFrom = $publishedFrom;
    }

    /**
     * @return mixed
     */
    public function getCategoryIds()
    {
        return $this->categoryIds;
    }

    /**
     * @param mixed $categoryIds
     */
    public function setCategoryIds($categoryIds)
    {
        $this->categoryIds = $this->parseAttributeValue($categoryIds);
    }

    /**
     * @return mixed
     */
    public function getBranchIds()
    {
        return $this->branchIds;
    }


    public function setBranchIds($branchIds)
    {
        $this->branchIds = $this->parseAttributeValue($branchIds);
    }

    /**
     * @return mixed
     */
    public function getRegionIds()
    {
        return $this->regionIds;
    }

    /**
     * @param mixed $regionIds
     */
    public function setRegionIds($regionIds)
    {
        $this->regionIds = $this->parseAttributeValue($regionIds);
    }

    /**
     * @return mixed
     */
    public function getCountryIds()
    {
        return $this->countryIds;
    }

    /**
     * @param mixed $countryIds
     */
    public function setCountryIds($countryIds)
    {
        $this->countryIds = $this->parseAttributeValue($countryIds);
    }

    /**
     * @return mixed
     */
    public function getSalaryIds()
    {
        return $this->salaryIds;
    }

    /**
     * @param mixed $salaryIds
     */
    public function setSalaryIds($salaryIds)
    {
        $this->salaryIds = $this->parseAttributeValue($salaryIds);
    }

    /**
     * @return mixed
     */
    public function getLanguageIds()
    {
        return $this->languageIds;
    }

    /**
     * @param mixed $languageIds
     */
    public function setLanguageIds($languageIds)
    {
        $this->languageIds = $this->parseAttributeValue($languageIds);
    }

    function getEducationLevelIds()
    {
        return $this->educationLevelIds;
    }

    function setEducationLevelIds($educationLevelIds)
    {
        $this->educationLevelIds = $this->parseAttributeValue($educationLevelIds);
    }
    
    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    function getDirection()
    {
        return $this->direction;
    }

    function setDirection($direction)
    {
        $this->direction = $direction;
    }


    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return mixed
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * @param mixed $jobTitle
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param int $pageSize
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }

    /**
     * @return mixed
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * @param mixed $keyword
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }
    
    function getPositionIds()
    {
        return $this->positionIds;
    }

    function setPositionIds($positionIds)
    {
        $this->positionIds = $positionIds;
    }
}