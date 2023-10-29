<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\ViewModel;

use Magento\Backend\Model\Auth\Session;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Mikimpe\SyncWithWMS\Model\Config;

class SyncActionViewModel implements ArgumentInterface
{
    private Config $config;
    private Session $session;

    /**
     * @param Config $config
     * @param Session $session
     */
    public function __construct(
        Config $config,
        Session $session
    ) {
        $this->config = $config;
        $this->session = $session;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->config->isEnabled();
    }

    /**
     * @return bool
     */
    public function isAllowed(): bool
    {
        return $this->session->isAllowed('Mikimpe_SyncWithWMS::sync');
    }
}
