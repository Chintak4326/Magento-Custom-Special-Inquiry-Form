<?php
namespace ChintakExtensions\Special\Model\ResourceModel\Special;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init('ChintakExtensions\Special\Model\Special', 'ChintakExtensions\Special\Model\ResourceModel\Special');
    }
}