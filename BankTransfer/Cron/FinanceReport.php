<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Cron;

use Hello\BankTransfer\Model\Reports\FinanceReport as FinanceReportModel;

/**
 * Class FinanceReport
 *
 * @package Hello\BankTransfer\Cron
 */
class FinanceReport
{
    /**
     * @var \Hello\BankTransfer\Cron\FinanceReport
     */
    private $financeReport;

    /**
     * FinanceReport constructor.
     *
     * @param FinanceReportModel $financeReport
     */
    public function __construct(
        FinanceReportModel $financeReport
    ) {
        $this->financeReport = $financeReport;
    }

    /**
     * Fetch active BankTransfer order and send email report
     *
     * @return  void
     */
    public function execute()
    {
        $this->financeReport->prepareReport();
    }
}
