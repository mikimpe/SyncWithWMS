<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Block\Adminhtml\Product\Edit\Button;

use Magento\Backend\Model\Auth\Session;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic;
use Magento\Framework\Escaper;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Context;
use Mikimpe\SyncWithWMS\Model\Config;

class SyncWithWMSButton extends Generic
{
    private Config $config;
    private Escaper $escaper;
    private Session $session;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param Config $config
     * @param Escaper $escaper
     * @param Session $session
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Config $config,
        Escaper $escaper,
        Session $session
    ) {
        parent::__construct($context, $registry);
        $this->config = $config;
        $this->escaper = $escaper;
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData(): array
    {
        $currentProduct = $this->getProduct();
        if ($currentProduct && $this->shouldShowButton($currentProduct)) {
            return [
                'label' => __('Sync with WMS'),
                'class' => 'action-secondary',
                'on_click' => $this->getOnClickAction($currentProduct->getSku()),
                'sort_order' => 50
            ];
        }

        return [];
    }

    /**
     * @param ProductInterface $product
     * @return bool
     */
    private function shouldShowButton(ProductInterface $product): bool
    {
        return $product->getId() &&
            $this->config->isEnabled() &&
            $this->session->isAllowed('Mikimpe_SyncWithWMS::sync');
    }

    /**
     * @param string $sku
     * @return string
     */
    private function getOnClickAction(string $sku): string
    {
        return 'window.syncAction("'
            . $this->escaper->escapeHtml($this->escaper->escapeJs($this->getSyncActionUrl($sku)))
            . '")';
    }

    /**
     * @param string $sku
     * @return string
     */
    private function getSyncActionUrl(string $sku): string
    {
        return $this->getUrl('wms_sync/sync/sync', ['sku' => $sku]);
    }
}
