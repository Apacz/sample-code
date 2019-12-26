<?php

namespace ApiBundle\Utils\OfferManager;


use ApiBundle\OfferSearchParameters\OfferSearchParameters;
use ApiBundle\Search\OfferSearchService;
use ApiBundle\ViewModels\OfferSingleViewModel;
use ApiBundle\ViewModels\OffersViewModel\OffersViewModel;
use ApiBundle\ViewModels\OffersViewModel\OffersViewModelItem;
use DevonshireBundle\Entity\Attribute;
use DevonshireBundle\Entity\Offer;
use DevonshireBundle\Entity\Source;
use DevonshireBundle\Repository\AttributeGroupRepository;
use DevonshireBundle\Repository\OfferRepository;
use DevonshireBundle\Repository\SourceRepository;
use DevonshireBundle\Utils\EmailHelper;
use DevonshireBundle\Utils\Shared\OfferContentBuilder;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use RecruitmentBundle\Entity\RecruitmentTag;
use Symfony\Component\Routing\RouterInterface;


class OfferManager
{

    /** @var EntityManager */
    private $entityManager;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @var OfferContentBuilder
     */
    private $contentBuilder;

    /**
     * @var OfferSearchService
     */
    private $offerSearch;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(EntityManager $entityManager, Paginator $paginator, OfferContentBuilder $contentBuiler,
                                OfferSearchService $offerSearch, RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->contentBuilder = $contentBuiler;
        $this->offerSearch = $offerSearch;
        $this->router = $router;
    }


    /**
     * @param OfferSearchParameters $offerSearchParametersModel
     * @return OffersViewModel
     */
    public function find(OfferSearchParameters $offerSearchParametersModel)
    {
        $offerRepository = $this->entityManager->getRepository(Offer::class);
        $offerQueryBuilder = $offerRepository->getSearchOffersQueryBuilder();
        $this->modifySortValue();
        $source = $this->entityManager->getRepository(Source::class)->findSource($offerSearchParametersModel->getSource());
        $paginatedOffers = $this->offerSearch->getPaginatedOffers($this->paginator, $offerQueryBuilder, $offerSearchParametersModel, $source);

        $paginated = $paginatedOffers->getItems();
        $offersViewModel = new OffersViewModel();
        $offersViewModel->setItemsTotal($paginatedOffers->getTotalItemCount());
        
       
        
        if (!empty($paginated)) {
            foreach ($paginated as $item) {
                /**@var \DateTime $d**/
                $offersViewModelItem = new OffersViewModelItem();
                $offersViewModelItem->setId($this->contentBuilder->getExternalOfferId($item));
                $d = $item->getDateJobStart();
                $offersViewModelItem->setPublished($d->format('Y-m-d'));
                $description = $this->contentBuilder->build($item, true, $source->getId());
                $offersViewModelItem->setJobDescription($description);
                $offersViewModelItem->setTitle($item->getTitle());
                $offersViewModel->addItem($offersViewModelItem);
            }
        }

        return $offersViewModel;
    }

    protected function modifySortValue()
    {
        // API backwards compatibility: previously field dateJobStart was called published
        if (isset($_GET['sort']))
        {
            $sort = $_GET['sort'];
            if ($sort == 'published') {
                $sort = 'dateJobStart';
            }
            $_GET['sort'] = 'o.'.$sort;
        }
        else //API default sort
        {
            $_GET['sort'] = 'o.dateJobStart';
            if (!isset($_GET['direction'])) {
                $_GET['direction'] = 'desc';
            }
        }
    }


