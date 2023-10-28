<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Model;

use Magento\Framework\Model\AbstractModel;
use Mikimpe\SyncWithWMS\Api\Data\WMSSyncRequestHistoryInterface;

class WMSSyncRequestHistory extends AbstractModel implements WMSSyncRequestHistoryInterface
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Mikimpe\SyncWithWMS\Model\ResourceModel\WMSSyncRequestHistory');
    }

    /**
     * @inheritDoc
     */
    public function getEntryId(): int
    {
        return $this->getData(self::ENTRY_ID);
    }

    /**
     * @inheritDoc
     */
    public function getSku(): string
    {
        return $this->getData(self::SKU);
    }

    /**
     * @inheritDoc
     */
    public function setSku(string $sku): WMSSyncRequestHistoryInterface
    {
        return $this->setData(self::SKU, $sku);

    }

    /**
     * @inheritDoc
     */
    public function getStatusCode(): int
    {
        return $this->getData(self::STATUS_CODE);
    }

    /**
     * @inheritDoc
     */
    public function setStatusCode(int $statusCode): WMSSyncRequestHistoryInterface
    {
        return $this->setData(self::STATUS_CODE, $statusCode);
    }

    /**
     * @inheritDoc
     */
    public function getQtyReceived(): ?int
    {
        return $this->getData(self::QTY_RECEIVED);
    }

    /**
     * @inheritDoc
     */
    public function setQtyReceived(int $qtyReceived): WMSSyncRequestHistoryInterface
    {
        return $this->setData(self::QTY_RECEIVED, $qtyReceived);
    }

    /**
     * @inheritDoc
     */
    public function getErrorMsg(): ?string
    {
        return $this->getData(self::ERROR_MSG);
    }

    /**
     * @inheritDoc
     */
    public function setErrorMsg(string $errorMsg): WMSSyncRequestHistoryInterface
    {
        return $this->setData(self::ERROR_MSG, $errorMsg);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(string $createdAt): WMSSyncRequestHistoryInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}
