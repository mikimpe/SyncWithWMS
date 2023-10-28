<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface WMSSyncRequestHistorySearchResultInterface extends SearchResultsInterface
{
    /**
     * @return WMSSyncRequestHistoryInterface[]
     */
    public function getItems();

    /**
     * @param array $items
     * @return WMSSyncRequestHistorySearchResultInterface
     */
    public function setItems(array $items);
}
