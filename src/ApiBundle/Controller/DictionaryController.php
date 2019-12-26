<?php

namespace ApiBundle\Controller;

use ApiBundle\OfferSearchParameters\OfferSearchParameters;
use CoreBundle\Controller\CoreController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use ApiBundle\Utils\DictionaryManager\DictionaryManager;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/dictionaries")
 */
class DictionaryController extends CoreController
{
    /**
     * @ApiDoc(
     *  section="Dictionaries",
     *  description="Get dictionaries which can be used to filter offers",
     *  output="ApiBundle\ViewModels\DictionaryViewModel"
     * )
     * @Route("/")
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        /** @var DictionaryManager $DictionaryManager */
        $dictionaryManager = $this->get('api.dictionary_manager');
        $serializer = $this->get('jms_serializer');

        $parametersModelInstance = new OfferSearchParameters($request->query->all());
        $offerValidator = $this->get('api.offer_list_validator');

        if (!$offerValidator->isValid($parametersModelInstance)) {
            return $this->sendSearchErrorResponse($offerValidator);
        }
        $results = $dictionaryManager->generate($parametersModelInstance);
        $results = (array)$results;
        foreach ($results as $key=>$value){
            if(empty($value)){
                unset($results[$key]);
            }
        }
        $serializedDictionaries = $serializer->serialize($results, "json");
        return new Response($serializedDictionaries, 200, array('Content-Type' => 'application/json', 'Access-Control-Allow-Origin' => '*'));
    }
}
