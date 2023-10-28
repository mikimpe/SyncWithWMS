<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Mikimpe\SyncWithWMS\Api\Data\WMSSyncRequestHistoryInterface;
use Mikimpe\SyncWithWMS\Api\Data\WMSSyncRequestHistorySearchResultInterface;

interface WMSSyncRequestHistoryRepositoryInterface
{
    /**
     * @param WMSSyncRequestHistoryInterface $WMSSyncRequestHistory
     * @return WMSSyncRequestHistoryInterface
     */
    public function save(WMSSyncRequestHistoryInterface $WMSSyncRequestHistory): WMSSyncRequestHistoryInterface;

    /**
     * @param int $entryId
     * @return WMSSyncRequestHistoryInterface
     */
    public function get(int $entryId): WMSSyncRequestHistoryInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return WMSSyncRequestHistorySearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): WMSSyncRequestHistorySearchResultInterface;

    /**
     * @param WMSSyncRequestHistoryInterface $WMSSyncRequestHistory
     * @return bool
     */
    public function delete(WMSSyncRequestHistoryInterface $WMSSyncRequestHistory): bool;

    /**
     * @param int $entryId
     * @return bool
     */
    public function deleteById(int $entryId): bool;
}
