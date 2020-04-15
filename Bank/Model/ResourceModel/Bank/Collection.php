<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\Bank\Model\ResourceModel\Bank;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * @package Hello\Bank\Model\ResourceModel\Bank
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'row_id';

    /**
     * Property of active bank for a store
     */
    const IS_ACTIVE = 1;

    /**
     * Init collection
     */
    public function _construct()
    {
        $this->_init('Hello\Bank\Model\Bank', 'Hello\Bank\Model\ResourceModel\Bank');
    }

    /**
     * Retrieve available items by store Ids
     *
     * @param int $storeId
     * @return $this
     */
    private function getAvailableBankRequisites($storeId)
    {
        return $this->addFieldToFilter('store_id', ['eq' => $storeId])
            ->addFieldToFilter('is_active', ['eq' => self::IS_ACTIVE]);
    }

    /**
     * Retrieve requisites of active banks
     *
     * @param int $storeId
     * @return array
     */
    public function collectRequisites($storeId)
    {
        $items = $this->getAvailableBankRequisites($storeId);

        $data = [];
        $counter = 0;
        foreach ($items as $item) {
            $data[$counter]['account_number'] = $item->getData('hello_account_number');
            $data[$counter]['account_name'] = $item->getData('hello_account_name');
            $data[$counter]['payee_name'] = $item->getData('payee_name');
            $data[$counter]['bank_name'] = $item->getData('bank_name');
            $counter++;
        }

        return $data;
    }
}
