<?php
declare(strict_types=1);

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\TestFramework\Helper\Bootstrap;
use Mikimpe\SyncWithWMS\Api\WMSSyncRequestHistoryRepositoryInterface;

$repository = Bootstrap::getObjectManager()->get(WMSSyncRequestHistoryRepositoryInterface::class);
$searchCriteriaBuilder = Bootstrap::getObjectManager()->get(SearchCriteriaBuilder::class);

$searchCriteria = $searchCriteriaBuilder->addFilter('sku', 'test-fixture')->create();
$results = $repository->getList($searchCriteria)->getItems();
$entity = reset($results);

if ($entity) {
    $repository->delete($entity);
}
