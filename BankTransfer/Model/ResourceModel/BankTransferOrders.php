<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Model\ResourceModel;

use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;
use Hello\BankTransfer\Services\ConfigurationService;
use Magento\Framework\App\ResourceConnection;
use Magento\Sales\Model\Order;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class BankTransferDaily
 *
 * @package Hello\BankTransfer\Model\ResourceModel
 */
class BankTransferOrders
{
    /**
     * @var ConfigurationService
     */
    private $configurationService;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * Pending status of order
     */
    const ORDER_PENDING_STATUS = 'pending';

    /**
     * For not expired orders
     */
    const STATUS_NOT_EXPIRED = 0;

    /**
     * For expired orders
     */
    const STATUS_EXPIRED = 1;

    /**
     * For daily reports
     */
    const STATUS_DAILY = 2;

    /**
     * BankTransferOrders constructor.
     *
     * @param ConfigurationService $configurationService
     * @param ResourceConnection $resourceConnection
     * @param OrderRepositoryInterface $orderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ConfigurationService $configurationService,
        ResourceConnection $resourceConnection,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->configurationService = $configurationService;
        $this->resourceConnection = $resourceConnection;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Retrieve collection of bank transfer orders
     *
     * @param string $expired
     * @return array|\Magento\Sales\Model\ResourceModel\Order\Collection|\Magento\Sales\Api\Data\OrderSearchResultInterface
     */
    public function getBankTransferOrders($expired)
    {
        $select = $this->getCollection($expired)->getSelect()->reset(\Zend_Db_Select::COLUMNS);
        $connection = $this->resourceConnection->getConnection();

        $salesOrderPaymentTable = $connection->getTableName("sales_order_payment");
        $banktransferNotificationTable = $connection->getTableName("hello_banktransfer_notification");
        $select
            ->columns(['main_table.entity_id', 'main_table.created_at',
                'main_table.customer_firstname', 'main_table.customer_lastname',
                'main_table.grand_total',
                'UNIX_TIMESTAMP(main_table.created_at) as place_time'])
            ->join(['payment' => $salesOrderPaymentTable],
                'main_table.entity_id = payment.parent_id',
                ['method']
            )
            ->joinLeft(['notification' => $banktransferNotificationTable],
                'main_table.entity_id = notification.order_id'
            );
        $select->where("payment.method = ?", ConfigurationService::METHOD_CODE);

        if ($expired === self::STATUS_NOT_EXPIRED) {
            $delay = $this->configurationService->getDelayInSeconds();
            $exprTime = new \Zend_Db_Expr("(`place_time` + $delay) > UNIX_TIMESTAMP(NOW())");
            $select->having($exprTime);
            $select->where("notification.email_reminder_sent IS NULL");
        } elseif ($expired === self::STATUS_EXPIRED) {
            $select->where("notification.email_reminder_sent = ?", ConfigurationService::PAYMENT_NOTIFICATION_SENT);
        }
        $result = $connection->fetchAll($select);

        if (in_array($expired, [self::STATUS_NOT_EXPIRED, self::STATUS_EXPIRED])) {
            return $this->loadCollection($result);
        }
        return $result;
    }

    /**
     * Retrieve collection depending on period of creation
     *
     * @param string $expired
     * @return \Magento\Sales\Api\Data\OrderSearchResultInterface
     */
    public function getCollection($expired)
    {
        if ($expired === self::STATUS_EXPIRED) {
            $startPeriod = $this->configurationService->getOrderExpirationPeriod() + 6; //check orders for 5 days
            $endPeriod = $this->configurationService->getOrderExpirationPeriod();
            $startTime = strtotime(sprintf('-%d day', $startPeriod), strtotime(date("Y-m-d h:i:s")));
            $endTime = strtotime(sprintf('-%d day', $endPeriod), strtotime(date("Y-m-d h:i:s")));
            $to = date('Y-m-d h:i:s', $endTime);
            $from = date('Y-m-d h:i:s', $startTime);
            return $this->getCurrentCollection($from, $to, true);
        } elseif ($expired === self::STATUS_NOT_EXPIRED) {
            $expirationPeriod = $this->configurationService->getOrderExpirationPeriod();
            $to = date("Y-m-d h:i:s"); // current date
            $time = strtotime(sprintf('-%d day', $expirationPeriod), strtotime($to));
            $from = date('Y-m-d h:i:s', $time);
            return $this->getCurrentCollection($from, $to, true);
        } else {
            //for daily collection
            $expirationPeriod = 1; // checking for daily report
            $to = date("Y-m-d h:i:s"); // current date
            $time = strtotime(sprintf('-%d day', $expirationPeriod), strtotime($to));
            $from = date('Y-m-d h:i:s', $time);
            return $this->getCurrentCollection($from, $to, false);
        }
    }

    /**
     * Retrieve collection depending on statuses and/or state and date
     *
     * @param int $from
     * @param int $to
     * @param bool $expiration
     * @return \Magento\Sales\Api\Data\OrderSearchResultInterface
     */
    public function getCurrentCollection($from, $to, $expiration = false)
    {
        $collection = $this->orderRepository
            ->getList($this->searchCriteriaBuilder
                ->addFilter('state', Order::STATE_NEW, 'eq')
                ->addFilter('created_at', $from, 'gt')
                ->addFilter('created_at', $to, 'lt')
                ->create());

        return $collection;
    }

    /**
     * Retrieve collection for report
     *
     * @return array
     */
    public function getFinanceReportOrdersInfo()
    {
        return $this->getBankTransferOrders(self::STATUS_DAILY);
    }

    /**
     * Load collection of orders by ids
     *
     * @param array $orderInfo
     * @return null|\Magento\Sales\Api\Data\OrderSearchResultInterface
     */
    private function loadCollection($orderInfo)
    {
        if (is_array($orderInfo)) {
            $orderIds = [];
            foreach ($orderInfo as $order) {
                $orderIds[] = $order['entity_id'];
            }

            return $this->orderRepository
                ->getList($this->searchCriteriaBuilder
                    ->addFilter('entity_id', $orderIds, 'in')
                    ->create());
        }
        return null;
    }
}
