<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Controller\Adminhtml\Sync;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Mikimpe\SyncWithWMS\Model\DoWMSSyncRequestAndSaveResult;

class Sync extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Mikimpe_SyncWithWMS::sync';
    private JsonFactory $jsonFactory;
    private DoWMSSyncRequestAndSaveResult $doWMSSyncRequestAndSaveResult;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param DoWMSSyncRequestAndSaveResult $doWMSSyncRequestAndSaveResult
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        DoWMSSyncRequestAndSaveResult $doWMSSyncRequestAndSaveResult
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->doWMSSyncRequestAndSaveResult = $doWMSSyncRequestAndSaveResult;
    }

    /**
     * @return Json
     */
    public function execute(): Json
    {
        $sku = $this->_request->getParam('sku');

        $requestResult = $this->doWMSSyncRequestAndSaveResult->execute($sku);

        $jsonResult = $this->jsonFactory->create();
        $jsonResult->setData(
            [
                'success' => $requestResult->isSuccess(),
                'qty' => $requestResult->getQty(),
                'error_msg' => $requestResult->getErrorMsg()
            ]
        );

        return $jsonResult;
    }
}
