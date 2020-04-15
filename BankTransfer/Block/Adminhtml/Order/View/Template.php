<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Block\Adminhtml\Order\View;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\UrlInterface;

/**
 * Class Template
 *
 * @package Hello\BankTransfer\Block\Adminhtml\Order\View
 */
class Template extends \Magento\Backend\Block\Template
{
    /**
     * @var string
     */
    private $saveUrl = 'banktransfer/transaction/save';

    /**
     * @var string
     */
    private $backUrl = 'sales/order/view';

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    private $backendUrl;

    /**
     * Template constructor.
     *
     * @param Context $context
     * @param UrlInterface $backendUrl
     */
    public function __construct(
        Context $context,
        UrlInterface $backendUrl
    ) {
        parent::__construct($context);
        $this->backendUrl = $backendUrl;
    }

    /**
     * Retrieve save URL
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->backendUrl->getUrl(
            $this->saveUrl,
            ['order_id' => $this->getRequest()->getParam('order_id')]
        );
    }

    /**
     * Retrieve back URL
     *
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->backendUrl->getUrl(
            $this->backUrl,
            ['order_id' => $this->getRequest()->getParam('order_id')]
        );
    }
}
