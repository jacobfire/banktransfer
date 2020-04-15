<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Model\Order;

use Hello\OrderManager\Model\Status as OrderStatus;
use Magento\Sales\Model\Order as Order;
use Magento\Sales\Api\OrderRepositoryInterface as OrderRepository;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

/**
 * Class Status
 *
 * @package Hello\BankTransfer\Model
 */
class Status
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var PsrLoggerInterface
     */
    private $logger;

    /**
     * Status constructor.
     *
     * @param OrderRepository $orderRepository
     * @param PsrLoggerInterface $logger
     */
    public function __construct(
        OrderRepository $orderRepository,
        PsrLoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->logger = $logger;
    }

    /**
     * Save order model
     *
     * @param \Magento\Sales\Model\Order|\Magento\Sales\Api\Data\OrderInterface $order
     */
    public function changeStatus($order)
    {
        try {
            $order->setStatus(OrderStatus::STATUS_PAYMENT_SETTLED)
                ->setState(Order::STATE_PROCESSING);
            $this->orderRepository->save($order);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
