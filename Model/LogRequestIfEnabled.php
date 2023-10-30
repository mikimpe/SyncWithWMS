<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Model;

use Magento\Backend\Model\Auth\Session;
use Mikimpe\SyncWithWMS\Api\Data\WMSSyncRequestHistoryInterface;
use Psr\Log\LoggerInterface;

class LogRequestIfEnabled
{
    private Config $config;
    private LoggerInterface $logger;
    private Session $session;

    /**
     * @param Config $config
     * @param LoggerInterface $logger
     * @param Session $session
     */
    public function __construct(
        Config $config,
        LoggerInterface $logger,
        Session $session
    ) {
        $this->config = $config;
        $this->logger = $logger;
        $this->session = $session;
    }

    /**
     * @param WMSSyncRequestHistoryInterface $request
     * @return void
     */
    public function execute(WMSSyncRequestHistoryInterface $request): void
    {
        if ($this->config->isRequestLoggerEnabled()) {
            $userEmail = $this->session->getUser() ? $this->session->getUser()->getEmail() : '';
            $this->logger->info(
                __(
                    "Request performed by %1.\nentry_id: %2\nsku: %3\nstatus_code: %4\nqty_received: %5\nerror_msg: %6\ncreated_at: %7",
                    $userEmail,
                    $request->getEntryId(),
                    $request->getSku(),
                    $request->getStatusCode(),
                    $request->getQtyReceived(),
                    $request->getErrorMsg(),
                    $request->getCreatedAt()
                )
            );
        }
    }
}
