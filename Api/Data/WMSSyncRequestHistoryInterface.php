<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Api\Data;

interface WMSSyncRequestHistoryInterface
{
    public const ENTRY_ID = 'entry_id';
    public const SKU = 'sku';
    public const STATUS_CODE = 'status_code';
    public const QTY_RECEIVED = 'qty_received';
    public const ERROR_MSG = 'error_msg';
    public const CREATED_AT = 'created_at';

    /**
     * @return int
     */
    public function getEntryId(): int;

    /**
     * @return string
     */
    public function getSku(): string;

    /**
     * @param string $sku
     * @return WMSSyncRequestHistoryInterface
     */
    public function setSku(string $sku): WMSSyncRequestHistoryInterface;

    /**
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * @param int $statusCode
     * @return WMSSyncRequestHistoryInterface
     */
    public function setStatusCode(int $statusCode): WMSSyncRequestHistoryInterface;

    /**
     * @return int|null
     */
    public function getQtyReceived(): ?int;

    /**
     * @param int|null $qtyReceived
     * @return WMSSyncRequestHistoryInterface
     */
    public function setQtyReceived(?int $qtyReceived): WMSSyncRequestHistoryInterface;

    /**
     * @return string|null
     */
    public function getErrorMsg(): ?string;

    /**
     * @param string|null $errorMsg
     * @return WMSSyncRequestHistoryInterface
     */
    public function setErrorMsg(?string $errorMsg): WMSSyncRequestHistoryInterface;

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * @param string $createdAt
     * @return WMSSyncRequestHistoryInterface
     */
    public function setCreatedAt(string $createdAt): WMSSyncRequestHistoryInterface;
}
