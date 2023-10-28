<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class WMSSyncRequestHistory extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('wms_sync_request_history', 'entry_id');
    }
}
