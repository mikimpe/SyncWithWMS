<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Mikimpe\SyncWithWMS\Model\Config;

class SyncActionViewModel implements ArgumentInterface
{
    private Config $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->config->isEnabled();
    }
}
