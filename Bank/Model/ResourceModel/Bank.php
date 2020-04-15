<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\Bank\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Bank
 *
 * @package Hello\Bank\Model\ResourceModel
 */
class Bank extends AbstractDb
{
    /**
     * Define main table and primary key
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('hello_bank_requisites', 'row_id');
    }
}
