<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\Bank\Controller\Adminhtml\Requisites;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\NoSuchEntityException;
use Hello\Bank\Model\BankFactory;
use Hello\Bank\Model\BankRepository;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

/**
 * Class Save
 *
 * @package Hello\Bank\Controller\Adminhtml\Requisites
 */
class Save extends Action
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var \Hello\Bank\Model\Bank
     */
    private $bankModelFactory;

    /**
     * @var BankRepository
     */
    private $bankRepository;

    /**
     * @var PsrLoggerInterface
     */
    private $logger;

    /**
     * ACL resource
     *
     * @var string
     */
    const ADMIN_RESOURCE = 'Hello_Bank::management';

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param BankFactory $bankModelFactory
     * @param BankRepository $bankRepository
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        BankFactory $bankModelFactory,
        BankRepository $bankRepository,
        PsrLoggerInterface $logger
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->bankModelFactory = $bankModelFactory;
        $this->bankRepository = $bankRepository;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getParams();
        $model = null;
        if ($data) {
            $id = $this->getRequest()->getParam('row_id');

            if (empty($data['row_id'])) {
                $data['row_id'] = null;
            }

            try {
                if ($id) {
                    $model = $this->bankRepository->getById($id);
                } else {
                    $model = $this->bankModelFactory->create();
                }
                $model->setData($data);
                $this->bankRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the requisite.'));
                $this->dataPersistor->clear('bank');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['row_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (NoSuchEntityException $e) {
                $this->messageManager
                    ->addExceptionMessage($e, __('Such bank requisite with ID %d does not exist', $id));
                $this->logger->critical($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager
                    ->addExceptionMessage($e, __('Something wrong while loading the requisites'));
                $this->logger->critical($e->getMessage());
            }

            $this->dataPersistor->set('bank', $data);
            return $resultRedirect->setPath(
                '*/*/edit',
                ['row_id' => $this->getRequest()->getParam('row_id')]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }
}
