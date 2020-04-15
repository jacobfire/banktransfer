<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Cron;

use Hello\BankTransfer\Model\Order\Cancel;
use Hello\BankTransfer\Model\ResourceModel\BankTransferOrders;
use Magento\Sales\Model\Order;

/**
 * Class CancelExpired
 *
 * @package Hello\BankTransfer\Cron
 */
class CancelExpired
{
    /**
     * @var Cancel
     */
    private $orderCancel;

    /**
     * @var BankTransferOrders
     */
    private $bankTransferOrders;

    /**
     * CancelExpired constructor.
     *
     * @param Cancel $cancel
     * @param BankTransferOrders $bankTransferOrders
     */
    public function __construct(
        Cancel $cancel,
        BankTransferOrders $bankTransferOrders
    ) {
        $this->orderCancel = $cancel;
        $this->bankTransferOrders = $bankTransferOrders;
    }

    /**
     * Check not paid orders
     *
     * @return void
     */
    public function execute()
    {
        $this->cancelOrder($this->bankTransferOrders->getBankTransferOrders(BankTransferOrders::STATUS_EXPIRED));
    }

    /**
     * Cancel order if it has not been paid
     *
     * @param \Magento\Sales\Model\ResourceModel\Order\Collection $collection
     * @return void
     */
    public function cancelOrder($collection)
    {
        if (null === $collection) {
            return;
        }

        foreach ($collection as $order) {
            if ($order->getState() === Order::STATE_NEW) {
                $this->orderCancel->cancelOrder($order);
            }
        }
    }
}
