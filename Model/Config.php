<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    private const XML_PATH_MIKIMPE_WMS_SYNC_GENERAL_ENABLED = 'mikimpe_wms_sync/general/enabled';
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
}
