<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */
namespace Hello\BankTransfer\Plugin\OfflinePayments\Model;

use Hello\BankTransfer\Services\ConfigurationService;

/**
 * Class BankTransferObserver
 *
 * @package Hello\BankTransfer\Observer
 */
class BanktransferPlugin
{
    /**
     * @var ConfigurationService
     */
    private $configuration;

    /**
     * BankTransferObserver constructor.
     *
     * @param ConfigurationService $configurationService
     */
    public function __construct(
        ConfigurationService $configurationService
    ) {
        $this->configuration = $configurationService;
    }

    /**
     * Check if method available
     *
     * @param \Magento\OfflinePayments\Model\Banktransfer $context
     * @param \Closure $proceed
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return int
     */
    public function aroundIsAvailable($context, $proceed, $quote)
    {
        $result = $proceed();
        if ($result) {
            return $this->configuration->isAvailableForQuote($quote);
        }
        return $result;
    }
}
