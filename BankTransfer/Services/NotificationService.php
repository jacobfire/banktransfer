<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Services;

use Hello\BankTransfer\Model\Mailer;
use Hello\BankTransfer\Model\ResourceModel\Notification as NotificationResource;
use Hello\BankTransfer\Model\Notification;
use Hello\BankTransfer\Services\ConfigurationService;

/**
 * Class NotificationService
 *
 * @package Hello\BankTransfer\Services
 */
class NotificationService
{
    /**
     * @var NotificationResource
     */
    private $notificationResource;

    /**
     * @var \Hello\BankTransfer\Services\ConfigurationService
     */
    private $configurationService;

    /**
     * @var \Hello\BankTransfer\Model\Notification
     */
    private $notification;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * NotificationService constructor.
     *
     * @param NotificationResource $notificationResource
     * @param Notification $notification
     * @param Mailer $mailer
     * @param \Hello\BankTransfer\Services\ConfigurationService $configurationService
     */
    public function __construct(
        NotificationResource $notificationResource,
        Notification $notification,
        Mailer $mailer,
        ConfigurationService $configurationService
    ) {
        $this->notification = $notification;
        $this->notificationResource = $notificationResource;
        $this->mailer = $mailer;
        $this->configurationService = $configurationService;
    }

    /**
     * Set notification flag
     *
     * @param $order
     * @return void
     */
    public function setNotifiedFlag($order)
    {
        if (!$order->getId()) {
            return;
        }
        $this->notification->setOrderId($order->getId())
            ->setEmailReminderSent(ConfigurationService::PAYMENT_NOTIFICATION_SENT);
        $this->notificationResource->save($this->notification);
    }

    /**
     * Send a reminder email notification
     *
     * @param \Magento\Sales\Model\Order $order
     * @return void
     */
    public function sendReminderNotification($order)
    {
        $senderInfo = [
            'name' => $this->configurationService->getStoreRepresentiveName(),
            'email' => $this->configurationService->getStoreRepresentiveEmail()
        ];
        $recieverInfo = [
            'name' => sprintf('%s %s', $order->getCustomerFirstname(), $order->getCustomerLastname()),
            'email' => $order->getCustomerEmail()
        ];
        $this->mailer->sendMail(
            $senderInfo,
            $recieverInfo,
            $this->configurationService->getReminderEmailTemplate(),
            null
        );
        $this->setNotifiedFlag($order);
    }

    /**
     * Send a mail with attachment
     *
     * @param string $attachment
     * @return void
     */
    public function sendReport($attachment)
    {
        $recievers = $this->configurationService->getReportRecipient();
        if (!is_array($recievers)) {
            return;
        }
        $senderInfo = [
            'name' => $this->configurationService->getStoreRepresentiveName(),
            'email' => $this->configurationService->getStoreRepresentiveEmail()
        ];
        foreach ($recievers as $recieverEmail) {
            $recieverInfo = [
                'name' => null,
                'email' => $recieverEmail
            ];
            $this->mailer->sendMail(
                $senderInfo,
                $recieverInfo,
                $this->configurationService->getReportTemplate(),
                $attachment
            );
        }
    }
}
