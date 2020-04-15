<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\Bank\Controller\Adminhtml\Requisites;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Edit
 *
 * @package Hello\Bank\Controller\Adminhtml\Requisites
 */
class Edit extends Action
{
    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * ACL resource
     *
     * @var string
     */
    const ADMIN_RESOURCE = 'Hello_Bank::management';

    /**
     * Edit constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Requisites form
     *
     * @return Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE);
        $resultPage->getConfig()->getTitle()->prepend(__('Bank Requisite Editing'));
        return $resultPage;
    }
}
