<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Services;

use Hello\Config\Model\Condition\Applier\QuoteFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Hello\Config\Model\Condition\Data\Transform;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class ConfigurationService
 *
 * @package Hello\BankTransfer\Services
 */
class ConfigurationService
{
    /**
     * Bank Transfer method code
     */
    const METHOD_CODE = 'banktransfer';

    /**
     * Flag of expired order
     */
    const PAYMENT_NOTIFICATION_SENT = 1;

    /**
     * Conditions of availability of bank transfer
     */
    const XML_PATH_QUOTE_PRODUCT_CONDITIONS = 'payment/banktransfer/quote_product_conditions';

    /**
     * How many days before order expires and we need to cancel
     */
    const XML_PATH_EXPIRATION_DAYS = 'payment/banktransfer/expire_days';

    /**
     * Bank transfer new order status
     */
    const XML_PATH_NEW_ORDER_STATUS = 'payment/banktransfer/order_status';

    /**
     * Reminder email template ID
     */
    const XML_PATH_REMINDER_TEMPLATE_ID = 'payment/banktransfer/reminder_email';

    /**
     * Comma separated emails
     */
    const XML_PATH_REPORT_RECIPIENT = 'payment/banktransfer/report_recipient';

    /**
     * Reminder Time Delay
     */
    const XML_PATH_REMINDER_TIME_DELAY = 'payment/banktransfer/reminder_time_delay';

    /**
     * Reminder email template's path
     */
    const XML_PATH_REMINDER_EMAIL_TEMPLATE = 'payment/banktransfer/banktransfer_reminder_email_template';

    /**
     * Report template
     */
    const XML_PATH_REPORT_EMAIL_TEMPLATE = 'payment/banktransfer/banktransfer_report_email_template';

    /**
     * Name of sender
     */
    const XML_PATH_SENDER_NAME = 'trans_email/ident_sales/name';

    /**
     * Email of sender
     */
    const XML_PATH_SENDER_EMAIL = 'trans_email/ident_sales/email';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var QuoteFactory
     */
    private $quoteFactory;

    /**
     * @var Transform
     */
    private $transform;

    /**
     * ConfigurationService constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param QuoteFactory $quoteFactory
     * @param Transform $transform
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        QuoteFactory $quoteFactory,
        Transform $transform
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->quoteFactory = $quoteFactory;
        $this->transform = $transform;
    }

    /**
     * Check if method is available for current quote
     *
     * @param CartInterface $quote
     * @return bool
     */
    public function isAvailableForQuote($quote)
    {
        if (empty($quote)) {
            return true;
        }

        $data = $this->scopeConfig->getValue(self::XML_PATH_QUOTE_PRODUCT_CONDITIONS);

        if (empty($data['rows']) || empty($data['scope_logic'])) {
            return true;
        }

        $conditions = $this->transform->initConditionsFromSystemXml($data['rows']);
        /** @var  \Hello\Config\Model\Condition\Applier\Quote $conditionQuoteValidator */
        $conditionQuoteValidator = $this->quoteFactory->create([
            'scope' => $quote,
            'conditions' => $conditions,
            'strategy' => $data['scope_logic']
        ]);

        return $conditionQuoteValidator->isMatch();
    }

    /**
     * Retrieve new order status
     *
     * @return string
     */
    public function getNewOrderStatus()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_NEW_ORDER_STATUS, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve expiration days
     *
     * @return int
     */
    public function getOrderExpirationPeriod()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_EXPIRATION_DAYS, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve an array of email addresses
     *
     * @return array
     */
    public function getReportRecipient()
    {
        return explode(',', trim($this->scopeConfig
            ->getValue(self::XML_PATH_REPORT_RECIPIENT, ScopeInterface::SCOPE_STORE)));
    }

    /**
     * Retrieve time in hours
     * after this amount we need to send a reminder
     *
     * @return int
     */
    public function getReminderTimeDelay()
    {
        return $this->scopeConfig
            ->getValue(self::XML_PATH_REMINDER_TIME_DELAY, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve delay time in seconds
     *
     * @return int
     */
    public function getDelayInSeconds()
    {
        return 60 * 60 * $this->getReminderTimeDelay();
    }

    /**
     * Retrieve reminder email template
     *
     * @return int
     */
    public function getReminderEmailTemplate()
    {
        return $this->scopeConfig
            ->getValue(self::XML_PATH_REMINDER_EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve Sales Representative Contact Name
     *
     * @return string
     */
    public function getStoreRepresentiveName()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SENDER_NAME, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve Sales Representative Contact Email
     *
     * @return string
     */
    public function getStoreRepresentiveEmail()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SENDER_EMAIL, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve report template
     *
     * @return int|string
     */
    public function getReportTemplate()
    {
        return $this->scopeConfig
            ->getValue(self::XML_PATH_REPORT_EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE);
    }
}
