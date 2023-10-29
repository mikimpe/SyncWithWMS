<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Model\Request;

use Magento\Framework\HTTP\ClientInterface;
use Mikimpe\SyncWithWMS\Exception\UnexpectedWMSResponseBodyStructureException;
use Mikimpe\SyncWithWMS\Model\Config;
use Mikimpe\SyncWithWMS\Model\DTO\WMSSyncRequestResult;
use Mikimpe\SyncWithWMS\Model\DTO\WMSSyncRequestResultFactory;

class RealRequest
{
    private Config $config;
    private ClientInterface $client;
    private WMSSyncRequestResultFactory $WMSSyncRequestResultFactory;
    private ExtractErrorMsgFromWMSResponse $extractErrorMsgFromWMSResponse;
    private ExtractQtyFromWMSResponse $extractQtyFromWMSResponse;

    /**
     * @param Config $config
     * @param ClientInterface $client
     * @param WMSSyncRequestResultFactory $WMSSyncRequestResultFactory
     * @param ExtractErrorMsgFromWMSResponse $extractErrorMsgFromWMSResponse
     * @param ExtractQtyFromWMSResponse $extractQtyFromWMSResponse
     */
    public function __construct(
        Config $config,
        ClientInterface $client,
        WMSSyncRequestResultFactory $WMSSyncRequestResultFactory,
        ExtractErrorMsgFromWMSResponse $extractErrorMsgFromWMSResponse,
        ExtractQtyFromWMSResponse $extractQtyFromWMSResponse
    ) {
        $this->config = $config;
        $this->client = $client;
        $this->WMSSyncRequestResultFactory = $WMSSyncRequestResultFactory;
        $this->extractErrorMsgFromWMSResponse = $extractErrorMsgFromWMSResponse;
        $this->extractQtyFromWMSResponse = $extractQtyFromWMSResponse;
    }

    /**
     * @param string $sku
     * @return WMSSyncRequestResult
     */
    public function execute(string $sku): WMSSyncRequestResult
    {
        if (!$this->config->getWMSEndpoint()) {
            return $this->buildErrorResponse(400, 'WMS endpoint not configured');
        }

        $this->client->get($this->config->getWMSEndpoint() . $sku);
        if ($this->client->getStatus() !== 200) {
            return $this->buildErrorResponse(
                $this->client->getStatus(),
                $this->extractErrorMsgFromWMSResponse->execute($this->client->getBody())
            );
        }

        try {
            return $this->buildSuccessResponse($this->client->getBody());
        } catch (UnexpectedWMSResponseBodyStructureException $e) {
            return $this->buildErrorResponse(400, $e->getMessage());
        }
    }

    /**
     * @param string $responseBody
     * @return WMSSyncRequestResult
     * @throws UnexpectedWMSResponseBodyStructureException
     */
    private function buildSuccessResponse(string $responseBody): WMSSyncRequestResult
    {
        $qty = $this->extractQtyFromWMSResponse->execute($responseBody);

        $res = $this->WMSSyncRequestResultFactory->create();
        $res->setSuccess(true);
        $res->setStatusCode(200);
        $res->setQty($qty);

        return $res;
    }

    /**
     * @param int $statusCode
     * @param string $errorMsg
     * @return WMSSyncRequestResult
     */
    private function buildErrorResponse(int $statusCode, string $errorMsg): WMSSyncRequestResult
    {
        $res = $this->WMSSyncRequestResultFactory->create();
        $res->setSuccess(false);
        $res->setStatusCode($statusCode);
        $res->setErrorMsg($errorMsg);

        return $res;
    }
}
