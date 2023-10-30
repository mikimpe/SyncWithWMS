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
        $subject = Bootstrap::getObjectManager()->get(WMSSyncRequestHistoryRepositoryInterface::class);

        $entity = Bootstrap::getObjectManager()->create(WMSSyncRequestHistoryInterface::class);
        $entity->setSku('test');
        $entity->setStatusCode(200);
        $entity->setQtyReceived(10);
        $entity = $subject->save($entity);

        $this->entitySaved = $subject->get($entity->getEntryId());
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
        $subject = Bootstrap::getObjectManager()->get(WMSSyncRequestHistoryRepositoryInterface::class);

        $entity = Bootstrap::getObjectManager()->create(WMSSyncRequestHistoryInterface::class);
        $entity->setSku('test');
        $entity->setStatusCode(503);
        $entity->setErrorMsg('Test Error');
        $entity = $subject->save($entity);

        $this->entitySaved = $subject->get($entity->getEntryId());
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
        $subject = Bootstrap::getObjectManager()->get(WMSSyncRequestHistoryRepositoryInterface::class);
        $searchCriteriaBuilder = Bootstrap::getObjectManager()->get(SearchCriteriaBuilder::class);

        $searchCriteria = $searchCriteriaBuilder->addFilter('sku', 'test-fixture')->create();
        $results = $subject->getList($searchCriteria)->getItems();
        self::assertCount(1, $results);
        $fixtureEntity = reset($results);
        self::assertSame('test-fixture', $fixtureEntity->getSku());
        self::assertSame(10, $fixtureEntity->getQtyReceived());

        $fixtureEntity->setQtyReceived(20);
        $subject->save($fixtureEntity);

        $newResults = $subject->getList($searchCriteria)->getItems();
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
        $subject = Bootstrap::getObjectManager()->get(WMSSyncRequestHistoryRepositoryInterface::class);
        $searchCriteriaBuilder = Bootstrap::getObjectManager()->get(SearchCriteriaBuilder::class);

        $searchCriteriaAllEntities = $searchCriteriaBuilder->create();
        $results = $subject->getList($searchCriteriaAllEntities)->getItems();
        self::assertCount(2, $results);

        $searchCriteriaTestFixture = $searchCriteriaBuilder->addFilter('sku', 'test-fixture')->create();
        $textFixtureResult = $subject->getList($searchCriteriaTestFixture)->getItems();
        $entityToDelete = reset($textFixtureResult);
        $subject->delete($entityToDelete);

        $newResults = $subject->getList($searchCriteriaAllEntities)->getItems();
        self::assertCount(1, $newResults);
        $entity = reset($newResults);
        self::assertSame('test-fixture-error', $entity->getSku());
    }

    /**
     * @return void
     */
    public function testShouldNotRetrieveNotExistingWMSSyncRequestEntity(): void
    {
        $subject = Bootstrap::getObjectManager()->get(WMSSyncRequestHistoryRepositoryInterface::class);

        $this->expectException(NoSuchEntityException::class);
        $subject->get(1);
    }

    /**
     * @return void
     */
    public function testShouldNotDeleteNotExistingWMSSyncRequestEntity(): void
    {
        $subject = Bootstrap::getObjectManager()->get(WMSSyncRequestHistoryRepositoryInterface::class);
        $entity = Bootstrap::getObjectManager()->create(WMSSyncRequestHistoryInterface::class);

        $this->expectException(NoSuchEntityException::class);
        $subject->delete($entity);
    }

    /**
     * @inheritDoc
     */
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
