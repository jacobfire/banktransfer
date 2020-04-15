<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Controller\Adminhtml\Transaction;

use Magento\Backend\App\Action\Context;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\TransactionInterface as Transaction;
use Magento\Sales\Api\TransactionRepositoryInterface as TransactionRepository;
use Magento\Sales\Model\Order\Payment\Transaction as TransactionModel;
use Hello\BankTransfer\Model\Order\Status as OrderStatus;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

/**
 * Class Add
 *
 * @package Hello\BankTransfer\Controller\Adminhtml\Transaction
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Hello_BankTransfer::transaction_save';

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var TransactionRepository
     */
    private $transactionRepository;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var OrderStatus
     */
    private $orderStatus;

    /**
     * @var PsrLoggerInterface
     */
    private $logger;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param Transaction $transaction
     * @param TransactionRepository $transactionRepository
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderStatus $orderStatus
     * @param PsrLoggerInterface $logger
     */
    public function __construct(
        Context $context,
        Transaction $transaction,
        TransactionRepository $transactionRepository,
        OrderRepositoryInterface $orderRepository,
        OrderStatus $orderStatus,
        PsrLoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->transaction = $transaction;
        $this->transactionRepository = $transactionRepository;
        $this->orderRepository = $orderRepository;
        $this->orderStatus = $orderStatus;
        $this->logger = $logger;
    }

    /**
     * Save parameters
     *
     * @return \Magento\Framework\Controller\ResultInterface|\Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $order = null;
        try {
            if ($orderId) {
                $order = $this->orderRepository->get($orderId);
            }
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addExceptionMessage($e, __("Can't load the order. The order doesn't exist"));
            $this->logger->critical($e->getMessage());
            return $this->_redirect('*/*');
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __("Can't process the saving"));
            $this->logger->critical($e->getMessage());
            return $this->_redirect('*/*');
        }

        $txnId = $this->getRequest()->getParam('txn_id');
        if (!$txnId) {
            $this->messageManager->addErrorMessage(__('Please specify transaction ID'));
            return $this->_redirect('*/*/add', ['order_id' => $this->getRequest()->getParam('order_id')]);
        }

        if ($orderId && $order->getPayment() && $order->getPayment()->getEntityId()) {
            try {
                $this->transaction
                    ->setTransactionId(null)
                    ->setParentId(null)
                    ->setOrderId($order->getEntityId())
                    ->setPaymentId($order->getPayment()->getEntityId())
                    ->setTxnId($txnId)
                    ->setParentTxnId(null)
                    ->setTxnType(TransactionModel::TYPE_CAPTURE)
                    ->setIsClosed(1)
                    ->setCreatedAt(null);

                $this->transactionRepository->save($this->transaction);
                $this->orderStatus->changeStatus($order);

                $this->messageManager
                    ->addSuccessMessage(__('Transaction has been saved to the transaction history!'));
            } catch(\Exception $e) {
                $this->messageManager
                    ->addErrorMessage(__('Something went wrong while saving: %s', $e->getMessage()));
                $this->logger->critical($e->getMessage());
            }
        }

        return $this->_redirect('sales/order/view', ['order_id' => $this->getRequest()->getParam('order_id')]);
    }

    /**
     * ACL restriction for resources
     *
     * @return bool
     */
    protected function _isAllowed() {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }
}
