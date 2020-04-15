<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Model\System\Config\Backend;

/**
 * Class FinanceReportTime
 *
 * @package Hello\BankTransfer\Model\System\Config\Backend
 */
class FinanceReportTime extends \Magento\Framework\App\Config\Value
{
    /**
     * Cron expression configuration path
     */
    const CRON_STRING_PATH = 'payment/banktransfer/cron_time';

    /**
     * @var \Magento\Framework\App\Config\ValueFactory
     */
    private $configValueFactory;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Config\ValueFactory $configValueFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Config\ValueFactory $configValueFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->configValueFactory = $configValueFactory;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * Add cron task
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterSave()
    {
        $time = $this->getData('groups/banktransfer/fields/time/value');

        $cronExprArray = [
            intval($time[1]),
            intval($time[0]),
            '*',
            '*',
            '*',
        ];

        $cronExprString = join(' ', $cronExprArray);

        try {
            $this->configValueFactory->create()->load(
                self::CRON_STRING_PATH,
                'path'
            )->setValue(
                $cronExprString
            )->setPath(
                self::CRON_STRING_PATH
            )->save();
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('We were unable to save the cron expression.'));
        }
        return parent::afterSave();
    }
}
