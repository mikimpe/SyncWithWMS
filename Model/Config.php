<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    private const XML_PATH_MIKIMPE_WMS_SYNC_GENERAL_ENABLED = 'mikimpe_wms_sync/general/enabled';
    private const XML_PATH_MIKIMPE_WMS_SYNC_GENERAL_TEST_MODE = 'mikimpe_wms_sync/general/test_mode';
    private const XML_PATH_MIKIMPE_WMS_SYNC_GENERAL_WMS_ENDPOINT = 'mikimpe_wms_sync/general/wms_endpoint';
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_MIKIMPE_WMS_SYNC_GENERAL_ENABLED);
    }

    /**
     * @return bool
     */
    public function isTestMode(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_MIKIMPE_WMS_SYNC_GENERAL_TEST_MODE);
    }

    /**
     * @return string
     */
    public function getWMSEndpoint(): string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_MIKIMPE_WMS_SYNC_GENERAL_WMS_ENDPOINT) ?? '';
    }
}
