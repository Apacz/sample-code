<?php
/**
 * Created by PhpStorm.
 * User: patry
 * Date: 2017-03-27
 * Time: 10:53
 */

namespace ApiBundle\Utils\OfferSearchValidator;


use ApiBundle\OfferSearchParameters\OfferSearchParameters;
use Doctrine\ORM\EntityManager;

/**
 * Class OfferSearchValidator
 * @package ApiBundle\Utils\OfferSearchValidator
 */
class OfferSearchValidator
{

    /**
     *
     */
    const SORT_DESC = 'desc';
    /**
     *
     */
    const SORT_ASC = 'asc';

    /**
     *
     */
    const VALIDATE_NUMBER = 1;


    /**
     *
     */
    const VALIDATE_NUMBER_ARRAY = 6;
    /**
     *
     */
    const VALIDATE_STRING = 2;
    /**
     *
     */
    const VALIDATE_DATE = 3;
    /**
     *
     */
    const VALIDATE_DIRECTION = 4;
    /**
     *
     */
    const VALIDATE_PAGE_SIZE = 5;

    const VALIDATE_KEYWORD = 7;


    /**
     * @var array
     */
    private $errors = array();
    /**
     * @var EntityManager
     */
    private $entityManager;


    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $parametersModel
     * @return bool
     */
    public function isValid(OfferSearchParameters $parametersModel)
    {
        $objectVars = $parametersModel->getObjectVars();
        $objectVars = array_filter($objectVars);
        foreach ($objectVars as $property => $value) {
            $validatorMethod = $this->getValidatorByPropertyName($property);
            if (method_exists($this, $validatorMethod)) {
                $this->$validatorMethod($value);
            }
        }

        if ($this->hasErrors()) {
            return false;
        }

        return true;
    }


    /**
     * @return string
     */
    public function getJsonErrors()
    {
        return json_encode($this->getErrors());
    }


    /**
     * @param $error
     */
    private function addError($error)
    {
        $arr = $this->getErrors();
        $arr[] = $error;
        $this->setErrors($arr);
    }

    /**
     * @return bool
     */
    private function hasErrors()
    {
        return !empty($this->getErrors());
    }


    /**
     * @param $key
     * @return string
     */
    private function getValidatorByPropertyName($key)
    {
        return 'validate'.ucfirst($key);
    }


    private function validate($type, $value, $name = '')
    {
        switch ($type) {
            case self::VALIDATE_NUMBER:

                if (!is_numeric($value)) {
                    $this->addError(sprintf("OfferSearchValidator: %s must be a number. ('%s' given)", $name, $value));
                }
                break;

            case self::VALIDATE_NUMBER_ARRAY:


                if (!is_array($value)) {
                    $this->addError(sprintf("OfferSearchValidator: %s must be an array. ('%s' given)", $name, $value));
                    break;
                }


                if (empty($value)) {
                    $this->addError(sprintf("OfferSearchValidator: %s - array can not be empty.", $name));
                    break;
                }


                foreach ($value as $k => $v) {
                    if (!is_numeric($v)) {
                        $this->addError(
                            sprintf(
                                "OfferSearchValidator: %s - All values must be numbers. ('%s' given)",
                                $name,
                                $v
                            )
                        );
                    }
                }
                break;


            case self::VALIDATE_STRING:
                if (!is_string($value)) {
                    $this->addError(sprintf("OfferSearchValidator: %s must be a string. ('%s' given)", $name, $value));
                }
                break;

            case self::VALIDATE_KEYWORD:
                if (!(is_string($value) || is_numeric($value) || !empty($value))) {
                    $this->addError(sprintf("OfferSearchValidator: %s must be a not empty string or number. ('%s' given)", $name, $value));
                }
                break;

            case self::VALIDATE_DATE:
                $fromDate = new \DateTime($value);
                if ($fromDate->format("Y-m-d") !== $value) {
                    $this->addError(sprintf("OfferSearchValidator: date parse error. ('%s' given)", $value));
                }
                break;

            case self::VALIDATE_DIRECTION:
                if (strtolower($value) !== self::SORT_DESC && strtolower($value) !== self::SORT_ASC) {
                    $this->addError("OfferSearchValidator: incorrect direction parameter. Allowed values are: asc, desc");
                }
                break;

            case self::VALIDATE_PAGE_SIZE:
                if (!is_numeric($value)) {
                    $this->addError(sprintf("OfferSearchValidator: %s must be a number. ('%s' given)", $name, $value));
                }

                if ($value > 100 || $value < 0) {
                    $this->addError(
                        sprintf("OfferSearchValidator: %s must be between 0 and 100. ('%s' given)", $name, $value)
                    );
                }
                break;
        }


    }


