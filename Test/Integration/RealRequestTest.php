<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Test\Integration;

use Magento\Framework\HTTP\ClientInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\TestFramework\Helper\Bootstrap;
use Mikimpe\SyncWithWMS\Model\Config;
use Mikimpe\SyncWithWMS\Model\Request\RandomError;
use Mikimpe\SyncWithWMS\Model\Request\RealRequest;
use PHPUnit\Framework\TestCase;

class RealRequestTest extends TestCase
{
    /**
     * @return void
     * @magentoConfigFixture default/mikimpe_wms_sync/general/enabled 1
     * @magentoConfigFixture default/mikimpe_wms_sync/general/test_mode 0
     * @magentoConfigFixture default/mikimpe_wms_sync/general/wms_endpoint test
     */
    public function testShouldReturnSuccessResult(): void
    {
        $clientMockCorrect = $this->buildSuccessClientMockWithCorrectBody();
        $randomErrorMockNotReturningError = $this->buildRandomErrorMock(false);
        $subject = Bootstrap::getObjectManager()->create(
            RealRequest::class,
            [
                'client' => $clientMockCorrect,
                'randomError' => $randomErrorMockNotReturningError
            ]
        );

        $res = $subject->execute('test');
        self::assertTrue($res->isSuccess());
        self::assertSame(200, $res->getStatusCode());
        self::assertSame(10, $res->getQty());
    }

    /**
     * @return void
     */
    public function testShouldReturnErrorResultIfWMSEndpointConfigIsNotSet(): void
    {
        $clientMockCorrect = $this->buildSuccessClientMockWithCorrectBody();
        $configMockWithEmptyWMSEndpointConfigured = $this->buildConfigMockWithEmptyWMSEndpointConfigured();
        $subject = Bootstrap::getObjectManager()->create(
            RealRequest::class,
            [
                'client' => $clientMockCorrect,
                'config' => $configMockWithEmptyWMSEndpointConfigured
            ]
        );

        $res = $subject->execute('test');

        self::assertFalse($res->isSuccess());
        self::assertSame(400, $res->getStatusCode());
        self::assertSame('WMS endpoint not configured', $res->getErrorMsg());
    }

    /**
     * @return void
     * @magentoConfigFixture default/mikimpe_wms_sync/general/enabled 1
     * @magentoConfigFixture default/mikimpe_wms_sync/general/test_mode 0
     * @magentoConfigFixture default/mikimpe_wms_sync/general/wms_endpoint test
     */
    public function testShouldReturnErrorResultIfForced(): void
    {
        $clientMockWithCorrectBody = $this->buildErrorClientMockWithCorrectBody();
        $randomErrorMockReturningError = $this->buildRandomErrorMock(true);
        $subject = Bootstrap::getObjectManager()->create(
            RealRequest::class,
            [
                'client' => $clientMockWithCorrectBody,
                'randomError' => $randomErrorMockReturningError
            ]
        );

        $res = $subject->execute('test');

        self::assertFalse($res->isSuccess());
        self::assertSame(400, $res->getStatusCode());
        self::assertSame('Test error', $res->getErrorMsg());
    }

    /**
     * @return void
     * @magentoConfigFixture default/mikimpe_wms_sync/general/enabled 1
     * @magentoConfigFixture default/mikimpe_wms_sync/general/test_mode 0
     * @magentoConfigFixture default/mikimpe_wms_sync/general/wms_endpoint test
     */
    public function testShouldReturnErrorResultIfWMSResponseBodyIsEmpty(): void
    {
        $clientMockWithEmptyBody = $this->buildSuccessClientMockWithEmptyBody();
        $randomErrorMockNotReturningError = $this->buildRandomErrorMock(false);
        $subject = Bootstrap::getObjectManager()->create(
            RealRequest::class,
            [
                'client' => $clientMockWithEmptyBody,
                'randomError' => $randomErrorMockNotReturningError
            ]
        );

        $res = $subject->execute('test');

        self::assertFalse($res->isSuccess());
        self::assertSame(200, $res->getStatusCode());
        self::assertSame('Unable to extract product quantity from WMS empty response body', $res->getErrorMsg());
    }

