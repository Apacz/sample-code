<?php

namespace ApiBundle\Utils\DictionaryManager;


use ApiBundle\OfferSearchParameters\OfferSearchParameters;
use ApiBundle\Search\OfferSearchService;
use ApiBundle\ViewModels\DictionaryViewModel;
use ApiBundle\ViewModels\DictionaryViewModelItem;
use DevonshireBundle\Entity\Attribute;
use DevonshireBundle\Entity\AttributeGroup;
use DevonshireBundle\Entity\AttributeGroupTranslation;
use DevonshireBundle\Entity\AttributeTranslation;
use DevonshireBundle\Entity\Language;
use DevonshireBundle\Entity\Offer;
use DevonshireBundle\Entity\Source;
use DevonshireBundle\Repository\AttributeGroupRepository;
use DevonshireBundle\Repository\SourceRepository;
use Doctrine\ORM\EntityManager;

class DictionaryManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var OfferSearchService
     */
    private $offerSearchService;

    /**
     * @var \DevonshireBundle\Repository\LanguageRepository
     */
    private $languageRepository;

    /**
     * @var \DevonshireBundle\Repository\SourceRepository
     */
    private $sourceRepository;

    /**
     * @var \DevonshireBundle\Repository\AttributeRepository
     */
    private $attributeRepository;

    public function __construct(EntityManager $entityManager, OfferSearchService $offerSearchService)
    {
        $this->entityManager = $entityManager;
        $this->offerSearchService = $offerSearchService;

        $this->languageRepository = $entityManager->getRepository(Language::class);
        $this->sourceRepository = $entityManager->getRepository(Source::class);
        $this->attributeRepository = $entityManager->getRepository(Attribute::class);
    }

    public function generate(OfferSearchParameters $searchParameters): DictionaryViewModel
    {

        $language = $this->languageRepository->findLanguage($searchParameters->getLanguage());
        $source = $this->sourceRepository->findSource($searchParameters->getSource());

        $attributes = $this->attributeRepository->findAllAttributeGroups($source, $language);
        $offerCounts = $this->getOfferCounts($source, $searchParameters);
        $viewModel = new DictionaryViewModel();

        foreach ($attributes as $attribute) {
            if($searchParameters->getSource() != SourceRepository::SOURCE_DE_CODE ||
                $attribute['agId'] != AttributeGroupRepository::POSITION_ID) {
                $agName = $attribute['agName'];
                $attributeGroupSetter = 'add' . ucfirst($agName);
                $attributeGroupIs = 'is' . ucfirst($agName);
                if (method_exists($viewModel, $attributeGroupSetter) && method_exists($viewModel,$attributeGroupIs)) {
                    if(!$viewModel->$attributeGroupIs($attribute['aId'])){
                        $viewModel->$attributeGroupSetter(new DictionaryViewModelItem($attribute['aId'], $attribute['name'], $offerCounts[$attribute['aId']] ?? 0), $attribute['aId']);
                    }
                }
            }
        }

        return $viewModel;
    }

    protected function getOfferCounts(Source $source, OfferSearchParameters $searchParameters): array
    {
        $offerRepository = $this->entityManager->getRepository(Offer::class);
        $count = [];
            $offersCountQueryBuilder = $offerRepository->getOfferCountsInAttributesQueryBuilder();
            $var = $this->offerSearchService->addSearchParamsWithResult($offersCountQueryBuilder, $searchParameters, $source);
            $count = $this->parseOffersCounts($var, $count);
        return $count;
    }

    protected function parseOffersCounts($counts, $result): array
    {
        foreach ($counts as $count)
        {
            $var = $result[$count['id']] ?? 0;
            $result[$count['id']] = $var + $count['quantity'];
        }

        return $result;
    }
}