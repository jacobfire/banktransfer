<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */
namespace Hello\BankTransfer\Plugin\Order;

use Magento\Sales\Api\OrderManagementInterface;
use Hello\BankTransfer\Model\ResourceModel\Notification as NotificationResource;
use Hello\BankTransfer\Model\Notification;
use Magento\Sales\Api\OrderPaymentRepositoryInterface;
use Hello\Bank\Model\BankManagement;
use Psr\Log\LoggerInterface as PsrLoggerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Class PlaceAfterPlugin
 *
 * Setting a flag for notifying a customer if the order was not paid
 * Saving data of bank requisites to additional info
 *
 * @package Hello\BankTransfer\Plugin\Order
 */
class PlaceAfterPlugin
{
    /**
     * @var \Hello\BankTransfer\Model\Notification
     */
    private $notification;

    /**
     * @var NotificationResource
     */
    private $notificationResource;

    /**
     * @var OrderPaymentRepositoryInterface
     */
    private $orderPaymentRepository;

    /**
     * @var BankManagement
     */
    private $bankManagement;

    /**
     * @var PsrLoggerInterface
     */
    private $logger;

    /**
     * PlaceAfterPlugin constructor.
     *
     * @param NotificationResource $notificationResource
     * @param Notification $notification
     * @param OrderPaymentRepositoryInterface $orderPaymentRepository
     * @param BankManagement $bankManagement
     * @param PsrLoggerInterface $logger
     */
    public function __construct(
        NotificationResource $notificationResource,
        Notification $notification,
        OrderPaymentRepositoryInterface $orderPaymentRepository,
        BankManagement $bankManagement,
        PsrLoggerInterface $logger
    ) {
        $this->notificationResource = $notificationResource;
        $this->notification = $notification;
        $this->orderPaymentRepository = $orderPaymentRepository;
        $this->bankManagement = $bankManagement;
        $this->logger = $logger;
    }

    /**
     * After plugin for place order
     *
     * @param \Magento\Sales\Api\OrderManagementInterface $orderManagementInterface
     * @param \Magento\Sales\Model\Order\Interceptor $order
     * @return \Magento\Sales\Model\Order\Interceptor $order
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     */
    public function afterPlace(OrderManagementInterface $orderManagementInterface, $order)
    {
        try {
            $payment = $this->orderPaymentRepository->get($order->getPayment()->getId());
            $payment->setAdditionalInformation(
                'bank_requisites',
                $this->bankManagement->prepareInstructions($order->getStoreId())
            );
            $this->orderPaymentRepository->save($payment);
        } catch (NoSuchEntityException $e) {
                $this->logger->critical($e);
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }

        try {
            $this->notification->setOrderId($order->getId())
                ->setEmailReminderSent(0);
            $this->notificationResource->save($this->notification);
        } catch (CouldNotSaveException $e) {
            $this->logger->critical($e);
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }

        return $order;
    }
}