    /**
     * @return void
     * @magentoConfigFixture default/mikimpe_wms_sync/general/enabled 1
     * @magentoConfigFixture default/mikimpe_wms_sync/general/test_mode 0
     * @magentoConfigFixture default/mikimpe_wms_sync/general/wms_endpoint test
     */
    public function testShouldReturnErrorResultIfWMSResponseBodyIsMalformed(): void
    {
        $clientMockWithMalformed = $this->buildSuccessClientMockWithMalformedBody();
        $randomErrorMockNotReturningError = $this->buildRandomErrorMock(false);
        $subject = Bootstrap::getObjectManager()->create(
            RealRequest::class,
            [
                'client' => $clientMockWithMalformed,
                'randomError' => $randomErrorMockNotReturningError
            ]
        );

        $res = $subject->execute('test');

        self::assertFalse($res->isSuccess());
        self::assertSame(200, $res->getStatusCode());
        self::assertSame('WMS response body is not a valid JSON', $res->getErrorMsg());
    }

    /**
     * @return void
     * @magentoConfigFixture default/mikimpe_wms_sync/general/enabled 1
     * @magentoConfigFixture default/mikimpe_wms_sync/general/test_mode 0
     * @magentoConfigFixture default/mikimpe_wms_sync/general/wms_endpoint test
     */
    public function testShouldReturnErrorResultIfWMSResponseBodyStructureIsNotAsExpected(): void
    {
        $clientMockWithUnexpectedResponseBodyStructure = $this->buildSuccessClientMockWithUnexpectedResponseBodyStructure();
        $randomErrorMockNotReturningError = $this->buildRandomErrorMock(false);
        $subject = Bootstrap::getObjectManager()->create(
            RealRequest::class,
            [
                'client' => $clientMockWithUnexpectedResponseBodyStructure,
                'randomError' => $randomErrorMockNotReturningError
            ]
        );

        $res = $subject->execute('test');

        self::assertFalse($res->isSuccess());
        self::assertSame(200, $res->getStatusCode());
        self::assertSame('Unable to extract product quantity from WMS response body', $res->getErrorMsg());
    }

    /**
     * @return ClientInterface
     */
    private function buildSuccessClientMockWithCorrectBody(): ClientInterface
    {
        $json = Bootstrap::getObjectManager()->get(Json::class);
        $clientMock = $this->getMockBuilder(ClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $clientMock->method('get')->willReturn([]);
        $clientMock->method('getStatus')->willReturn(200);
        $clientMock->method('getBody')->willReturn($json->serialize(['qty' => 10]));

        return $clientMock;
    }

    /**
     * @return ClientInterface
     */
    private function buildErrorClientMockWithCorrectBody(): ClientInterface
    {
        $json = Bootstrap::getObjectManager()->get(Json::class);
        $clientMock = $this->getMockBuilder(ClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $clientMock->method('get')->willReturn([]);
        $clientMock->method('getStatus')->willReturn(400);
        $clientMock->method('getBody')->willReturn($json->serialize(['error' => 'Test error']));

        return $clientMock;
    }

    /**
     * @return ClientInterface
     */
    private function buildSuccessClientMockWithEmptyBody(): ClientInterface
    {
        $clientMock = $this->getMockBuilder(ClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $clientMock->method('get')->willReturn([]);
        $clientMock->method('getStatus')->willReturn(200);
        $clientMock->method('getBody')->willReturn('');

        return $clientMock;
    }

    /**
     * @return ClientInterface
     */
    private function buildSuccessClientMockWithMalformedBody(): ClientInterface
    {
        $clientMock = $this->getMockBuilder(ClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $clientMock->method('get')->willReturn([]);
        $clientMock->method('getStatus')->willReturn(200);
        $clientMock->method('getBody')->willReturn('<');

        return $clientMock;
    }

    /**
     * @return ClientInterface
     */
    private function buildSuccessClientMockWithUnexpectedResponseBodyStructure(): ClientInterface
    {
        $json = Bootstrap::getObjectManager()->get(Json::class);
        $clientMock = $this->getMockBuilder(ClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $clientMock->method('get')->willReturn([]);
        $clientMock->method('getStatus')->willReturn(200);
        $clientMock->method('getBody')->willReturn($json->serialize(['quantity' => 10]));

        return $clientMock;
    }

    /**
     * @return Config
     */
    private function buildConfigMockWithEmptyWMSEndpointConfigured(): Config
    {
        $configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $configMock->method('isEnabled')->willReturn(true);
        $configMock->method('isTestMode')->willReturn(false);
        $configMock->method('getWMSEndpoint')->willReturn("");

        return $configMock;
    }

    /**
     * @param bool $shouldReturnError
     * @return RandomError
     */
    private function buildRandomErrorMock(bool $shouldReturnError): RandomError
    {
        $randomErrorMock = $this->getMockBuilder(RandomError::class)
            ->disableOriginalConstructor()
            ->getMock();
        $randomErrorMock->method('execute')->willReturn($shouldReturnError);

        return $randomErrorMock;
    }
}
