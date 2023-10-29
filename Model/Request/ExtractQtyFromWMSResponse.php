<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Model\Request;

use Exception;
use Magento\Framework\Serialize\Serializer\Json;
use Mikimpe\SyncWithWMS\Exception\UnexpectedWMSResponseBodyStructureException;
use Psr\Log\LoggerInterface;

class ExtractQtyFromWMSResponse
{
    private Json $json;
    private LoggerInterface $logger;

    /**
     * @param Json $json
     * @param LoggerInterface $logger
     */
    public function __construct(
        Json $json,
        LoggerInterface $logger
    ) {
        $this->json = $json;
        $this->logger = $logger;
    }

    /**
     * @param string $responseBody
     * @return int
     * @throws UnexpectedWMSResponseBodyStructureException
     */
    public function execute(string $responseBody): int
    {
        if (empty($responseBody)) {
            $msg = __('Unable to extract product quantity from WMS empty response body');
            $this->logger->error($msg);
            throw new UnexpectedWMSResponseBodyStructureException($msg);
        }

        try {
            $res = $this->json->unserialize($responseBody);
        } catch (Exception) {
            $msg = __('WMS response body is not a valid JSON');
            $this->logger->error($msg . ":\n$responseBody");
            throw new UnexpectedWMSResponseBodyStructureException($msg);
        }

        if (empty($res['qty'])) {
            $msg = __('Unable to extract product quantity from WMS response body');
            $this->logger->error($msg . ":\n$responseBody");
            throw new UnexpectedWMSResponseBodyStructureException($msg);
        }

        return $res['qty'];
    }
}
