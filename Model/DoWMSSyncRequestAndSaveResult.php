<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use Mikimpe\SyncWithWMS\Api\Data\WMSSyncRequestHistoryInterfaceFactory;
use Mikimpe\SyncWithWMS\Api\WMSSyncRequestHistoryRepositoryInterface;
use Mikimpe\SyncWithWMS\Model\DTO\WMSSyncRequestResult;
use Mikimpe\SyncWithWMS\Model\Request\WMSSyncRequest;
use Psr\Log\LoggerInterface;

class DoWMSSyncRequestAndSaveResult
{
    private WMSSyncRequest $WMSSyncRequest;
    private WMSSyncRequestHistoryInterfaceFactory $WMSSyncRequestHistoryFactory;
    private WMSSyncRequestHistoryRepositoryInterface $WMSSyncRequestHistoryRepository;
    private LoggerInterface $logger;
    private LogRequestIfEnabled $logRequestIfEnabled;

    /**
     * @param WMSSyncRequest $WMSSyncRequest
     * @param WMSSyncRequestHistoryInterfaceFactory $WMSSyncRequestHistoryFactory
     * @param WMSSyncRequestHistoryRepositoryInterface $WMSSyncRequestHistoryRepository
     * @param LoggerInterface $logger
     * @param LogRequestIfEnabled $logRequestIfEnabled
     */
    public function __construct(
        WMSSyncRequest $WMSSyncRequest,
        WMSSyncRequestHistoryInterfaceFactory $WMSSyncRequestHistoryFactory,
        WMSSyncRequestHistoryRepositoryInterface $WMSSyncRequestHistoryRepository,
        LoggerInterface $logger,
        LogRequestIfEnabled $logRequestIfEnabled
    ) {
        $this->WMSSyncRequest = $WMSSyncRequest;
        $this->WMSSyncRequestHistoryFactory = $WMSSyncRequestHistoryFactory;
        $this->WMSSyncRequestHistoryRepository = $WMSSyncRequestHistoryRepository;
        $this->logger = $logger;
        $this->logRequestIfEnabled = $logRequestIfEnabled;
    }

    /**
     * @param string $sku
     * @return WMSSyncRequestResult
     */
    public function execute(string $sku): WMSSyncRequestResult
    {
        $res = $this->WMSSyncRequest->execute($sku);

        $WMSSyncRequestHistoryEntity = $this->WMSSyncRequestHistoryFactory->create();
        $WMSSyncRequestHistoryEntity->setSku($sku);
        $WMSSyncRequestHistoryEntity->setStatusCode($res->getStatusCode());
        $WMSSyncRequestHistoryEntity->setQtyReceived($res->getQty());
        $WMSSyncRequestHistoryEntity->setErrorMsg($res->getErrorMsg());

        try {
            $this->WMSSyncRequestHistoryRepository->save($WMSSyncRequestHistoryEntity);
        } catch (CouldNotSaveException $e) {
            $this->logger->error($e);
        }

        $this->logRequestIfEnabled->execute($WMSSyncRequestHistoryEntity);

        return $res;
    }
}
