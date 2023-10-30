<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Controller\Adminhtml\Sync;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Mikimpe\SyncWithWMS\Model\DoWMSSyncRequestAndSaveResult;

class Sync extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Mikimpe_SyncWithWMS::sync';
    private JsonFactory $jsonFactory;
    private DoWMSSyncRequestAndSaveResult $doWMSSyncRequestAndSaveResult;
    private Session $session;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param DoWMSSyncRequestAndSaveResult $doWMSSyncRequestAndSaveResult
     * @param Session $session
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        DoWMSSyncRequestAndSaveResult $doWMSSyncRequestAndSaveResult,
        Session $session
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->doWMSSyncRequestAndSaveResult = $doWMSSyncRequestAndSaveResult;
        $this->session = $session;
    }

    /**
     * @return Json
     */
    public function execute(): Json
    {
        if (!$this->session->isAllowed('Mikimpe_SyncWithWMS::sync')) {
            $jsonResult = $this->jsonFactory->create();
            $jsonResult->setData(
                [
                    'success' => false,
                    'qty' => null,
                    'error_msg' => __('Current user is not allowed to perform this action')->getText()
                ]
            );

            return $jsonResult;
        }

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
