<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Model\Order;

use Hello\BankTransfer\Services\NotificationService;

/**
 * Class Status
 *
 * @package Hello\BankTransfer\Model
 */
class Cancel
{
    /**
     * @var NotificationService
     */
    private $notificationService;

    /**
     * Cancel constructor.
     *
     * @param NotificationService $notificationService
     */
    public function __construct(
        NotificationService $notificationService
    ) {
        $this->notificationService = $notificationService;
    }

    /**
     * Cancel order and set flag
     *
     * @param $order
     * @return void
     */
    public function cancelOrder($order)
    {
        $this->notificationService->setNotifiedFlag($order);
    }
}
