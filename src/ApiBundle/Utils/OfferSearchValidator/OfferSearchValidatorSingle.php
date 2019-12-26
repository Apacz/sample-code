<?php
/**
 * Created by PhpStorm.
 * User: patry
 * Date: 2017-04-03
 * Time: 13:14
 */

namespace ApiBundle\Utils\OfferSearchValidator;


/**
 * Class OfferSearchValidatorSingle
 * @package ApiBundle\Utils\OfferSearchValidator
 */
/**
 * Class OfferSearchValidatorSingle
 * @package ApiBundle\Utils\OfferSearchValidator
 */
class OfferSearchValidatorSingle
{


    /**
     * @var array
     */
    private $errors = array();


    /**
     * @param $offerSearchParameter
     * @return bool
     */
    public function isValid($offerSearchParameter)
    {

        if (!is_numeric($offerSearchParameter)) {
            $this->addError(
                sprintf("OfferSearchValidatorSingle: offer id must be a number! ('%s' given)", $offerSearchParameter)
            );
        }

        if ($this->hasErrors()) {
            return false;
        }

        return true;


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


}