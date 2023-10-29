<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Model\Request;

use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;

class ExtractErrorMsgFromWMSResponse
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
     * @return string
     */
    public function execute(string $responseBody): string
    {
        if (empty($responseBody)) {
            return 'Undefined error';
        }

        $res = $this->json->unserialize($responseBody);
        if (empty($res['error'])) {
            $this->logger->error(__('Unable to extract error message from WMS response body'));
            return 'Undefined error';
        }

        return $res['error'];
    }
}
