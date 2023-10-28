<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Controller\Adminhtml\Sync;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Json;
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

    /**
     * @return Json
     */
    public function execute(): Json
    {
        $sku = $this->_request->getParam('sku');

        $result = $this->jsonFactory->create();
        $result->setData(
            [
                'success' => true,
                'qty' => 100,
                'sku' => $sku
            ]
        );

        return $result;
    }
}
