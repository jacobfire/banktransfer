<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Notification
 *
 * @package Hello\BankTransfer\Model\ResourceModel
 */
class Notification extends AbstractDb
{
    /**
     * Define main table and primary key
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('hello_banktransfer_notification', 'row_id');
    }
}
