<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Cron;

use Hello\BankTransfer\Services\NotificationService;
use Hello\BankTransfer\Model\ResourceModel\BankTransferOrders;
use Magento\Sales\Model\Order;

/**
 * Class StatusCheck
 *
 * @package Hello\BankTransfer\Cron
 */
class StatusCheck
{
    /**
     * @var NotificationService
     */
    private $notification;

    /**
     * @var BankTransferOrders
     */
    private $bankTransferOrders;

    /**
     * StatusCheck constructor.
     *
     * @param NotificationService $notificationService
     * @param BankTransferOrders $bankTransferOrders
     */
    public function __construct(
        NotificationService $notificationService,
        BankTransferOrders $bankTransferOrders
    ) {
        $this->notification = $notificationService;
        $this->bankTransferOrders = $bankTransferOrders;
    }

    /**
     * Check not paid orders for last 3 days
     *
     * @return void
     */
    public function execute()
    {
        $this->sendReminder($this->bankTransferOrders->getBankTransferOrders(BankTransferOrders::STATUS_NOT_EXPIRED));
    }

    /**
     * Inform buyer if he should pay for order
     *
     * @param \Magento\Sales\Model\ResourceModel\Order\Collection $collection
     * @return void
     */
    private function sendReminder($collection)
    {
        if (null === $collection) {
            return;
        }

        foreach ($collection->getItems() as $item) {
            if ($item->getState() === Order::STATE_NEW) {
                $this->notification->sendReminderNotification($item);
            }
        }
    }
}
