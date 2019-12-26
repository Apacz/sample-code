<?php
namespace ApiBundle\Search;

use ApiBundle\OfferSearchParameters\OfferSearchParameters;
use DevonshireBundle\Entity\Source;
use DevonshireBundle\Repository\AttributeGroupRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;

/**
 * User: jszutkowski
 * Date: 2017-05-25
 * Time: 15:04
 */
class OfferSearchService
{
    const title = 'title';
    const jobDescription = 'jobDescription';
    const JobCompanyProfile = 'JobCompanyProfile';
    const requirementsJobText = 'requirementsJobText';
    const workplaceJobText = 'workplaceJobText';
    const jobShortDescription = 'jobShortDescription';
    /**
     * @param PaginatorInterface $paginator
     * @param QueryBuilder $queryBuilder
     * @param OfferSearchParameters $searchParams
     * @param Source $source
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getPaginatedOffers(PaginatorInterface $paginator, QueryBuilder $queryBuilder, OfferSearchParameters $searchParams, Source $source)
    {
        $this->addSearchParams($queryBuilder, $searchParams, $source);
        return $paginator->paginate($queryBuilder->getQuery(), $searchParams->getPage(), $searchParams->getPageSize());
    }
    public function addSearchParamsWithResult(QueryBuilder $queryBuilder, OfferSearchParameters $searchParams, Source $source) : array
    {
        $qb = $this->addSearchParams($queryBuilder, $searchParams, $source);
        return $qb->getQuery()->getResult();

    }

    public function addSearchParams(QueryBuilder $queryBuilder, OfferSearchParameters $searchParams, Source $source) : QueryBuilder
    {
        $queryBuilder->distinct();
        $threeMonthsAgo = (new \DateTime())->modify('-3 months')->format("Y-m-d");
        $queryBuilder
            ->join('o.recruitment', 'r') //to hide offers from deleted recruitments
            ->andWhere('o.isRemoved = :isRemoved')->setParameter('isRemoved', false)
            ->andWhere("o.isActive = :isActive")->setParameter("isActive", true)
            ->andWhere('o.dateJobStart >= :publishedFrom')
        ;
        $queryBuilder->setParameter('publishedFrom', $threeMonthsAgo);

        $this->appendAttributesMatching($queryBuilder, AttributeGroupRepository::CATEGORY_ID, (array)$searchParams->getCategoryIds());
        $this->appendAttributesMatching($queryBuilder, AttributeGroupRepository::BRANCH_ID, (array)$searchParams->getBranchIds());
        $this->appendAttributesMatching($queryBuilder, AttributeGroupRepository::COUNTRY_ID, (array)$searchParams->getCountryIds());
        $this->appendAttributesMatching($queryBuilder, AttributeGroupRepository::EDUCATION_ID, (array)$searchParams->getEducationLevelIds());
        $this->appendAttributesMatching($queryBuilder, AttributeGroupRepository::LANGUAGE_ID, (array)$searchParams->getLanguageIds());
        $this->appendAttributesMatching($queryBuilder, AttributeGroupRepository::REGION_ID, (array)$searchParams->getRegionIds());
        $this->appendAttributesMatching($queryBuilder, AttributeGroupRepository::SALARY_ID, (array)$searchParams->getSalaryIds());
        $this->appendAttributesMatching($queryBuilder, AttributeGroupRepository::POSITION_ID, (array)$searchParams->getPositionIds());
       
        $keyword = $searchParams->getKeyword();
        if (null !== $keyword) {
            $queryBuilder->andWhere("(o.".OfferSearchService::jobDescription ." like :keyword) OR (o.". OfferSearchService::title." like :keyword)")
                ->setParameter('keyword', '%'.$keyword.'%');
        }

        $city = $searchParams->getCity();
        if (null !== $city){
            $queryBuilder->andWhere('o.cityName = :city')
                ->setParameter('city', $city);
        }

        if ($searchParams->getPublishedFrom()) {
            $queryBuilder->andWhere('o.dateJobStart >= :publishedFrom')->setParameter('publishedFrom', $searchParams->getPublishedFrom());
        }

        $queryBuilder->andWhere('o.source = :source')->setParameter('source', $source);

        return $queryBuilder;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param int $groupId
     * @param array $idsToFilterOn
     */
    private function appendAttributesMatching(QueryBuilder $queryBuilder, int $groupId, array $idsToFilterOn)
    {
        if ($idsToFilterOn) {
            $alias = 'a'.$groupId;
            $queryBuilder->innerJoin('o.attributes', $alias, Join::WITH, $alias.'.id IN (:idsA'.$groupId.') and '.$alias.'.attributeGroup=:agParam'.$groupId)
                ->setParameter('idsA' . $groupId, $idsToFilterOn)
                ->setParameter('agParam' . $groupId, $groupId);

        }
    }
}