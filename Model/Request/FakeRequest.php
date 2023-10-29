<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Model\Request;

use Mikimpe\SyncWithWMS\Model\DTO\WMSSyncRequestResult;
use Mikimpe\SyncWithWMS\Model\DTO\WMSSyncRequestResultFactory;
use Mikimpe\WMS\Api\GetProductQtyInterface;
use Mikimpe\WMS\Exception\FakeException;

class FakeRequest
{
    private GetProductQtyInterface $getProductQty;
    private WMSSyncRequestResultFactory $WMSSyncRequestResultFactory;

    /**
     * @param GetProductQtyInterface $getProductQty
     * @param WMSSyncRequestResultFactory $WMSSyncRequestResultFactory
     */
    public function __construct(
        GetProductQtyInterface $getProductQty,
        WMSSyncRequestResultFactory $WMSSyncRequestResultFactory
    ) {
        $this->getProductQty = $getProductQty;
        $this->WMSSyncRequestResultFactory = $WMSSyncRequestResultFactory;
    }

    /**
     * @param string $sku
     * @return WMSSyncRequestResult
     */
    public function execute(string $sku): WMSSyncRequestResult
    {
        $res = $this->WMSSyncRequestResultFactory->create();
        try {
            $qty = $this->getProductQty->execute($sku);
            $res->setSuccess(true);
            $res->setStatusCode(200);
            $res->setQty($qty);
        } catch (FakeException $e) {
            $res->setSuccess(false);
            $res->setStatusCode(400);
            $res->setErrorMsg($e->getMessage());
        }

        return $res;
    }
}
