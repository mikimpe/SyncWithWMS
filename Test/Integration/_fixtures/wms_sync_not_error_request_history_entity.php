<?php
declare(strict_types=1);

use Magento\TestFramework\Helper\Bootstrap;
use Mikimpe\SyncWithWMS\Api\Data\WMSSyncRequestHistoryInterface;
use Mikimpe\SyncWithWMS\Api\WMSSyncRequestHistoryRepositoryInterface;

$repository = Bootstrap::getObjectManager()->get(WMSSyncRequestHistoryRepositoryInterface::class);
$entity = Bootstrap::getObjectManager()->create(WMSSyncRequestHistoryInterface::class);

$entity->setSku('test-fixture');
$entity->setStatusCode(200);
$entity->setQtyReceived(10);

$repository->save($entity);
