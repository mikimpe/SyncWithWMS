<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Model\DTO;

class WMSSyncRequestResult
{
    private bool $success;
    private int $statusCode;
    private ?int $qty = null;
    private ?String $errorMsg = null;

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return int|null
     */
    public function getQty(): ?int
    {
        return $this->qty;
    }

    /**
     * @param int|null $qty
     */
    public function setQty(?int $qty): void
    {
        $this->qty = $qty;
    }

    /**
     * @return String|null
     */
    public function getErrorMsg(): ?string
    {
        return $this->errorMsg;
    }

    /**
     * @param String|null $errorMsg
     */
    public function setErrorMsg(?string $errorMsg): void
    {
        $this->errorMsg = $errorMsg;
    }

//    /**
//     * @return array
//     */
//    public function getData(): array
//    {
//        return [
//            'success' => $this->success,
//            'status_code' => $this->statusCode,
//            'qty' => $this->qty,
//            'error_msg' => $this->errorMsg,
//        ];
//    }
}
