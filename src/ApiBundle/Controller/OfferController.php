<?php

namespace ApiBundle\Controller;

use ApiBundle\OfferSearchParameters\OfferSearchParameters;
use ApiBundle\Utils\OfferManager\OfferManager;
use ApiBundle\Utils\OfferSearchValidator\OfferSearchValidatorSingle;
use CoreBundle\Controller\CoreController;
use DevonshireBundle\Entity\Offer;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use RecruitmentBundle\Repository\ApplicationSourceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/offers")
 */
class OfferController extends CoreController
{

    /**
     * @ApiDoc(
     *  section="Offers",
     *  description="Gets list of offers filtered by provided criteria",
     *  output="ApiBundle\ViewModels\OffersViewModel\OffersViewModel",
     *  parameters={
     *      {"name"="keyword", "dataType"="string", "required"=false, "description"="Mateches offers which have given keyword in title or job description"},
     *      {"name"="category", "dataType"="array of integers", "format"="1,2,3", "required"=false, "description"="Check /api/dictionaries for allowed values"},
     *      {"name"="branch", "dataType"="array of integers", "required"=false, "format"="1,2,3", "description"="Check /api/dictionaries for allowed values"},
     *      {"name"="region", "dataType"="array of integers", "required"=false, "format"="1,2,3", "description"="Check /api/dictionaries for allowed values"},
     *      {"name"="country", "dataType"="array of integers", "required"=false, "format"="1,2,3", "description"="Check /api/dictionaries for allowed values"},
     *      {"name"="salary", "dataType"="array of integers", "required"=false, "format"="1,2,3", "description"="Check /api/dictionaries for allowed values"},
     *      {"name"="language", "dataType"="array of integers", "required"=false, "format"="1,2,3", "description"="Check /api/dictionaries for allowed values"}, 
     *      {"name"="educationLevel", "dataType"="array of integers", "required"=false, "format"="1,2,3", "description"="Check /api/dictionaries for allowed values"},
     *      {"name"="position", "dataType"="array of integers", "required"=false, "format"="1,2,3", "description"="Check /api/dictionaries for allowed values"},
     *      {"name"="city", "dataType"="string", "required"=false, "description"="Matches offers by city"},
     *      {"name"="publishedFrom", "dataType"="date", "required"=false, "format"="YYYY-MM-DD", "description"="Minimum publication date"},
     *      {"name"="pageSize", "dataType"="integer", "required"=false, "description"="Number of rows in a single page. Default value: 25."},
     *      {"name"="page", "dataType"="integer", "required"=false, "description"="Page number. Default value: 1 (first page)"},
     *      {"name"="sort", "dataType"="string", "required"=false, "description"="Allows to sort by specified filed. Allowed values: id, title, published, jobDescription. Default: id, ascending"},
     *      {"name"="direction", "dataType"="string", "required"=false, "description"="Sort direction. Allowed values: asc, desc. Default: desc (when sort parameter provided)"}
     *  }
     * )
     * @Route("/")
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $offerValidator = $this->get('api.offer_list_validator');
        $parametersModelInstance = new OfferSearchParameters($request->query->all());

        $threeMonthsAgo = (new \DateTime())->modify('-3 months')->format("Y-m-d");
        $parametersModelInstance->setPublishedFrom($threeMonthsAgo);

        if (!$offerValidator->isValid($parametersModelInstance)) {
            return $this->sendSearchErrorResponse($offerValidator);
        }

        $offerManager = $this->get('api.offer_manager');
        $offers = $offerManager->find($parametersModelInstance);
        $serializer = $this->get('jms_serializer');
        $serializedOffers = $serializer->serialize($offers, "json");
        return new Response($serializedOffers, 200, array('Content-Type' => 'application/json', 'Access-Control-Allow-Origin' => '*'));
    }



    /**
     * @ApiDoc(
     *  section="Offers",
     *  description="Get single offer details by id",
     *  output="ApiBundle\ViewModels\OfferSingleViewModel",
     *  requirements={
     *      {"name"="id", "dataType"="integer",  "requirement"="\d+", "description"="Offer id"}
     *  }
     * )
     * @Route("/{id}")
     * @Method({"GET"})
     */
    public function viewAction(Request $request, $id)
    {
        if (is_numeric($id))
        {
            /** @var Offer $offer */
            $offer = $this->getDoctrine()->getManager()->getRepository("DevonshireBundle:Offer")->findSingleOfferByIdForApi((int)$id);
            //if ($offer && $offer->getFirstSystemOfferPublication() == null)
            //{
            //    $offer = null;
            //}
        }
        else
        {
            $offer = $this->getDoctrine()->getManager()->getRepository("DevonshireBundle:Offer")->findSingleOfferByGuidForApi($id);
        }


        if(!$offer){
            return new JsonResponse(
                array("messages" => "Offer not found", "status" => "not_found"),
                Response::HTTP_NOT_FOUND
            );
        }

        /**
         * @var OfferManager $offerManager ;
         * @var OfferSearchValidatorSingle $offerValidatorSingle ;
         * @var OfferSearchParameters $parametersModel ;
         */
        $offerManager = $this->get('api.offer_manager');
        
        $languageCode = $request->query->get('language');
        if (!$languageCode) {
            $languageCode = $offer->getSource()->getName();
        }
        
        $offerViewModel = $offerManager->createOfferViewModel($offer, $languageCode);

        $source = $request->query->get('source');
        $applyUrl = $offerViewModel->getApplyUrl();
        if ($source == 'pl' || $source == null) {
            $applyUrl .= (parse_url($applyUrl, PHP_URL_QUERY) ? '&' : '?') . 'application_source=' . ApplicationSourceRepository::APPLICATION_SOURCE_DEVIRE_PL_ID;
        } else if ($source== 'de') {
            $applyUrl .= (parse_url($applyUrl, PHP_URL_QUERY) ? '&' : '?') . 'application_source=' . ApplicationSourceRepository::APPLICATION_SOURCE_DEVIRE_DE_ID;
        } else if ($source== 'cz') {
            $applyUrl .= (parse_url($applyUrl, PHP_URL_QUERY) ? '&' : '?') . 'application_source=' . ApplicationSourceRepository::APPLICATION_SOURCE_DEVIRE_CZ_ID;
        }
        $offerViewModel->setApplyUrl($applyUrl);

        $serializer = $this->get('jms_serializer');
        $serializedOffer = $serializer->serialize($offerViewModel, "json");
        return new Response($serializedOffer, 200, array('Content-Type' => 'application/json', 'Access-Control-Allow-Origin' => '*'));

    }

}
