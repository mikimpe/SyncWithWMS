<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Model;

use Exception;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Mikimpe\SyncWithWMS\Api\Data\WMSSyncRequestHistoryInterface;
use Mikimpe\SyncWithWMS\Api\Data\WMSSyncRequestHistoryInterfaceFactory;
use Mikimpe\SyncWithWMS\Api\Data\WMSSyncRequestHistorySearchResultInterface;
use Mikimpe\SyncWithWMS\Api\Data\WMSSyncRequestHistorySearchResultInterfaceFactory;
use Mikimpe\SyncWithWMS\Api\WMSSyncRequestHistoryRepositoryInterface;
use Mikimpe\SyncWithWMS\Model\ResourceModel\WMSSyncRequestHistory as ResourceModel;
use Mikimpe\SyncWithWMS\Model\ResourceModel\WMSSyncRequestHistory\Collection;
use Mikimpe\SyncWithWMS\Model\ResourceModel\WMSSyncRequestHistory\CollectionFactory;
use Psr\Log\LoggerInterface;

class WMSSyncRequestHistoryRepository implements WMSSyncRequestHistoryRepositoryInterface
{
    private ResourceModel $resourceModel;
    private LoggerInterface $logger;
    private WMSSyncRequestHistoryInterfaceFactory $WMSSyncRequestHistoryFactory;
    private CollectionFactory $collectionFactory;
    private CollectionProcessorInterface $collectionProcessor;
    private WMSSyncRequestHistorySearchResultInterfaceFactory $searchResultFactory;

    /**
     * @param LoggerInterface $logger
     * @param ResourceModel $resourceModel
     * @param WMSSyncRequestHistoryInterfaceFactory $WMSSyncRequestHistoryFactory
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param WMSSyncRequestHistorySearchResultInterfaceFactory $searchResultFactory
     */
    public function __construct(
        LoggerInterface $logger,
        ResourceModel $resourceModel,
        WMSSyncRequestHistoryInterfaceFactory $WMSSyncRequestHistoryFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        WMSSyncRequestHistorySearchResultInterfaceFactory $searchResultFactory
    ) {
        $this->logger = $logger;
        $this->resourceModel = $resourceModel;
        $this->WMSSyncRequestHistoryFactory = $WMSSyncRequestHistoryFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * @inheritDoc
     * @throws CouldNotSaveException
     */
    public function save(WMSSyncRequestHistoryInterface $WMSSyncRequestHistory): WMSSyncRequestHistoryInterface
    {
        try {
            $this->resourceModel->save($WMSSyncRequestHistory);
            return $WMSSyncRequestHistory;
        } catch (Exception $e) {
            $this->logger->error($e);
            throw new CouldNotSaveException(__('Unable to save WMSSyncRequestHistory entity'), $e);
        }
    }

    /**
     * @inheritDoc
     * @throws NoSuchEntityException
     */
    public function get(int $entryId): WMSSyncRequestHistoryInterface
    {
        /** @var WMSSyncRequestHistoryInterface $WMSSyncRequestHistory */
        $WMSSyncRequestHistory = $this->WMSSyncRequestHistoryFactory->create();
        $this->resourceModel->load($WMSSyncRequestHistory, $entryId);

        if (!$WMSSyncRequestHistory->getEntryId()) {
            throw new NoSuchEntityException(
                __("The WMSSyncRequestHistory entity that was requested doesn't exist. Verify the entity and try again.")
            );
        }

        return $WMSSyncRequestHistory;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): WMSSyncRequestHistorySearchResultInterface
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        /** @var WMSSyncRequestHistorySearchResultInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }

    /**
     * @inheritDoc
     * @throws NoSuchEntityException
     */
    public function delete(WMSSyncRequestHistoryInterface $WMSSyncRequestHistory): void
    {
        $this->deleteById($WMSSyncRequestHistory->getEntryId());
    }

    /**
     * @inheritDoc
     * @throws NoSuchEntityException
     * @throws Exception
     */
    public function deleteById(int $entryId): void
    {
        $entity = $this->get($entryId);
        $this->resourceModel->delete($entity);
    }
}
