<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Test\Integration;

use Exception;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\TestFramework\Helper\Bootstrap;
use Mikimpe\SyncWithWMS\Api\Data\WMSSyncRequestHistoryInterface;
use Mikimpe\SyncWithWMS\Api\WMSSyncRequestHistoryRepositoryInterface;
use Mikimpe\SyncWithWMS\Model\DoWMSSyncRequestAndSaveResult;
use Mikimpe\SyncWithWMS\Model\DTO\WMSSyncRequestResult;
use Mikimpe\SyncWithWMS\Model\Request\WMSSyncRequest;
use PHPUnit\Framework\TestCase;

class DoWMSSyncRequestAndSaveResultTest extends TestCase
{
    private ?WMSSyncRequestHistoryInterface $historyEntry = null;

    /**
     * @return void
     */
    public function testShouldCorrectlySaveSyncRequestResult(): void
    {
        $WMSSyncRequestResult = Bootstrap::getObjectManager()->create(WMSSyncRequestResult::class);
        $WMSSyncRequestResult->setSuccess(true);
        $WMSSyncRequestResult->setStatusCode(200);
        $WMSSyncRequestResult->setQty(10);
        $WMSSyncRequestMock = $this->getMockBuilder(WMSSyncRequest::class)->disableOriginalConstructor()->getMock();
        $WMSSyncRequestMock->method('execute')->willReturn($WMSSyncRequestResult);
        $subject = Bootstrap::getObjectManager()->create(
            DoWMSSyncRequestAndSaveResult::class,
            ['WMSSyncRequest' => $WMSSyncRequestMock]
        );

        $WMSSYncRequestRepository = Bootstrap::getObjectManager()->get(WMSSyncRequestHistoryRepositoryInterface::class);

        $searchCriteriaBuilder = Bootstrap::getObjectManager()->get(SearchCriteriaBuilder::class);
        $searchCriteria = $searchCriteriaBuilder->addFilter('sku', 'test')->create();
        $historyEntriesBefore = $WMSSYncRequestRepository->getList($searchCriteria)->getItems();
        self::assertCount(0, $historyEntriesBefore);

        $subject->execute('test');

        $historyEntriesAfter = $WMSSYncRequestRepository->getList($searchCriteria)->getItems();
        self::assertCount(1, $historyEntriesAfter);
        $this->historyEntry = reset($historyEntriesAfter);
        self::assertSame('test', $this->historyEntry->getSku());
        self::assertSame(200, $this->historyEntry->getStatusCode());
        self::assertSame(10, $this->historyEntry->getQtyReceived());
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        if ($this->historyEntry) {
            $WMSSYncRequestRepository = Bootstrap::getObjectManager()->get(WMSSyncRequestHistoryRepositoryInterface::class);
            try {
                $WMSSYncRequestRepository->delete($this->historyEntry);
            } catch (Exception) {}
        }

        parent::tearDown();
    }
}
