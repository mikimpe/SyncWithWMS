<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Block\Adminhtml\Product\Edit;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Serialize\Serializer\Json;
use Mikimpe\SyncWithWMS\Model\Config;

class SyncWithWMSBlock extends Template
{
    private Json $json;
    private Config $config;

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
        array $data = [],
        ?JsonHelper $jsonHelper = null,
        ?DirectoryHelper $directoryHelper = null
    ) {
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
        $this->json = $json;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function getJsLayout()
    {
        $layout = $this->json->unserialize(parent::getJsLayout());

        $layout['components']['sync-with-wms-action']['isEnabled'] = $this->config->isEnabled();

        return $this->json->serialize($layout);
    }

    /**
     * @inheritdoc
     */
    public function toHtml()
    {
        if (!$this->config->isEnabled()) {
            return '';
        }

        return parent::toHtml();
    }
}
