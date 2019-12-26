<?php
namespace ApiBundle\ViewModels;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\AccessType;

/**
 * Class Dictionary
 * @package ApiBundle\ViewModels
 * @AccessType("reflection")
 */
class DictionaryViewModel
{

    /**
     * @Type(name="array<ApiBundle\ViewModels\DictionaryViewModelItem>")
     */
    public $regions = [];

    /**
     * @Type(name="array<ApiBundle\ViewModels\DictionaryViewModelItem>")
     */
    public $branches = [];

    /**
     * @Type(name="array<ApiBundle\ViewModels\DictionaryViewModelItem>")
     */
    public $categories = [];

    /**
     * @Type(name="array<ApiBundle\ViewModels\DictionaryViewModelItem>")
     */
    public $educationLevels = [];

    /**
     * @Type(name="array<ApiBundle\ViewModels\DictionaryViewModelItem>")
     */
    public $countries = [];

    /**
     * @Type(name="array<ApiBundle\ViewModels\DictionaryViewModelItem>")
     */
    public $languages = [];

    /**
     * @Type(name="array<ApiBundle\ViewModels\DictionaryViewModelItem>")
     */
    public $salaries = [];   
    
    /**
     * @Type(name="array<ApiBundle\ViewModels\DictionaryViewModelItem>")
     */
    public $positions = [];

    /**
     * @param $region
     * @param int|null $id
     */
    public function addRegion($region, $id = null)
    {
        $this->regions[$id] = $region;

    }

    /**
     * @param int|null $id
     * @return boolean
     */
    public function isRegion($id = null)
    {
        return key_exists($id,$this->regions);
    }

    /**
     * @param $branch
     * @param int|null $id
     */
    public function addBranch($branch, $id = null)
    {
        $this->branches[$id] = $branch;
    }

    /**
     * @param int|null $id
     * @return boolean
     */
    public function isBranch($id = null)
    {
        return key_exists($id,$this->branches);
    }

    /**
     * @param $cat
     * @param int|null $id
     */
    public function addCategory($cat, $id = null)
    {
        $this->categories[$id] = $cat;
    }

    /**
     * @param int|null $id
     * @return boolean
     */
    public function isCategory($id = null)
    {
        return key_exists($id,$this->categories);
    }

    /**
     * @param $edLevel
     * @param int|null $id
     */
    public function addEducation($edLevel, $id = null)
    {
        $this->educationLevels[$id] = $edLevel;
    }

    /**
     * @param int|null $id
     * @return boolean
     */
    public function isEducation($id = null)
    {
        return key_exists($id,$this->educationLevels);
    }

    /**
     * @param $country
     * @param int|null $id
     */
    public function addCountry($country, $id = null)
    {
        $this->countries[$id] = $country;
    }

    /**
     * @param int|null $id
     * @return boolean
     */
    public function isCountry($id = null)
    {
        return key_exists($id,$this->countries);
    }

    /**
     * @param $language
     * @param int|null $id
     */
    public function addLanguage($language, $id = null)
    {
        $this->languages[$id] = $language;
    }

    /**
     * @param int|null $id
     * @return boolean
     */
    public function isLanguage($id = null)
    {
        return key_exists($id,$this->languages);
    }

    /**
     * @param $salary
     * @param int|null $id
     */
    function addSalary($salary, $id = null)
    {
        $this->salaries[$id] = $salary;
    }

    /**
     * @param int|null $id
     * @return boolean
     */
    public function isSalary($id = null)
    {
        return key_exists($id,$this->salaries);
    }

    /**
     * @param $position
     * @param int|null $id
     */
    function addPosition($position, $id = null)
    {
        $this->positions[$id] = $position;
    }

    /**
     * @param int|null $id
     * @return boolean
     */
    public function isPosition($id = null)
    {
        return key_exists($id,$this->positions);
    }


}