<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Model\Request;

use Mikimpe\SyncWithWMS\Model\Config;
use Mikimpe\SyncWithWMS\Model\DTO\WMSSyncRequestResult;

class WMSSyncRequest
{
    private Config $config;
    private FakeRequest $fakeRequest;
    private RealRequest $realRequest;

    /**
     * @param Config $config
     * @param FakeRequest $fakeRequest
     * @param RealRequest $realRequest
     */
    public function __construct(
        Config $config,
        FakeRequest $fakeRequest,
        RealRequest $realRequest
    ) {
        $this->config = $config;
        $this->fakeRequest = $fakeRequest;
        $this->realRequest = $realRequest;
    }

    /**
     * @param string $sku
     * @return WMSSyncRequestResult
     */
    public function execute(string $sku): WMSSyncRequestResult
    {
        if ($this->config->isTestMode()) {
            return $this->fakeRequest->execute($sku);
        }

        return $this->realRequest->execute($sku);
    }
}