    /**
     * @param $offer
     * @return OfferSingleViewModel
     */
    public function createOfferViewModel(Offer $offer, $languageCode)
    {
        /**
         * @var $offerRepository OfferRepository
         * @var Offer $offer;
         */

        $offerAttributes = $offer->getAttributes();

        $offerSingleResponseModel = new OfferSingleViewModel;

        $offerSingleResponseModel->setId($this->contentBuilder->getExternalOfferId($offer));       
        $offerSingleResponseModel->setReferenceNumber($this->contentBuilder->getReferenceNumber($offer));
        $d = $offer->getDateJobStart();
        $offerSingleResponseModel->setPublicationDate(is_object($d) && 'DateTime' === get_class($d) ? $d->format('Y-m-d') : null);
        $offerSingleResponseModel->setJobTitle($offer->getRecruitment()->getPosition());
        $offerSingleResponseModel->setApplyUrl($offer->getApplyURL());

        $offerSingleResponseModel->setContactEmail(EmailHelper::getOfferContactEmail($offer));
        $offerSingleResponseModel->setCompanyIntroduction($this->contentBuilder->getCompanyIntroduction($languageCode));
        $offerSingleResponseModel->setJobShortDescription($offer->getJobShortDescription());
        $offerSingleResponseModel->setJobDescription($this->contentBuilder->build($offer, false, $languageCode, $offer->getSource()->getId(), true));
        if($offer->getSource()->getId() == SourceRepository::SOURCE_DE){
            $regions = $this->getAttributesCollection($offerAttributes, AttributeGroupRepository::REGION_ID);
            if($regions){
                $regionNames = array_map(function($r) { return $r['name']; }, $regions);
                $offerSingleResponseModel->setCity(implode(', ', $regionNames));
            } else {
                $offerSingleResponseModel->setCity($offer->getCityName());
            }
        } else {
            $offerSingleResponseModel->setCity($offer->getCityName());
        }
        $offerSingleResponseModel->setRegions($this->getAttributesCollection($offerAttributes, AttributeGroupRepository::REGION_ID));
        $offerSingleResponseModel->setCountries($this->getAttributesCollection($offerAttributes, AttributeGroupRepository::COUNTRY_ID));
        $offerSingleResponseModel->setCategories($this->getAttributesCollection($offerAttributes, AttributeGroupRepository::CATEGORY_ID));
        $offerSingleResponseModel->setBranches($this->getAttributesCollection($offerAttributes, AttributeGroupRepository::BRANCH_ID));
        $offerSingleResponseModel->setSalary($this->getAttributesCollection($offerAttributes, AttributeGroupRepository::SALARY_ID));
        $offerSingleResponseModel->setJobLevels($this->getAttributesCollection($offerAttributes, AttributeGroupRepository::JOB_LEVEL));
        $offerSingleResponseModel->setLanguages($this->getAttributesCollection($offerAttributes, AttributeGroupRepository::LANGUAGE_ID));
        $offerSingleResponseModel->setEducation($this->getAttributesCollection($offerAttributes, AttributeGroupRepository::EDUCATION_ID));
        $offerSingleResponseModel->setExperience($this->getAttributesCollection($offerAttributes, AttributeGroupRepository::EXPERIENCE));
        $offerSingleResponseModel->setPositions($this->getAttributesCollection($offerAttributes, AttributeGroupRepository::POSITION_ID));
        $offerSingleResponseModel->setKeywords($this->getKeywords($offer->getRecruitment()->getModelTags()));
        return $offerSingleResponseModel;

    }

    private function getAttributesCollection(Collection $attributes, int $groupId){
        /** @var Attribute $attribute */

        $filteredAttributes = $attributes->filter(
            function (Attribute  $attribute) use ($groupId) {
                return $attribute->getAttributeGroup()->getId() == $groupId;
            }
        );
        $collection = [];
        foreach ($filteredAttributes as $attribute) {
            $collection[] = ['id'=>$attribute->getId(), 'name'=>$attribute->getName()];
        }
        return $collection;
    }

    private function getKeywords(Collection $tags){
        $array = [];
        foreach($tags as $tag){
            /** @var RecruitmentTag $tag */
            $array[] = (string)$tag->getTag();
        }
        return implode($array, ", ");
    }
}