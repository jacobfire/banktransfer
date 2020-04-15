<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Model\Reports;

use Magento\Store\Model\StoreManagerInterface;
use Hello\BankTransfer\Services\NotificationService;
use Magento\Framework\File\Csv;
use Magento\Framework\App\Filesystem\DirectoryList;
use Hello\BankTransfer\Model\ResourceModel\BankTransferOrders;
use Hello\BankTransfer\Model\Mailer;
use Magento\Framework\Filesystem\Io\File;

/**
 * Class FinanceReport
 *
 * @package Hello\BankTransfer\Model\Reports
 */
class FinanceReport
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $notification;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Csv
     */
    private $csvProcessor;

    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * @var BankTransferOrders
     */
    private $bankTransferOrders;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var File
     */
    private $filesystem;

    /**
     * template path
     */
    const XML_PATH_EMAIL_TEMPLATE_FIELD  = 'payment/banktransfer/reminder_email_template';

    /**
     * Report filename
     */
    const FINANCE_REPORT_FILENAME = 'finance_report.csv';

    /**
     * Name of folder where we store reports
     */
    const REPORT_FOLDER = 'finance_report';

    /**
     * FinanceReport constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param Csv $csvProcessor
     * @param NotificationService $notificationService
     * @param DirectoryList $directoryList
     * @param BankTransferOrders $bankTransferOrders
     * @param Mailer $mailer
     * @param File $fileIo
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Csv $csvProcessor,
        NotificationService $notificationService,
        DirectoryList $directoryList,
        BankTransferOrders $bankTransferOrders,
        Mailer $mailer,
        File $fileIo
    ) {
        $this->notification = $notificationService;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->storeManager = $storeManager;
        $this->bankTransferOrders = $bankTransferOrders;
        $this->mailer = $mailer;
        $this->filesystem = $fileIo;
    }

    /**
     * Prepare CSV file for finance report and send
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function prepareReport()
    {
        $this->prepareFolder();
        $prefix = date('Y_m_d');
        $filePath = $this->directoryList->getPath(DirectoryList::VAR_DIR)
            . '/' . self::REPORT_FOLDER . "/" . $prefix . '_' . self::FINANCE_REPORT_FILENAME;

        $this->csvProcessor
            ->setDelimiter(',')
            ->setEnclosure('"')
            ->saveData(
                $filePath,
                $this->getFinanceReportData()
            );

        $this->notification->sendReport(file_get_contents($filePath));
    }

    /**
     * Prepare order data for csv file
     *
     * @return array
     */
    private function getFinanceReportData()
    {
        $result = [];
        $orders = $this->getNotPaidOrders();
        $result[] = [
            'Order ID',
            'Buyer Name',
            'Total Amount',
            'Order Date',
        ];

        foreach ($orders as $order) {
            $result[] = [
                $order['entity_id'],
                sprintf('%s %s', $order['customer_firstname'], $order['customer_lastname']),
                $order['grand_total'],
                $order['created_at']
            ];
        }

        return $result;
    }

    /**
     * Retrieve data for report
     *
     * @return array
     */
    public function getNotPaidOrders()
    {
        return $this->bankTransferOrders->getFinanceReportOrdersInfo();
    }

    /**
     * Check if folder not exist then create
     *
     * @return  void
     */
    public function prepareFolder()
    {
        $financeReportFolder = $this->directoryList->getPath(DirectoryList::VAR_DIR)
            . '/' . self::REPORT_FOLDER;
        if (!file_exists($financeReportFolder)) {
            $this->filesystem->mkdir($financeReportFolder, 0775);
        }
    }
}
