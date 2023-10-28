<?php
declare(strict_types=1);

namespace Mikimpe\SyncWithWMS\Model\ResourceModel\WMSSyncRequestHistory;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init(
            'Mikimpe\SyncWithWMS\Model\WMSSyncRequestHistory',
            'Mikimpe\SyncWithWMS\Model\ResourceModel\WMSSyncRequestHistory'
        );
    }
}
