<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Controller\Adminhtml\History;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action implements HttpGetActionInterface
{
    private PageFactory $pageFactory;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute(): Page
    {
        /** @var Page $resultPage */
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Mikimpe_SyncWithWMS::sync_with_wms_request_history');
        $resultPage->getConfig()->getTitle()->prepend(__('WMS Sync Request History'));

        return $resultPage;
    }
}
