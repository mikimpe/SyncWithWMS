<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;
use Mikimpe\SyncWithWMS\Api\Data\WMSSyncRequestHistoryInterface;

class WMSSyncRequestHistory extends AbstractModel implements WMSSyncRequestHistoryInterface
{
    private DateTime $dateTime;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param DateTime $dateTime
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DateTime $dateTime,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->dateTime = $dateTime;
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Mikimpe\SyncWithWMS\Model\ResourceModel\WMSSyncRequestHistory');
    }

    /**
     * @inheritDoc
     */
    public function beforeSave()
    {
        parent::beforeSave();
        if ($this->isObjectNew() && !$this->getCreatedAt()) {
            $this->setCreatedAt($this->dateTime->formatDate(true));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getEntryId(): int
    {
        return (int) $this->getData(self::ENTRY_ID);
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
        return (int) $this->getData(self::STATUS_CODE);
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
        $qtyReceived = $this->getData(self::QTY_RECEIVED);
        if (is_string($qtyReceived)) {
            $qtyReceived = (int) $qtyReceived;
        }

        return $qtyReceived;
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
