<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Controller\Adminhtml\Sync;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class Sync extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Mikimpe_SyncWithWMS::sync';
    private JsonFactory $jsonFactory;

    /**
     * @param JsonFactory $jsonFactory
     * @param Context $context
     */
    public function __construct(
        JsonFactory $jsonFactory,
        Context $context
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
    }

    public function execute()
    {
        $sku = $this->_request->getParam('sku');

        $result = $this->jsonFactory->create();
        $result->setData(['success' => true]);
        $result->setData(['qty' => 10]);

        return $result;
    }
}
