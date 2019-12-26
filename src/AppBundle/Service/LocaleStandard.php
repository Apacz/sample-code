<?php
namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Created by PhpStorm.
 * User: apacz
 * Date: 18.07.2017
 * Time: 18:35
 */
class LocaleStandard
{
    public $mapFullLanguage = array('pl' => 'pl_PL', 'en' => 'en_US');

    /**
     * @var RequestStack $requestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFullLocale(){
        $request = $this->requestStack->getCurrentRequest();
        $locale = $request->getLocale();
        return $this->mapFullLanguage[$locale];
    }


}