    /**
     * @param null $publishedFrom
     * @throws \Exception
     */
    public function validatePublishedFrom($publishedFrom = null)
    {
        $this->validate(self::VALIDATE_DATE, $publishedFrom);
    }


    /**
     * @param mixed $categoryId
     */
    public function validateCategoryIds($categoryId)
    {
        $this->validate(self::VALIDATE_NUMBER_ARRAY, $categoryId, 'categoryId');
    }


    /**
     * @param $branchId
     * @throws \Exception
     */
    public function validateBranchIds($branchId)
    {
        $this->validate(self::VALIDATE_NUMBER_ARRAY, $branchId, 'branchId');
    }


    /**
     * @param mixed $regionId
     */
    public function validateRegionIds($regionId)
    {
        $this->validate(self::VALIDATE_NUMBER_ARRAY, $regionId, 'regionId');
    }


    /**
     * @param mixed $countryId
     */
    public function validateCountryIds($countryId)
    {
        $this->validate(self::VALIDATE_NUMBER_ARRAY, $countryId, 'countryId');
    }

    /**
     * @param mixed $salaryId
     */
    public function validateSalaryIds($salaryId)
    {
        $this->validate(self::VALIDATE_NUMBER_ARRAY, $salaryId, 'salaryId');
    }


    /**
     * @param mixed $languageId
     */
    public function validateLanguageIds($languageId)
    {
        $this->validate(self::VALIDATE_NUMBER_ARRAY, $languageId, 'languageId');
    }


    /**
     * @param mixed $educationId
     */
    public function validateEducationIds($educationId)
    {
        $this->validate(self::VALIDATE_NUMBER_ARRAY, $educationId, 'educationId');
    }

    /**
     * @param mixed $city
     */
    public function validateCity($city)
    {
        $this->validate(self::VALIDATE_STRING, $city, 'city');
    }

    /**
     * @param mixed $published
     */
    public function validatePublished($published)
    {
        $this->validate(self::VALIDATE_DATE, $published);
    }


    /**
     * @param mixed $direction
     */
    public function validateDirection($direction)
    {
        $this->validate(self::VALIDATE_DIRECTION, $direction);
    }

    /**
     * @param mixed $jobTitle
     */
    public function validateJobTitle($jobTitle)
    {
        $this->validate(self::VALIDATE_STRING, $jobTitle, 'Job title');
    }


    /**
     * @param mixed $sort
     */
    public function validateSort($sort)
    {
        $this->validate(self::VALIDATE_STRING, $sort, 'sort');
    }
    
    /**
     * @param mixed $page
     */
    public function validatePage($page)
    {
        $this->validate(self::VALIDATE_NUMBER, $page, 'page');
    }

    /**
     * @param $pageSize
     */
    public function validatePageSize($pageSize)
    {
        $this->validate(self::VALIDATE_PAGE_SIZE, $pageSize, 'page size');
    }

    /**
     * @param mixed $pageNumber
     */
    public function validatePageNumber($pageNumber)
    {
        $this->validate(self::VALIDATE_NUMBER, $pageNumber, 'page');
    }


    public function validateKeyword($keyword){
        $this->validate(self::VALIDATE_KEYWORD, $keyword, 'keyword');
    }


    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }


}