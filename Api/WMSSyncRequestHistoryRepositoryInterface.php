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
     * @return void
     */
    public function delete(WMSSyncRequestHistoryInterface $WMSSyncRequestHistory): void;

    /**
     * @param int $entryId
     * @return void
     */
    public function deleteById(int $entryId): void;
}
