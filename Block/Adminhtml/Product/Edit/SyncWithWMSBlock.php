<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Block\Adminhtml\Product\Edit;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Serialize\Serializer\Json;
use Mikimpe\SyncWithWMS\Model\Config;

class SyncWithWMSBlock extends Template
{
    private Json $json;
    private Config $config;
    private Session $session;

    /**
     * @param Context $context
     * @param Json $json
     * @param Config $config
     * @param array $data
     * @param JsonHelper|null $jsonHelper
     * @param DirectoryHelper|null $directoryHelper
     */
    public function __construct(
        Template\Context $context,
        Json $json,
        Config $config,
        Session $session,
        array $data = [],
        ?JsonHelper $jsonHelper = null,
        ?DirectoryHelper $directoryHelper = null
    ) {
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
        $this->json = $json;
        $this->config = $config;
        $this->session = $session;
    }

    /**
     * @inheritDoc
     */
    public function getJsLayout(): string
    {
        $layout = $this->json->unserialize(parent::getJsLayout());

        $layout['components']['sync-with-wms-action']['isEnabled'] = $this->config->isEnabled();
        $layout['components']['sync-with-wms-action']['isAllowed'] =
            $this->session->isAllowed('Mikimpe_SyncWithWMS::sync');

        return $this->json->serialize($layout);
    }

    /**
     * @inheritdoc
     */
    public function toHtml(): string
    {
        if (!$this->config->isEnabled()) {
            return '';
        }

        return parent::toHtml();
    }
}
