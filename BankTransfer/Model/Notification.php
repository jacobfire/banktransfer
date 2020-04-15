<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Notification
 *
 * @package Hello\BankTransfer\Model
 */
class Notification extends AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Hello\BankTransfer\Model\ResourceModel\Notification');
    }
}
