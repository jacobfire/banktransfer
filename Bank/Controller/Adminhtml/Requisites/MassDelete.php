<?php
/**
 * Copyright © 2017 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\Bank\Controller\Adminhtml\Requisites;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Hello\Bank\Model\ResourceModel\Bank\CollectionFactory;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

/**
 * Class massDelete
 *
 * @package Hello\Bank\Controller\Adminhtml\Requisites
 */
class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var PsrLoggerInterface
     */
    private $logger;

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Hello_Bank::manage';

    /**
     * MassDelete constructor.
     *
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param PsrLoggerInterface $logger
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        PsrLoggerInterface $logger
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $collectionSize = $collection->getSize();
            foreach ($collection as $item) {
                $item->delete();
            }
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            $this->messageManager->addErrorMessage("Can't delete bank requisites you selected");
            return $resultRedirect->setPath('*/*/');
        }

        $this->messageManager
            ->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));

        return $resultRedirect->setPath('*/*/');
    }
}
