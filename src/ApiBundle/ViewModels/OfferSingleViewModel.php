<?php
/**
 * Created by PhpStorm.
 * User: patry
 * Date: 2017-04-03
 * Time: 10:40
 */

namespace ApiBundle\ViewModels;
use JMS\Serializer\Annotation\Type;

/**
 * Class OfferSingle
 * @package ApiBundle\ViewModels
 */
class OfferSingleViewModel
{

    /**
     * @Type(name="integer")
     */
    public $id;

    /**
     * @Type(name="string")
     */
    public $referenceNumber;

    /**
     * YYYY-MM-DD
     * @Type(name="string")
     */
    public $publicationDate;

    /**
     * @Type(name="string")
     */
    public $jobTitle;

    /**
     * @Type(name="string")
     */
    public $companyIntroduction;

    /**
     * @Type(name="string")
     */
    public $jobShortDescription;

    /**
     * @Type(name="string")
     */
    public $jobDescription;

    /**
     * @Type(name="string")
     */
    public $city;

    /**
     * @Type(name="array")
     */
    public $regions = [];


    /**
     * @Type(name="array")
     */
    public $countries = [];


    /**
     * @Type(name="array")
     */
    public $categories = [];


    /**
     * @Type(name="array")
     */
    public $branches = [];


    /**
     * @Type(name="array")
     */
    public $salary = [];


    /**
     * @Type(name="array")
     */
    public $jobLevels = [];


    /**
     * @Type(name="array")
     */
    public $languages = [];


    /**
     * @Type(name="array")
     */
    public $education = [];


    /**
     * @Type(name="array")
     */
    public $experience = [];
    
    /**
     * @Type(name="array")
     */
    public $positions = [];
    

    /**
     * @Type(name="string")
     */
    public $applyUrl;

    /**
     * @Type(name="string")
     */
    public $contactEmail;

    /**
     * @Type(name="string")
     */
    public $keywords = [];

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
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getReferenceNumber()
    {
        return $this->referenceNumber;
    }

    /**
     * @param mixed $referenceNumber
     */
    public function setReferenceNumber($referenceNumber)
    {
        $this->referenceNumber = $referenceNumber;
    }

    /**
     * @return mixed
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * @param mixed $publicationDate
     */
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;
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
     * @return mixed
     */
    public function getCompanyIntroduction()
    {
        return $this->companyIntroduction;
    }

    /**
     * @param mixed $companyIntroduction
     */
    public function setCompanyIntroduction($companyIntroduction)
    {
        $this->companyIntroduction = $companyIntroduction;
    }

    /**
     * @return mixed
     */
    public function getJobShortDescription()
    {
        return $this->jobDescription;
    }

    /**
     * @param mixed $jobShortDescription
     */
    public function setJobShortDescription($jobShortDescription)
    {
        $this->jobShortDescription = $jobShortDescription;
    }

    /**
     * @return mixed
     */
    public function getJobDescription()
    {
        return $this->jobDescription;
    }

    /**
     * @param mixed $jobDescription
     */
    public function setJobDescription($jobDescription)
    {
        $this->jobDescription = $jobDescription;
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
     * @return array
     */
    public function getRegions()
    {
        return $this->regions;
    }

    /**
     * @param array $regions
     */
    public function setRegions($regions)
    {
        $this->regions = $regions;
    }

    /**
     * @return array
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * @param array $countries
     */
    public function setCountries($countries)
    {
        $this->countries = $countries;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return array
     */
    public function getBranches()
    {
        return $this->branches;
    }

    /**
     * @param array $branches
     */
    public function setBranches($branches)
    {
        $this->branches = $branches;
    }

    /**
     * @return array
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * @param array $salary
     */
    public function setSalary($salary)
    {
        $this->salary = $salary;
    }

    /**
     * @return array
     */
    public function getJobLevels()
    {
        return $this->jobLevels;
    }

    /**
     * @param array $jobLevels
     */
    public function setJobLevels($jobLevels)
    {
        $this->jobLevels = $jobLevels;
    }

    /**
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param array $languages
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

    /**
     * @return array
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * @param array $education
     */
    public function setEducation($education)
    {
        $this->education = $education;
    }

    /**
     * @return array
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * @param array $experience
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
    }

    /**
     * @return array
     */    
    function getPositions()
    {
        return $this->positions;
    }

    /**
     * @param array $positions
     */
    function setPositions($positions)
    {
        $this->positions = $positions;
    }
    
    /**
     * @return mixed
     */
    public function getApplyUrl()
    {
        return $this->applyUrl;
    }

    /**
     * @param mixed $applyUrl
     */
    public function setApplyUrl($applyUrl)
    {
        $applyUrl2 =  str_replace("http://www.devonshire.pl", "http://job.devire.pl", $applyUrl);
        $this->applyUrl =  str_replace("http://www.devonshire.de", "http://job.devire.de", $applyUrl2);
    }

    /**
     * @return mixed
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * @param mixed $contactEmail
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
    }

    /**
     * @return mixed
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param mixed $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }
}