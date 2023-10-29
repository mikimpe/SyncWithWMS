<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Test\Integration;

use Exception;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\TestFramework\Helper\Bootstrap;
use Mikimpe\SyncWithWMS\Api\Data\WMSSyncRequestHistoryInterface;
use Mikimpe\SyncWithWMS\Api\WMSSyncRequestHistoryRepositoryInterface;
use PHPUnit\Framework\TestCase;

class WMSSyncRequestHistoryRepositoryTest extends TestCase
{
    private ?WMSSyncRequestHistoryInterface $entitySaved = null;

    /**
     * @return void
     */
    public function testShouldSaveNewWMSSyncNotErrorRequestHistoryEntity(): void
    {
        $repository = Bootstrap::getObjectManager()->get(WMSSyncRequestHistoryRepositoryInterface::class);

        $entity = Bootstrap::getObjectManager()->create(WMSSyncRequestHistoryInterface::class);
        $entity->setSku('test');
        $entity->setStatusCode(200);
        $entity->setQtyReceived(10);
        $entity = $repository->save($entity);

        $this->entitySaved = $repository->get($entity->getEntryId());
        self::assertSame($entity->getEntryId(), $this->entitySaved->getEntryId());
        self::assertSame('test', $this->entitySaved->getSku());
        self::assertSame(200, $this->entitySaved->getStatusCode());
        self::assertSame(10, $this->entitySaved->getQtyReceived());
    }

    /**
     * @return void
     */
    public function testShouldSaveNewWMSSyncErrorRequestHistoryEntity(): void
    {
        $repository = Bootstrap::getObjectManager()->get(WMSSyncRequestHistoryRepositoryInterface::class);

        $entity = Bootstrap::getObjectManager()->create(WMSSyncRequestHistoryInterface::class);
        $entity->setSku('test');
        $entity->setStatusCode(503);
        $entity->setErrorMsg('Test Error');
        $entity = $repository->save($entity);

        $this->entitySaved = $repository->get($entity->getEntryId());
        self::assertSame($entity->getEntryId(), $this->entitySaved->getEntryId());
        self::assertSame('test', $this->entitySaved->getSku());
        self::assertSame(503, $this->entitySaved->getStatusCode());
        self::assertSame('Test Error', $this->entitySaved->getErrorMsg());
    }

    /**
     * @return void
     * @magentoDataFixture Mikimpe_SyncWithWMS::Test/Integration/_fixtures/wms_sync_not_error_request_history_entity.php
     */
    public function testShouldUpdateExistingWMSSyncRequestHistoryEntity(): void
    {
        $repository = Bootstrap::getObjectManager()->get(WMSSyncRequestHistoryRepositoryInterface::class);
        $searchCriteriaBuilder = Bootstrap::getObjectManager()->get(SearchCriteriaBuilder::class);

        $searchCriteria = $searchCriteriaBuilder->addFilter('sku', 'test-fixture')->create();
        $results = $repository->getList($searchCriteria)->getItems();
        self::assertCount(1, $results);
        $fixtureEntity = reset($results);
        self::assertSame('test-fixture', $fixtureEntity->getSku());
        self::assertSame(10, $fixtureEntity->getQtyReceived());

        $fixtureEntity->setQtyReceived(20);
        $repository->save($fixtureEntity);

        $newResults = $repository->getList($searchCriteria)->getItems();
        self::assertCount(1, $newResults);
        $entityUpdated = reset($newResults);
        self::assertSame('test-fixture', $entityUpdated->getSku());
        self::assertSame(20, $entityUpdated->getQtyReceived());
    }

    /**
     * @return void
     * @magentoDataFixture Mikimpe_SyncWithWMS::Test/Integration/_fixtures/wms_sync_not_error_request_history_entity.php
     * @magentoDataFixture Mikimpe_SyncWithWMS::Test/Integration/_fixtures/wms_sync_error_request_history_entity.php
     */
    public function testShouldDeleteExistingWMSSyncRequestHistoryEntity(): void
    {
        $repository = Bootstrap::getObjectManager()->get(WMSSyncRequestHistoryRepositoryInterface::class);
        $searchCriteriaBuilder = Bootstrap::getObjectManager()->get(SearchCriteriaBuilder::class);

        $searchCriteriaAllEntities = $searchCriteriaBuilder->create();
        $results = $repository->getList($searchCriteriaAllEntities)->getItems();
        self::assertCount(2, $results);

        $searchCriteriaTestFixture = $searchCriteriaBuilder->addFilter('sku', 'test-fixture')->create();
        $textFixtureResult = $repository->getList($searchCriteriaTestFixture)->getItems();
        $entityToDelete = reset($textFixtureResult);
        $repository->delete($entityToDelete);

        $newResults = $repository->getList($searchCriteriaAllEntities)->getItems();
        self::assertCount(1, $newResults);
        $entity = reset($newResults);
        self::assertSame('test-fixture-error', $entity->getSku());
    }

    /**
     * @return void
     */
    public function testShouldNotRetrieveNotExistingWMSSyncRequestEntity(): void
    {
        $repository = Bootstrap::getObjectManager()->get(WMSSyncRequestHistoryRepositoryInterface::class);

        $this->expectException(NoSuchEntityException::class);
        $repository->get(1);
    }

    /**
     * @return void
     */
    public function testShouldNotDeleteNotExistingWMSSyncRequestEntity(): void
    {
        $repository = Bootstrap::getObjectManager()->get(WMSSyncRequestHistoryRepositoryInterface::class);
        $entity = Bootstrap::getObjectManager()->create(WMSSyncRequestHistoryInterface::class);

        $this->expectException(NoSuchEntityException::class);
        $repository->delete($entity);
    }

    protected function tearDown(): void
    {
        if ($this->entitySaved) {
            $WMSSYncRequestRepository = Bootstrap::getObjectManager()->get(WMSSyncRequestHistoryRepositoryInterface::class);
            try {
                $WMSSYncRequestRepository->delete($this->entitySaved);
            } catch (Exception) {}
        }

        parent::tearDown();
    }
}
