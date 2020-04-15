<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Plugin\Block\Adminhtml\Order\View;

use Magento\Framework\Registry;
use Hello\BankTransfer\Services\ConfigurationService;

/**
 * Class TransactionsPlugin
 *
 * @package Hello\BankTransfer\Plugin\Block\Adminhtml\Order\View
 */
class TransactionsPlugin
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * TransactionsPlugin constructor.
     *
     * @param Registry $registry
     */
    public function __construct(
        Registry $registry
    ) {
        $this->registry = $registry;
    }

    /**
     * Allow to show transaction tab for bank transfer
     *
     * @param int|bool $result
     * @return bool
     */
    public function afterCanShowTab($result)
    {
        $order = $this->registry->registry('sales_order');
        if ($order->getPayment()->getMethod() === ConfigurationService::METHOD_CODE) {
            return true;
        }

        return $result;
    }
}
