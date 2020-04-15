<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Controller\Adminhtml\Transaction;

/**
 * Class Add
 *
 * @package Hello\BankTransfer\Controller\Adminhtml\Transaction
 */
class Add extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Hello_BankTransfer::transaction_add';

    /**
     * Show form for saving
     *
     * @return \Magento\Framework\Controller\ResultInterface|\Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        if (!$this->getRequest()->getParam('order_id')) {
            return $this->_redirect('*/*');
        }
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }

    /**
     * ACL restriction for resources
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }
}
