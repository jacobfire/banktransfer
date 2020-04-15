<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\Bank\Controller\Adminhtml\Requisites;

use Magento\Backend\App\Action;

/**
 * Class NewAction
 *
 * @package Hello\Bank\Controller\Adminhtml\Requisites
 */
class NewAction extends Action
{
    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    private $resultForwardFactory;

    /**
     * ACL resource
     *
     * @var string
     */
    const ADMIN_RESOURCE = 'Hello_Bank::management';

    /**
     * NewAction constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    /**
     * Create new bank requisites
     *
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
